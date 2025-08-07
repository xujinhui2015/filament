<?php

return [
    'components' => [
        'created_by_at'             => '<strong>:subject</strong> 被 <strong>:causer</strong> <strong>:event</strong>。<br><small>更新时间：<strong>:update_at</strong></small>',
        'updater_updated'           => ':causer <strong>:event</strong> 了以下内容：<br>:changes',
        'from_oldvalue_to_newvalue' => '- :key 从 <strong>:old_value</strong> 变更为 <strong>:new_value</strong>',
        'to_newvalue'               => '- :key 设置为 <strong>:new_value</strong>',
        'unknown'                   => '未知',
    ],
];
