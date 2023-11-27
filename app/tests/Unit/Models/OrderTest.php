<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateOrder()
    {
        $client = Client::factory()->create();
        $orderData = [
            'client_id' => $client->id,
            'total_price_cents' => 1000, // Total em centavos
        ];

        $order = Order::create($orderData);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($client->id, $order->client_id);
        $this->assertEquals(1000, $order->total_price_cents);
    }

    public function testOrderRelations()
    {
        $client = Client::factory()->create();
        $order = Order::create([
            'client_id' => $client->id,
            'total_price_cents' => 1000,
        ]);

        $product = Product::factory()->create();
        $order->products()->attach($product->id, ['price_cents' => 500]);

        $this->assertInstanceOf(Client::class, $order->client);
        $this->assertInstanceOf(Product::class, $order->products->first());
        $this->assertEquals(500, $order->products->first()->pivot->price_cents);
    }
}
