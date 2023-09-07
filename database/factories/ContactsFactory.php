<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContactsFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        return [
            'partner_id'    => 1,
            'name'          => 'Paulo Brandeburski',
            'cellphone'     => '11972572836',
            'email'         => 'paulo@cliqueti.com.br',
            'position'      => 'Developer'
        ];
    }
}
