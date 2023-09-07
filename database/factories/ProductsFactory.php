<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() {
        return [
            'isbn'      => '9788563993342',
            'title'     => 'Nos bastidores do Pink Floyd',
            'publisher' => 'Generale',
            'category'  => 'Biografia',
            'synopsis'  => 'A vida e a música Roger Waters, David Gilmour, Nick Mason, Richard Wright e Syd Barrett, assim como seus conflitos, neuroses, medos, paixões e vitórias, são esmiuçados, em um relato que impressiona pela riqueza de detalhes.',
            'height'    => '228',
            'width'     => '156',
            'length'    => '32',
            'weight'    => '150'
        ];
    }
}
