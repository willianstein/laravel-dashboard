<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Addresses;
use App\Models\Addressings;
use App\Models\Contacts;
use App\Models\Offices;
use App\Models\Partners;
use App\Models\Products;
use App\Models\Stocks;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'type' => 'user_adm',
        //     'name' => 'Paulo Brandeburski',
        //     'email' => 'paulo@cliqueti.com.br',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        // ]);

        // Offices::factory()->create();
        // Partners::factory()->create();
        // Addresses::factory()->create();
        // Contacts::factory()->create();
        // Products::factory()->create();
        // Addressings::factory()->create();
        // Stocks::factory()->create();
    }
}
