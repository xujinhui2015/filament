<?php

return [
    // 总后台模块, 此模块不允许禁用
    'admin' => [
        'panel' => [ // 多面板定义
            'label' => '大后台',
            'icon' => 'heroicon-o-square-2-stack',
        ],
    ],
    # 自定义扩展
    'custom' => [
        // 商城模块
        'mall' => [
            'enabled' => true, // 启用或者禁用模块
            'panel' => [ // 多面板定义
                'label' => '商城',
                'icon' => 'heroicon-c-wallet',
            ],
        ],
    ]
];
