<?php

use Modules\Superadmin\Entities\Package;

use Illuminate\Database\Seeder;

class SystemTableSeeder extends Seeder
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

                "key" => "invoice_business_name",
                "value" => "mmmmm",

            ],
            [

                "key" => "app_currency_id",
                "value" => "101",

            ],
            [

                "key" => "email",
                "value" => "morsyzidan77@gmail.com",

            ],

            [

                "key" => "invoice_business_landmark",
                "value" => "Landmark",

            ],
            [

                "key" => "invoice_business_city",
                "value" => "City",

            ],
            [

                "key" => "invoice_business_state",
                "value" => "State",

            ],
            [

                "key" => "invoice_business_zip",
                "value" => "Zip",

            ],
            [

                "key" => "invoice_business_country",
                "value" => "Country",

            ],
            [

                "key" => "superadmin_version",
                "value" => '1.0',

            ],
            [

                "key" => "Partners_version",
                "value" => '1.0',

            ],
            [

                "key" => "connector_version",
                "value" => '0.9',

            ],
            [

                "key" => "installment_version",
                "value" => '2.9',

            ],
            [

                "key" => "manufacturing_version",
                "value" => '2.0',

            ],
            [

                "key" => "Assets_version",
                "value" => '1.8',

            ],
            [

                "key" => "superhero_version",
                "value" => '1.0',

            ],
            [

                "key" => "chartofaccounts_version",
                "value" => '1.0',

            ], //-------------------------------
            [

                "key" => "nventory_version",
                "value" => '1.0',

            ],
            [

                "key" => "generalaccount_version",
                "value" => '1.0',

            ],
            [

                "key" => "restaurant_version",
                "value" =>  '1.0',

            ],
            [

                "key" => "project_version",
                "value" =>  '1.6',

            ],





        ];

        Package::insert($data);
    }
}
