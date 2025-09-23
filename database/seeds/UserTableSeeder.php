<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Barcode;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'surname'    => 1,
            'first_name'    => "مهيب الجنيد",
            'last_name'  => "776165784",
            'username'    => "المهيب",
            'user_type'=>'it_guy',
            'email'     => 'muhib@gmail.com',
            'password'  => bcrypt('password'),
            'remember_token' => '13131313',

        ]);
            


    }
}
