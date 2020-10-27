<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UnitSeeder::class,
            CategorySeeder::class,
            ProductStyleSeeder::class,
            DepartmentSeeder::class,
            PositionSeeder::class,
            DrivingLicenseTypeSeeder::class,
            CartypeSeeder::class,
            CarstyleSeeder::class,
            BusinesstypeSeeder::class,
            TiretypeSeeder::class,
            ThaiAddressSeeder::class,

        ]);
    }
}
