<?php

return [
    /**
     * Tree model fields
     */
    'column_name' => [
        'order' => 'sort',
        'parent' => 'parent_id',
        'title' => 'title',
    ],
    /**
     * Tree model default parent key
     */
    'default_parent_id' => 0,
    /**
     * Tree model default children key name
     */
    'default_children_key_name' => 'children',
];
