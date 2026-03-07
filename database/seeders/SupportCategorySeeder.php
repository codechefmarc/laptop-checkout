<?php

namespace Database\Seeders;

use App\Models\SupportCategory;
use Illuminate\Database\Seeder;

/**
 * Creates real support categories in the database.
 */
class SupportCategorySeeder extends Seeder {

  /**
   * Run the database seeds.
   */
  public function run(): void {
    $categories = [
      'Bear Cubs email' => ['description' => 'Issues related to creating, logging into Bear Cubs Gmail accounts or help with logging in alongside personal Gmail accounts.', 'weight' => 10],
      'Bear Cub Hub' => ['description' => 'Formally Student Portal - issues related to logging into the Bear Cub Hub, password resets, or student ID number lookups.', 'weight' => 20],
      'Microsoft Office' => ['description' => 'Issues related to Microsoft Office, web or desktop, logging in, creating Microsoft accounts, sharing documents, or accessing One Drive.', 'weight' => 30],
      'Adobe Suite' => ['description' => 'Any issues related to Adobe products such as Photoshop, Indesign, etc.', 'weight' => 300],
      'Canvas' => ['description' => 'Issues related to Canvas learning managment platform - logging in, navigation, contacting instructors, etc.', 'weight' => 600],
      'Other software' => ['description' => 'Software that isn\'t Microsoft or Adobe related.', 'weight' => 400],
      'Operating system' => ['description' => 'Windows or MacOS issues - file structure, copying files, general OS help, etc.', 'weight' => 500],
      'Wireless' => ['description' => 'Issues related to wireless connectivity.', 'weight' => 100],
      'Checkout laptop' => ['description' => 'Issues related to checking out laptops.', 'weight' => 800],
      'Personal device' => ['description' => 'Issues related to personal devices - phones, tablets, etc.', 'weight' => 900],
      'Campus navigation' => ['description' => 'Issues related to navigating campus, finding buildings, etc.', 'weight' => 1100],
      'Library computers' => ['description' => 'Issues related to using library computers, printing, etc.', 'weight' => 1200],
      'Printing and copying' => ['description' => 'Issues related to printing and copying on campus.', 'weight' => 1300],
      'Bookings' => ['description' => 'Issues related to booking rooms, equipment, etc.', 'weight' => 1400],
    ];

    foreach ($categories as $name => $attributes) {
      SupportCategory::firstOrCreate(
        ['name' => $name],
        $attributes
      );
    }
  }

}
