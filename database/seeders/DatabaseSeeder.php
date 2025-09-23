<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // public function run()
    // {
    //     // DB::table('users')->insert([
    //     //     'surname'    => 1,
    //     //     'first_name'    => "مهيب الجنيد",
    //     //     'last_name'  => "776165784",
    //     //     'username'    => "taiz",
    //     //     'email'     => 'muhib@gmail.com',
    //     //     // 'email_verified_at' => now(),
    //     //     'password'  => bcrypt('password'),
    //     //     'remember_token' => '13131313',

    //     // ]);


    //     // --------------
    //     DB::table('users')->insert([
    //         // 'id'        => 1,
    //         'surname'    => 1,
    //         'first_name'    => "مهيب الجنيد",
    //         'last_name'  => "776165784",
    //         'username'    => "المهيب",
    //         'user_type'=>'it_guy',
    //         'email'     => 'muhib@gmail.com',
    //         // 'email_verified_at' => now(),
    //         'password'  => bcrypt('password'),
    //         'remember_token' => '13131313',

    //     ]);
    //     // ------------
    // }


    public function run()
    {
        $this->call([
            
            BarcodesTableSeeder::class,
            PermissionsTableSeeder::class,
            CurrenciesTableSeeder::class,
            UserTableSeeder::class,
            PackageTableSeeder::class,




        ]);
    }
}
