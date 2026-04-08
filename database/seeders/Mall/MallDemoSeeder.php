<?php

namespace Database\Seeders\Mall;

use App\Enums\Customer\CustomerBalanceSceneTypeEnum;
use App\Enums\Customer\CustomerPointsSceneTypeEnum;
use App\Enums\MakeOrderNoEnum;
use App\Enums\Mall\MallOrderAdjustAdjustTypeEnum;
use App\Enums\Mall\MallOrderOrderSourceEnum;
use App\Enums\Mall\MallOrderOrderStatusEnum;
use App\Enums\Mall\MallOrderPaymentEnum;
use App\Enums\Mall\MallOrderRefundRefundStatusEnum;
use App\Enums\Mall\MallOrderRefundRefundTypeEnum;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerAddress;
use App\Models\Mall\MallAttr;
use App\Models\Mall\MallCart;
use App\Models\Mall\MallExpress;
use App\Models\Mall\MallGoods;
use App\Models\Mall\MallGoodsCategory;
use App\Models\Mall\MallOrder;
use App\Models\Mall\MallOrderDetail;
use App\Models\Mall\MallOrderRefund;
use App\Models\Mall\MallRefundAddress;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MallDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedUsers();
            $customers = $this->seedCustomers();
            $this->seedBaseMallData();
            $goodsCollection = $this->seedGoods();
            $this->seedCartData($customers, $goodsCollection);
            $this->seedOrders($customers, $goodsCollection);
        });
    }

    private function seedUsers(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'phone' => fake_phone(),
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        User::factory()->count(5)->create();
    }

    private function seedCustomers(): Collection
    {
        $customers = Customer::factory()->count(30)->create();

        foreach ($customers as $customer) {
            $customer->wechat()->create([
                'mini_openid' => 'mini_openid_' . $customer->id . '_' . fake()->unique()->numerify('######'),
            ]);

            $addressCount = random_int(1, 3);
            for ($i = 0; $i < $addressCount; $i++) {
                CustomerAddress::query()->create([
                    'customer_id' => $customer->id,
                    ...$this->fakeAddressData(),
                ]);
            }

            $customer->balanceRecords()->createMany([
                [
                    'change_explain' => '系统初始化余额',
                    'scene_type' => CustomerBalanceSceneTypeEnum::Increase->value,
                    'balance' => $customer->balance,
                    'relation_id' => null,
                    'remark' => 'Seeder 初始化',
                ],
                [
                    'change_explain' => '系统扣减测试',
                    'scene_type' => CustomerBalanceSceneTypeEnum::Deducted->value,
                    'balance' => random_int(0, max(0, $customer->balance)),
                    'relation_id' => null,
                    'remark' => 'Seeder 扣减记录',
                ],
            ]);

            $customer->pointsRecords()->createMany([
                [
                    'change_explain' => '系统初始化积分',
                    'scene_type' => CustomerPointsSceneTypeEnum::Increase->value,
                    'points' => $customer->points,
                    'relation_id' => null,
                    'remark' => 'Seeder 初始化',
                ],
                [
                    'change_explain' => '系统扣减积分',
                    'scene_type' => CustomerPointsSceneTypeEnum::Deducted->value,
                    'points' => random_int(0, max(0, $customer->points)),
                    'relation_id' => null,
                    'remark' => 'Seeder 扣减记录',
                ],
            ]);
        }

        return $customers->map(function (Customer $customer) {
            $customer->setRelation(
                'seed_addresses',
                CustomerAddress::query()->where('customer_id', $customer->id)->get()
            );

            return $customer;
        });
    }

    private function seedBaseMallData(): void
    {
        foreach (['顺丰速运', '京东物流', '中通快递', '圆通速递', '韵达快递'] as $expressName) {
            MallExpress::query()->create(['express_name' => $expressName]);
        }

        foreach ([
            '颜色' => ['曜石黑', '雪山白', '海盐蓝'],
            '尺寸' => ['S', 'M', 'L', 'XL'],
            '容量' => ['128G', '256G', '512G'],
            '版本' => ['标准版', 'Pro', 'Max'],
        ] as $attrName => $values) {
            $attr = MallAttr::query()->create([
                'attr_name' => $attrName,
                'sort' => 0,
                'is_disabled' => false,
            ]);

            foreach ($values as $index => $valueName) {
                $attr->attrValue()->create([
                    'attr_value_name' => $valueName,
                    'sort' => $index,
                    'is_disabled' => false,
                ]);
            }
        }

        foreach (range(1, 3) as $i) {
            MallRefundAddress::query()->create($this->fakeAddressData('退货联系人' . $i));
        }
    }

    private function seedGoods(): Collection
    {
        $rootCategories = collect([
            '数码家电' => ['手机通讯', '电脑办公', '智能穿戴'],
            '家居日用' => ['厨房用品', '收纳清洁', '家纺布艺'],
            '食品生鲜' => ['休闲零食', '乳品烘焙', '时令水果'],
        ])->map(function (array $children, string $rootTitle) {
            $root = MallGoodsCategory::query()->create([
                'parent_id' => 0,
                'title' => $rootTitle,
                'sort' => 0,
                'is_disabled' => false,
            ]);

            return collect($children)->map(function (string $childTitle, int $index) use ($root) {
                return MallGoodsCategory::query()->create([
                    'parent_id' => $root->id,
                    'title' => $childTitle,
                    'sort' => $index,
                    'is_disabled' => false,
                ]);
            });
        })->flatten();

        return $rootCategories->map(function (MallGoodsCategory $category, int $index) {
            $goods = MallGoods::query()->create([
                'goods_sn' => 'SPU' . now()->format('ymd') . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'goods_category_id' => $category->id,
                'goods_name' => $category->title . fake()->randomElement(['精选款', '热销款', '轻享版']),
                'subtitle' => fake()->randomElement([
                    '适合后台演示的测试商品',
                    '多规格、多订单场景演示',
                    '用于商城功能联调',
                ]),
                'main_img' => $this->fakeImageUrl('goods-main-' . $index),
                'images' => [
                    $this->fakeImageUrl('goods-' . $index . '-1'),
                    $this->fakeImageUrl('goods-' . $index . '-2'),
                    $this->fakeImageUrl('goods-' . $index . '-3'),
                ],
                'content' => '<p>这是一条用于后台演示和联调的测试商品详情。</p>',
                'is_sale' => true,
            ]);

            $attrs = [
                '颜色' => ['曜石黑', '雪山白'],
                '版本' => ['标准版', 'Pro'],
            ];

            $specGroups = [];
            foreach ($attrs as $attrName => $values) {
                $goodsAttr = $goods->attr()->create([
                    'attr_name' => $attrName,
                    'sort' => count($specGroups),
                    'is_disabled' => false,
                ]);

                $specGroups[] = collect($values)->map(function (string $valueName, int $sort) use ($goodsAttr) {
                    return $goodsAttr->attrValue()->create([
                        'attr_value_name' => $valueName,
                        'sort' => $sort,
                        'is_disabled' => false,
                    ]);
                });
            }

            foreach ($specGroups[0] as $colorIndex => $colorValue) {
                foreach ($specGroups[1] as $versionIndex => $versionValue) {
                    $goods->sku()->create([
                        'spec' => $colorValue->id . '-' . $versionValue->id,
                        'price' => 9900 + ($index * 500) + ($colorIndex * 300) + ($versionIndex * 600),
                        'sku_img' => $this->fakeImageUrl('sku-' . $goods->id . '-' . $colorIndex . $versionIndex),
                        'stock' => random_int(20, 200),
                    ]);
                }
            }

            return $goods->load('sku', 'attr.attrValue');
        });
    }

    private function seedCartData(Collection $customers, Collection $goodsCollection): void
    {
        foreach ($customers->take(12) as $customer) {
            foreach ($goodsCollection->random(2) as $goods) {
                $sku = $goods->sku->random();

                MallCart::query()->create([
                    'customer_id' => $customer->id,
                    'goods_id' => $goods->id,
                    'goods_sku_id' => $sku->id,
                    'goods_number' => random_int(1, 3),
                ]);
            }
        }
    }

    private function seedOrders(Collection $customers, Collection $goodsCollection): void
    {
        $statuses = [
            MallOrderOrderStatusEnum::Order,
            MallOrderOrderStatusEnum::Pay,
            MallOrderOrderStatusEnum::Delivery,
            MallOrderOrderStatusEnum::Refund,
            MallOrderOrderStatusEnum::Finish,
            MallOrderOrderStatusEnum::Close,
            MallOrderOrderStatusEnum::Cancel,
        ];

        foreach (range(1, 24) as $index) {
            /** @var Customer $customer */
            $customer = $customers->random();
            $address = $customer->getRelation('seed_addresses')->random();
            $selectedGoods = $goodsCollection->random(2);
            $status = $statuses[array_rand($statuses)];
            $payment = in_array($status, [MallOrderOrderStatusEnum::Order, MallOrderOrderStatusEnum::Cancel], true)
                ? null
                : fake()->randomElement([MallOrderPaymentEnum::Balance, MallOrderPaymentEnum::Wechat]);

            $details = [];
            $detailTotal = 0;

            foreach ($selectedGoods as $goods) {
                $sku = $goods->sku->random();
                $number = random_int(1, 2);
                $linePrice = $sku->price * $number;
                $detailTotal += $linePrice;

                $details[] = [
                    'goods_id' => $goods->id,
                    'goods_sku_id' => $sku->id,
                    'goods_name' => $goods->goods_name,
                    'goods_spec' => $sku->spec,
                    'goods_image' => $sku->sku_img ?: $goods->main_img,
                    'goods_price' => $sku->price,
                    'goods_number' => $number,
                ];
            }

            $postage = random_int(0, 1200);
            $discount = random_int(0, 1500);
            $orderMoney = $detailTotal + $postage;
            $factMoney = max(1, $orderMoney - $discount);
            $createdAt = now()->subDays(random_int(1, 90))->subHours(random_int(0, 23));

            /** @var MallOrder $order */
            $order = MallOrder::query()->create([
                'customer_id' => $customer->id,
                'order_no' => MakeOrderNoEnum::MallOrder->new() . str_pad((string) $index, 2, '0', STR_PAD_LEFT),
                'order_status' => $status,
                'order_money' => $orderMoney,
                'order_fact_money' => $factMoney,
                'order_source' => fake()->randomElement([
                    MallOrderOrderSourceEnum::Order,
                    MallOrderOrderSourceEnum::ShoppingCart,
                ]),
                'payment' => $payment,
                'name' => $address->name,
                'phone' => $address->phone,
                'province' => $address->province,
                'city' => $address->city,
                'district' => $address->district,
                'address' => $address->address,
                'last_pay_time' => $status === MallOrderOrderStatusEnum::Order ? $createdAt->copy()->addDay() : null,
                'pay_time' => $payment ? $createdAt->copy()->addMinutes(random_int(10, 180)) : null,
                'delivery_time' => in_array($status, [MallOrderOrderStatusEnum::Delivery, MallOrderOrderStatusEnum::Refund, MallOrderOrderStatusEnum::Finish], true)
                    ? $createdAt->copy()->addDays(1)
                    : null,
                'finish_time' => $status === MallOrderOrderStatusEnum::Finish ? $createdAt->copy()->addDays(5) : null,
                'cancel_time' => $status === MallOrderOrderStatusEnum::Cancel ? $createdAt->copy()->addHours(3) : null,
                'turnoff_time' => $status === MallOrderOrderStatusEnum::Close ? $createdAt->copy()->addHours(12) : null,
                'logistics_name' => in_array($status, [MallOrderOrderStatusEnum::Delivery, MallOrderOrderStatusEnum::Refund, MallOrderOrderStatusEnum::Finish], true)
                    ? MallExpress::query()->inRandomOrder()->value('express_name')
                    : null,
                'logistics_no' => in_array($status, [MallOrderOrderStatusEnum::Delivery, MallOrderOrderStatusEnum::Refund, MallOrderOrderStatusEnum::Finish], true)
                    ? fake()->numerify('SF################')
                    : null,
                'buyer_remark' => fake()->optional()->sentence(),
                'seller_message' => fake()->optional()->sentence(),
                'prepay_id' => $payment === MallOrderPaymentEnum::Wechat ? 'wx_' . fake()->bothify('##########') : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $createdDetails = $order->detail()->createMany($details);

            if ($postage > 0) {
                $order->adjust()->create([
                    'order_detail_id' => $createdDetails->first()->id,
                    'adjust_type' => MallOrderAdjustAdjustTypeEnum::Postage->value,
                    'adjust_price' => $postage,
                ]);
            }

            if ($status === MallOrderOrderStatusEnum::Refund) {
                $this->seedRefund($order, $createdDetails);
            }

            $customer->balanceRecords()->create([
                'change_explain' => '商城订单消费',
                'scene_type' => CustomerBalanceSceneTypeEnum::MallOrder->value,
                'balance' => $factMoney,
                'relation_id' => $order->id,
                'remark' => $order->order_no,
                'created_at' => $createdAt,
            ]);
        }
    }

    private function seedRefund(MallOrder $order, Collection $details): void
    {
        /** @var MallOrderDetail $detail */
        $detail = $details->random();
        $refundType = fake()->randomElement([
            MallOrderRefundRefundTypeEnum::Only,
            MallOrderRefundRefundTypeEnum::Return,
        ]);
        $refundStatus = $refundType === MallOrderRefundRefundTypeEnum::Only
            ? fake()->randomElement([
                MallOrderRefundRefundStatusEnum::Applied,
                MallOrderRefundRefundStatusEnum::Approved,
                MallOrderRefundRefundStatusEnum::Confirmed,
                MallOrderRefundRefundStatusEnum::Successful,
                MallOrderRefundRefundStatusEnum::Failed,
                MallOrderRefundRefundStatusEnum::Closed,
            ])
            : fake()->randomElement([
                MallOrderRefundRefundStatusEnum::Applied,
                MallOrderRefundRefundStatusEnum::Approved,
                MallOrderRefundRefundStatusEnum::BuyerReturned,
                MallOrderRefundRefundStatusEnum::ReturnReceived,
                MallOrderRefundRefundStatusEnum::Confirmed,
                MallOrderRefundRefundStatusEnum::Successful,
            ]);

        $refund = MallOrderRefund::query()->create([
            'order_id' => $order->id,
            'refund_order_no' => MakeOrderNoEnum::MallRefundOrder->new(),
            'refund_type' => $refundType,
            'refund_status' => $refundStatus,
            'refund_money' => $detail->goods_price * $detail->goods_number,
            'phone' => $order->phone,
            'refund_reason' => fake()->randomElement(['商品不想要了', '规格拍错了', '发货后申请退款']),
            'buyer_message' => fake()->optional()->sentence(),
            'buyer_images' => [$this->fakeImageUrl('refund-' . $order->id)],
            'seller_message' => fake()->optional()->sentence(),
        ]);

        $refund->detail()->create([
            'order_detail_id' => $detail->id,
            'goods_id' => $detail->goods_id,
            'goods_sku_id' => $detail->goods_sku_id,
            'refund_number' => $detail->goods_number,
        ]);

        if ($refundType === MallOrderRefundRefundTypeEnum::Return) {
            $refund->logistics()->create([
                'logistics_company_name' => MallExpress::query()->inRandomOrder()->value('express_name'),
                'logistics_no' => fake()->numerify('TH################'),
                'name' => $order->name,
                'phone' => $order->phone,
                'province' => $order->province,
                'city' => $order->city,
                'district' => $order->district,
                'address' => $order->address,
            ]);
        }
    }

    private function fakeAddressData(?string $name = null): array
    {
        return [
            'name' => $name ?: fake()->name(),
            'phone' => fake_phone(),
            'province' => fake()->randomElement(['广东省', '浙江省', '江苏省', '上海市']),
            'city' => fake()->randomElement(['广州市', '深圳市', '杭州市', '苏州市', '上海市']),
            'district' => fake()->randomElement(['天河区', '南山区', '西湖区', '工业园区', '浦东新区']),
            'address' => fake()->streetAddress(),
        ];
    }

    private function fakeImageUrl(string $seed): string
    {
        return 'https://picsum.photos/seed/' . $seed . '/800/800';
    }
}
