<?php

return [
    # 自定义扩展
    'custom' => [
        // 商城模块
        'mall' => [
            'enabled' => true,
            'resources' => [
                'MallAttrResource',
                'MallExpressResource',
                'MallGoodsCategoryResource',
                'MallGoodsResource',
                'MallOrderResource',
                'MallResource',
            ],
        ],
    ]
];