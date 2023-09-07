<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Stocks>
 */
class StocksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type'          => 'normal',
            'office_id'     => '1',
            'partner_id'    => '1',
            'product_id'    => '1',
            'addressing_id' => '1',
            'quantity_min'  => '50',
            'quantity_max'  => '1000',
        ];
    }
}
