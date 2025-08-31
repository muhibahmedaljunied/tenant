<?php



use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NonTenancyUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'It Guy',
                'blocked' => 0,
                'religion' => 'Islam',
                'email' => 'itguy@sms.com',
                'code' => 'ITGUY',
                'username' => 'itguy',
                'user_type' => 'it_guy',
                'password' => Hash::make('itguy'),
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            )
        ));
    }
}