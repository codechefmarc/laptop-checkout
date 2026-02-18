<?php

/**
 * Maps incoming library status labels to our internal status names.
 *
 * 'delete_flag' => true means we flag the device for manual review (lost and paid).
 * 'status' should match the `name` column in your statuses table exactly.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | "Item in Place" column value
    |--------------------------------------------------------------------------
    | When their primary status column says this, the item is with the library.
    */
    'item_in_place' => [
        'label'  => 'item in place',
        'status' => 'Library',
    ],

    /*
    |--------------------------------------------------------------------------
    | "Item not in place" reasons (from their reason column)
    |--------------------------------------------------------------------------
    */
    'reasons' => [
        'claimed returned' => [
            'status' => 'Library - Lost',
        ],
        'laptop damaged/repair instructional computing (sr)' => [
            'status' => 'Imaging',
        ],
        'loan' => [
            'status' => 'Library',
        ],
        'lost' => [
            'status' => 'Library - Lost',
        ],
        'lost and paid' => [
            'status'      => null,
            'delete_flag' => true,
        ],
        // Add additional reason mappings here as you discover them.
        // e.g. 'other reason label' => ['status' => 'Repair'],
    ],

];
