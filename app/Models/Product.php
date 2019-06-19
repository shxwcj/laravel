<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Product extends Model
{
    //定义商品的2种状态
    const TYPE_NORMAL = 'normal';
    const TYPE_CROWDFUNDING = 'crowdfunding';

    public static $typeMap = [
        self::TYPE_NORMAL  => '普通商品',
        self::TYPE_CROWDFUNDING => '众筹商品',
    ];

   protected $fillable = ['title','description','image','on_sale','rating','sold_count','review_count','type'];

   protected $casts = ['on_sale' => 'boolean'];  //on_sale 是一个布尔类型的字段

   public function skus()
   {
       return $this->hasMany(ProductSku::class);
   }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function crowdfunding()
    {
        return $this->hasOne(CrowdfundingProduct::class);
    }

    /**
     * @desc 获取图片链接属性 image_url下划线的形式会被解析成驼峰式命名
     * @return mixed
     */
   public function getImageUrlAttribute()
   {
       // 如果 image 字段本身就已经是完整的 url 就直接返回
       if (Str::startsWith($this->attributes['image'],['http://','https://'])){ //Str::startWith:确定给定的字符串是否以给定的子字符串开始
           return $this->attributes['image'];
       }
       return \Storage::disk('public')->url($this->attributes['image']); //由于创建图片软连接 故要映射到storage目录下
   }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = strip_tags($value);
    }
}
