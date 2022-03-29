<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $filePath = storage_path('app/public/products');
        return [
            'name' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'image' => $this->faker->image($filePath, 400, 300, 'products', false),
            'price' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
