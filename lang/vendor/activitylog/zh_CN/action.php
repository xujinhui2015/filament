<?php

return [
    'modal' => [
        'heading'     => '用户操作日志',
        'description' => '追踪所有用户操作记录',
        'tooltip'     => '用户操作',
    ],
    'event' => [
        'created'  => '创建',
        'deleted'  => '删除',
        'updated'  => '更新',
        'restored' => '恢复',
    ],
    'view'                => '查看',
    'edit'                => '编辑',
    'restore'             => '恢复',
    'restore_soft_delete' => [
        'label'             => '恢复模型',
        'modal_heading'     => '恢复已删除的模型',
        'modal_description' => '这将恢复一个被删除（软删除）的模型。',
    ],
];
