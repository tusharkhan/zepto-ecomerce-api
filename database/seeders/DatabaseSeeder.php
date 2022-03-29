<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Product;
use App\Models\User;
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
         User::factory(3)->create();
         Admin::factory(2)->create();
         Product::factory(10)->create();
    }
}
