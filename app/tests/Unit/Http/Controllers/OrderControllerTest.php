<?php

namespace Tests\Unit\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Client;
use App\Models\Product;
use App\Models\Order;

class OrderControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateOrder()
    {
        $client = Client::factory()->create();
        $products = Product::factory(3)->create();
        $productIds = $products->pluck('id')->toArray();

        $data = [
            'client_id' => $client->id,
            'product_ids' => $productIds,
        ];

        $response = $this->post('/api/orders', $data);

        $response->seeStatusCode(201);
    }

    public function testCreateOrderWithInvalidClient()
    {
        $invalidClientId = 'non_existent_client_id';

        $data = [
            'client_id' => $invalidClientId,
            'product_ids' => ['valid_product_id'],
        ];

        $response = $this->post( '/api/orders', $data);

        $response->seeStatusCode(422);
    }

    public function testCreateOrderWithInvalidProducts()
    {
        $client = Client::factory()->create();

        $data = [
            'client_id' => $client->id,
            'product_ids' => ['invalid_product_id'],
        ];

        $response = $this->post('/api/orders', $data);

        $response->seeStatusCode(404);
    }

    public function testDeleteOrder()
    {
        $order = Order::factory()->create();

        $response = $this->delete("/api/orders/{$order->id}");

        $response->seeStatusCode(200);

        $this->assertCount(0,
            DB::table('orders')
                ->where('id', $order->id)
                ->whereNull('deleted_at')
                ->get()
        );
    }

    public function testDeleteOrderWithInvalidId()
    {
        $invalidOrderId = 'non_existent_order_id';

        $response = $this->delete("/api/orders/{$invalidOrderId}");

        $response->seeStatusCode(404);
    }

    public function testGetOrderByProduct()
    {
        $product = Product::factory()->create();
        $order = Order::factory()->create();
        $order->products()->attach($product->id, ['price_cents' => $product->price_cents]);

        $response = $this->get("/api/orders/by-product/{$product->id}");

        $response->seeStatusCode(200);
    }

    public function testGetOrderByProductWithInvalidId()
    {
        $invalidProductId = 'non_existent_product_id';

        $response = $this->get("/api/orders/by-product/{$invalidProductId}");

        $response->seeStatusCode(404);
    }

    public function testGetOrderByClient()
    {
        $client = Client::factory()->create();
        $order = Order::factory()->create(['client_id' => $client->id]);

        $response = $this->get("/api/orders/by-client/{$client->id}");

        $response->seeStatusCode(200);
    }

    public function testGetOrderByClientWithInvalidId()
    {
        $invalidClientId = 'non_existent_client_id';

        $response = $this->get("/api/orders/by-client/{$invalidClientId}");

        $response->seeStatusCode(404);
    }

    public function testGetOrderById()
    {
        $order = Order::factory()->create();

        $response = $this->get("/api/orders/{$order->id}");

        $response->seeStatusCode(200);
    }

    public function testGetOrderByIdWithInvalidId()
    {
        $invalidOrderId = 'non_existent_order_id';

        $response = $this->get("/api/orders/{$invalidOrderId}");

        $response->seeStatusCode(404);
    }
}
