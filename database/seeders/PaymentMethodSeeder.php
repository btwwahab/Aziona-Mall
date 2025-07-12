<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::insert([
            ['name' => 'Cash on Delivery', 'status' => 1],
            ['name' => 'Bank Transfer', 'status' => 1],
            ['name' => 'Stripe', 'status' => 1]
        ]);

    }
}
