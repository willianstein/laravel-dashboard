<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AddressesFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        return [
            'partner_id' =>     1,
            'type' =>           'Comercial',
            'address' =>        'Av Henrique Eroles',
            'number' =>         '400',
            'complement' =>     'SL 32',
            'neighborhood' =>   'Alto Ipiranga',
            'city' =>           'Mogi das Cruzes',
            'state' =>          'SP',
            'country' =>        'Brasil',
            'postal_code' =>    '08737165'
        ];
    }
}
