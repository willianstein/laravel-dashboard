<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partners>
 */
class PartnersFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        return [
            'name'          => 'Clique-ti Soluções em Tecnologia Ltda',
            'trade_name'    => 'Clique-ti',
            'person'        => 'Juridica',
            'document01'    => '30054705000152',
            'document02'    => '123456789',
            'phone'         => '5511972572836',
            'email'         => 'contato@cliqueti.com.br',
            'type'          => 'Cliente',
            'segment'       => 1,
            'obs'           => 'Galera da T.I'
        ];
    }
}
