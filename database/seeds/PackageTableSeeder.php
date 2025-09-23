<?php

use Modules\Superadmin\Entities\Package;

use Illuminate\Database\Seeder;

class PackageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "id" => "1",
                "name" => "نسخة تجريبية",
                "description" => "حزمة تجربية لمدة 10 أيام",
                "location_count" => 0,
                "user_count" => 0,
                "product_count" => 0,
                'bookings'=>0,
                'kitchen'=>0,
                'order_screen'=>0,
                'tables' => 0,
                "invoice_count" => 0,
                "interval" => 'days',
                "interval_count" => 10,
                'trial_days' => 10,
                'price' => 0.0000,
                'custom_permissions' => '{"manufacturing_module":"1","repair_module":"1"}',
                'created_by' => 3,
                'sort_order' => 1,
                'is_active' => 1,
                'is_private' => 0,
                'is_one_time' => 1,
                'enable_custom_link' => 0,
                'custom_link' => null,
                'custom_link_text' => null,
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
                'enabled_modules'=>null
            ],
            [
                "id" => "2",
                "name" => "Gold",
                "description" => "للشركات و المطاعم و الكافيهات المتوسطه و مراكز الصيانه - اول شهر مجانا جربه الان",
                "location_count" => 3,
                "user_count" => 0,
                "product_count" => 0,
                'bookings'=>0,
                'kitchen'=>0,
                'order_screen'=>0,
                'tables' => 0,
                "invoice_count" => 0,
                "interval" => 'months',
                "interval_count" => 1,
                'trial_days' => 10,
                'price' => 70.0000,
                'custom_permissions' => '{"essentials_module":"1","productcatalogue_module":"1","woocommerce_module":"1"}',
                'created_by' => 1,
                'sort_order' => 1,
                'is_active' => 1,
                'is_private' => 0,
                'is_one_time' => 0,
                'enable_custom_link' => 0,
                'custom_link' => null,
                'custom_link_text' => null,
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
                'enabled_modules'=>null
            ],

            [
                "id" => "2",
                "name" => "Silver",
                "description" => "للشركات الصغرى و المطاعم و الكافيهات - اول شهر مجانا جربه الان",
                "location_count" => 2,
                "user_count" => 0,
                "product_count" => 0,
                'bookings'=>0,
                'kitchen'=>0,
                'order_screen'=>0,
                'tables' => 0,
                "invoice_count" => 0,
                "interval" => 'months',
                "interval_count" => 1,
                'trial_days' => 10,
                'price' => 50.0000,
                'custom_permissions' => '{"essentials_module":"1","woocommerce_module":"1"}',
                'created_by' => 1,
                'sort_order' => 1,
                'is_active' => 1,
                'is_private' => 0,
                'is_one_time' => 0,
                'enable_custom_link' => 0,
                'custom_link' => null,
                'custom_link_text' => null,
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
                'enabled_modules'=>null
            ],
           


        ];

        Package::insert($data);
    }
}
