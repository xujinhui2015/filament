<?php

return [
    # 自定义扩展
    'custom' => [
        // 总后台模块
        'admin' => [
            'enabled' => true, // 启用或者禁用模块
            'panel' => [ // 多面板定义
                'label' => '大后台',
                'icon' => 'heroicon-o-square-2-stack',
            ],
        ],
        // 商城模块
        'mall' => [
            'enabled' => true,
            'panel' => [
                'label' => '商城',
                'icon' => 'heroicon-c-wallet',
            ],
        ],
    ]
];
