<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'client_id' => function () {
                return Client::factory()->create()->id;
            },
            'total_price_cents' => $this->faker->numberBetween(1000, 100000), // Você pode ajustar os valores aqui
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Order $order) {
            $productCount = $this->faker->numberBetween(1, 5); // Número aleatório de produtos

            $products = Product::factory($productCount)->create();

            foreach ($products as $product) {
                $order->products()->attach([$product->id => ['price_cents' => $product->price_cents]]);
            }
        });
    }
}
