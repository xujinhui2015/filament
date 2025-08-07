<?php

return [
    'columns' => [
        'log_name' => [
            'label' => '类型',
        ],
        'event' => [
            'label' => '事件',
        ],
        'subject_type' => [
            'label'        => '对象',
            'soft_deleted' => '（软删除）',
            'deleted'      => '（已删除）',
        ],
        'causer' => [
            'label' => '用户',
        ],
        'properties' => [
            'label' => '属性',
        ],
        'created_at' => [
            'label' => '记录时间',
        ],
    ],
    'filters' => [
        'created_at' => [
            'label'                   => '记录时间',
            'created_from'            => '起始时间',
            'created_from_indicator'  => '从 :created_from 开始',
            'created_until'           => '结束时间',
            'created_until_indicator' => '截至 :created_until',
        ],
        'event' => [
            'label' => '事件',
        ],
    ],
];
