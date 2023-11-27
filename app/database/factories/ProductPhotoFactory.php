<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductPhotoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductPhoto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => function () {
                return Product::factory()->create()->id;
            },
            'url' => $this->faker->imageUrl,
        ];
    }
}
