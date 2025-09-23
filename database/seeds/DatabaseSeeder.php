<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([BarcodesTableSeeder::class,
        PermissionsTableSeeder::class,
        CurrenciesTableSeeder::class,
        UserTableSeeder::class,
        PackageTableSeeder::class,

  

        
        ]);
    }


//     public function run()
// {
//     foreach (glob(database_path('seeders/*.php')) as $file) {
//         $class = pathinfo($file, PATHINFO_FILENAME);
//         $this->call("Database\\Seeders\\$class");
//     }
// }

}
