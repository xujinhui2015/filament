<?php

namespace Database\Seeders\Mall;

use App\Enums\MakeOrderNoEnum;
use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Models\Customer\Customer;
use App\Models\Mall\MallGoods;
use App\Models\Mall\MallGoodsSku;
use App\Models\Mall\MallOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Random\RandomException;

class MallOrderSeeder extends Seeder
{

    /**
     * @throws RandomException
     */
    public function run(): void
    {
        for ($i = 0; $i < 15; $i++) {
            DB::beginTransaction();
            $this->makeOrder();
            DB::commit();
        }
    }

    /**
     * @throws RandomException
     */
    private function makeOrder(): void
    {
        $order = MallOrder::query()
            ->create([
                'customer_id' => Customer::query()->inRandomOrder()->value('id'),
                'order_no' => MakeOrderNoEnum::MallOrder->new(),
                'order_status' => MallOrderOrderStatusEnum::Pay,
                'order_money' => random_int(500, 600),
                'order_fact_money' => random_int(300, 500),
                'order_source' => random_int(0, 1),
                'payment' => random_int(0, 1),
                'name' => fake()->name(),
                'phone' => fake_phone(),
                'province' => '广东省',
                'city' => '广州市',
                'district' => '番禺区',
                'address' => fake()->address(),
                'last_pay_time' => fake()->dateTime(),
                'pay_time' => fake()->dateTime(),
            ]);

        $goods = MallGoods::query()->inRandomOrder()->first();
        /** @var MallGoodsSku $goodsSku */
        $goodsSku = $goods->sku()->inRandomOrder()->first();
        $goodsMoney = $order->order_fact_money - random_int(100, 200);

        $goodsTwo = MallGoods::query()->where('id', '!=', $goods->id)->inRandomOrder()->first();
        /** @var MallGoodsSku $goodsTwoSku */
        $goodsTwoSku = $goodsTwo->sku()->inRandomOrder()->first();
        $goodsTwoMoney = $order->order_fact_money - $goodsMoney;

        $order->detail()->createMany([
            [
                'goods_id' => $goods->id,
                'goods_sku_id' => $goodsSku->id,
                'goods_name' => $goods->goods_name,
                'goods_spec' => $goodsSku->spec,
                'goods_image' => $goodsSku->sku_img ?: $goods->main_img,
                'goods_price' => $goodsMoney,
                'goods_number' => 1,
            ],
            [
                'goods_id' => $goodsTwo->id,
                'goods_sku_id' => $goodsTwoSku->id,
                'goods_name' => $goodsTwo->goods_name,
                'goods_spec' => $goodsTwoSku->spec,
                'goods_image' => $goodsSku->sku_img ?: $goods->main_img,
                'goods_price' => $goodsTwoMoney,
                'goods_number' => 1,
            ]
        ]);

    }
}
