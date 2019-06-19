<?php
namespace App\Http\Controllers;

use App\Events\OrderReviewed;
use App\Exceptions\CouponCodeUnavailableException;
use App\Exceptions\InternalException;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\Admin\HandleRefundRequest;
use App\Http\Requests\ApplyRefundRequest;
use App\Http\Requests\CrowdFundingOrderRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\SendReviewRequest;
use App\Jobs\CloseOrder;
use App\Models\CouponCode;
use App\Models\ProductSku;
use App\Models\UserAddress;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * @desc 订单添加 未封装前
     * @param OrderRequest $request
     * @return mixed
     */
//    public function store(OrderRequest $request)
//    {
//        $user  = $request->user();
//        // 开启一个数据库事务
//        $order = \DB::transaction(function () use ($user, $request) {
//            $address = UserAddress::find($request->input('address_id'));
//            // 更新此地址的最后使用时间
//            $address->update(['last_used_at' => Carbon::now()]);
//            // 创建一个订单
//            $order   = new Order([
//                'address'      => [ // 将地址信息放入订单中
//                    'address'       => $address->full_address,
//                    'zip'           => $address->zip,
//                    'contact_name'  => $address->contact_name,
//                    'contact_phone' => $address->contact_phone,
//                ],
//                'remark'       => $request->input('remark'),
//                'total_amount' => 0,
//            ]);
//            // 订单关联到当前用户
//            $order->user()->associate($user);
//            // 写入数据库
//            $order->save();
//
//            $totalAmount = 0;
//            $items = $request->input('items');
//            // 遍历用户提交的 SKU
//            foreach ($items as $data) {
//                $sku  = ProductSku::find($data['sku_id']);
//                // 创建一个 OrderItem 并直接与当前订单关联
//                $item = $order->items()->make([
//                    'amount' => $data['amount'],
//                    'price'  => $sku->price,
//                ]);
//                $item->product()->associate($sku->product_id);
//                $item->productSku()->associate($sku);
//                $item->save();
//                $totalAmount += $sku->price * $data['amount'];
//                if ($sku->decreaseStock($data['amount']) <=0){
//                    throw new InternalException('该商品库存不足');
//                }
//            }
//            // 更新订单总金额
//            $order->update(['total_amount' => $totalAmount]);
//
//            // 将下单的商品从购物车中移除 CartController封装前的代码
////            $skuIds = collect($items)->pluck('sku_id');
////            $user->cartItems()->whereIn('product_sku_id', $skuIds)->delete();
//            //CartController封装后的代码
//            $skuIds = collect($request->input('items'))->pluck('sku_id')->all();
//            $cartService = new CartService();
//            $cartService->remove($skuIds);
//            return $order;
//        });
//        $this->dispatch(new CloseOrder($order,config('app.order_ttl')));
//        return $order;
//    }

    //封装订单添加代码
    public function store(OrderRequest $request,OrderService $orderService)
    {
        $user = $request->user();
        $address = UserAddress::find($request->address_id);
        $coupon  = null;
        // 如果用户提交了优惠码
        if ($code = $request->input('coupon_code')) {
            $coupon = CouponCode::where('code', $code)->first();
            if (!$coupon) {
                throw new CouponCodeUnavailableException('优惠券不存在');
            }
        }
        return $orderService->store($user,$address,$request->remark,$request->items,$coupon);
    }

    /**
     * @desc 订单列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $orders = Order::query()
        // 使用 with 方法预加载，避免N + 1问题
            ->with(['items.product','items.productSku'])
            ->where('user_id',$request->user()->id)
            ->orderBy('created_at','desc')
            ->paginate();
        return view('orders.index',['orders'=>$orders]);
    }

    /**
     * @desc 订单详情
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Order $order)
    {
        $this->authorize('own',$order);
        return view('orders.show', ['order' => $order->load(['items.productSku', 'items.product'])]);
    }

    /**
     * 订单确认收货
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws InvalidRequestException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function received(Order $order,Request $request)
    {
        $this->authorize('own',$order);
        //判断订单的发货状态是否为已发货
        if ($order->ship_status !== Order::SHIP_STATUS_DELIVERED){
            throw new InvalidRequestException('发货状态不正确');
        }
        // 更新发货状态为已收到
        $order->update(['ship_status'=> Order::SHIP_STATUS_RECEIVED]);

        return $order;
    }

    /**
     * @desc 订单评价页面
     * @param Order $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws InvalidRequestException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function review(Order $order)
    {
        // 校验权限
        $this->authorize('own', $order);
        // 判断是否已经支付
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付，不可评价');
        }
        // 使用 load 方法加载关联数据，避免 N + 1 性能问题
        return view('orders.review', ['order' => $order->load(['items.productSku', 'items.product'])]);
    }

    /**
     * @desc 订单评价
     * @param Order $order
     * @param SendReviewRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws InvalidRequestException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function sendReview(Order $order,SendReviewRequest $request)
    {
         // 校验权限
        $this->authorize('own', $order);
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付，不可评价');
        }
        // 判断是否已经评价
        if ($order->reviewed) {
            throw new InvalidRequestException('该订单已评价，不可重复提交');
        }
        $reviews = $request->input('reviews');
        // 开启事务
        \DB::transaction(function () use ($reviews, $order) {
            // 遍历用户提交的数据
            foreach ($reviews as $review) {
                $orderItem = $order->items()->find($review['id']);
                // 保存评分和评价
                $orderItem->update([
                    'rating'      => $review['rating'],
                    'review'      => $review['review'],
                    'reviewed_at' => Carbon::now(),
                ]);
            }
            // 将订单标记为已评价
            $order->update(['reviewed' => true]);
            event(new OrderReviewed($order)); //触动事件监听
        });
        return redirect()->back();
    }

    /**
     * @desc 申请退款
     * @param Order $order
     * @param ApplyRefundRequest $request
     * @return Order
     * @throws InvalidRequestException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function applyRefund(Order $order,ApplyRefundRequest $request)
    {
        //校验订单是否属于当前用户
        $this->authorize('own',$order);
        //判断订单是否付款
        if (!$order->paid_at){
            throw new InvalidRequestException('该订单未支付，不可退款');
        }
        //判断订单退款状态是否正确
        if ($order->refund_status !== Order::REFUND_STATUS_PENDING){
            throw new InvalidRequestException('该订单已经申请过退款，请勿重复申请');
        }
        //将用户输入的退款理由放知订单的extra字段中
        $extra                  = $order->extra ?: [];
        $extra['refund_reason'] = $request->reason;
        //将订单退款状态改为已申请退款
        $order->update([
           'extra'      => $extra,
           'refund_status' => Order::REFUND_STATUS_APPLIED,
        ]);
        return $order;
    }

    /**
     * 创建一个新的方法用于接受众筹商品下单请求
     * @param CrowdFundingOrderRequest $request
     * @param OrderService $orderService
     * @return mixed
     */
    public function crowdfunding(CrowdFundingOrderRequest $request,OrderService $orderService)
    {
        $user = $request->user();
        $sku = ProductSku::find($request->sku_id);
        $address = UserAddress::find($request->address_id);
        $amount = $request->amount;

        return $orderService->crowdfunding($user,$address,$sku,$amount);
    }
}