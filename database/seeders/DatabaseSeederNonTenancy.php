<?php

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeederNonTenancy extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {        
        
        // Universal seeders       
        // $this->call(NationalitiesTableSeeder::class);
        // $this->call(StatesTableSeeder::class);
        // $this->call(LgasTableSeeder::class);
        // $this->call(BloodGroupsTableSeeder::class);
        // $this->call(LanguagesTableSeeder::class);
        // $this->call(TimezonesTableSeeder::class);
        // Not tenancy specific seeders
        $this->call(NonTenancyUsersTableSeeder::class);
        $this->call(NonTenancyUserTypesTableSeeder::class);
        $this->call(NonTenancySettingsTableSeeder::class);
        // $this->call(NonTenancyStaffRecordsTableSeeder::class);
    }   
    
}
