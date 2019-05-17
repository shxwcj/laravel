<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">订单流水号:{{$order->no}}</h3>
        <div class="box-tools">
            <div class="btn-group float-right" style="margin-right: 10px">
                <a href="{{route('admin.orders.index')}}" class="btn btn-sm btn-default">
                    <i class="fa fa-list">列表</i>
                </a>
            </div>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>买家:</td>
                    <td>{{$order->user->name}}</td>
                    <td>支付时间:</td>
                    <td>{{$order->paid_at->format('Y-m-d H:i:s')}}</td>
                </tr>
                <tr>
                    <td>收货地址</td>
                    <td colspan="3">{{ $order->address['address'] }} {{ $order->address['zip'] }} {{ $order->address['contact_name'] }} {{ $order->address['contact_phone'] }}</td>
                </tr>
                <tr>
                    <td rowspan="{{$order->items->count() +1}}">商品列表</td>
                    <td>商品名称</td>
                    <td>单价</td>
                    <td>数量</td>
                </tr>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{$item->product->title}} {{$item->productSku->title}}</td>
                        <td>￥{{$item->price}}</td>
                        <td>{{$item->amount}}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td>订单金额:</td>
                        <td colspan="3">￥{{$order->total_amount}}</td>
                    </tr>
            </tbody>
        </table>
    </div>
</div>