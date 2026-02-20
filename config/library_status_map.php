<?php

/**
 * @file
 * Config for library status mappings.
 */

/**
 * Maps incoming library status labels to our internal status names.
 *
 * 'delete_flag' => Flag the device for manual review (lost and paid).
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
    'label'  => 'Item in place',
    'status' => 'Library',
  ],

  /*
  |--------------------------------------------------------------------------
  | "Item not in place" reasons (from their reason column)
  |--------------------------------------------------------------------------
   */
  'reasons' => [
    'With Doyle ITC' => [
      'status' => 'Imaging - Doyle',
    ],
    'With Mahoney ITC' => [
      'status' => 'Imaging - Mahoney',
    ],
    'Loan' => [
      'status' => 'Library',
    ],
    'Transit' => [
      'status' => 'Library',
    ],
    'Lost' => [
      'status' => 'Library - Lost',
    ],
    'Lost and paid' => [
      'status'      => NULL,
      'delete_flag' => TRUE,
    ],
    'Missing' => [
      'status'      => NULL,
      'delete_flag' => TRUE,
    ],
    'Claimed Returned' => [
      'status'      => NULL,
      'delete_flag' => TRUE,
    ],
  ],

  // Excluded from 'search missing' - known to be in other locations.
  'excluded_pools' => [
    'Adult Ed',
  ],
];
