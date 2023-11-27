<?php

namespace Tests\Unit\Services;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $orderService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderService();
    }

    public function testCreateOrder()
    {
        $client = Client::factory()->create();
        $products = Product::factory(2)->create();

        $requestData = [
            'client_id' => $client->id,
            'product_ids' => $products->pluck('id')->toArray(),
        ];

        $order = $this->orderService->createOrder($requestData);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($client->id, $order->client_id);
        $this->assertGreaterThan(0, $order->total_price_cents);
        $this->assertCount(2, $order->products);
    }

    public function testCreateOrderWithInvalidClient()
    {
        $invalidClientId = 'non_existent_id';
        $products = Product::factory(2)->create();

        $requestData = [
            'client_id' => $invalidClientId,
            'product_ids' => $products->pluck('id')->toArray(),
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('O cliente especificado não existe.');

        $this->orderService->createOrder($requestData);
    }

    public function testCreateOrderWithInvalidProducts()
    {
        $client = Client::factory()->create();
        $invalidProductIds = ['non_existent_id'];

        $requestData = [
            'client_id' => $client->id,
            'product_ids' => $invalidProductIds,
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Um ou mais produtos não encontrados.');

        $this->orderService->createOrder($requestData);
    }

    public function testDeleteOrder()
    {
        $order = Order::factory()->create();

        $result = $this->orderService->deleteOrder($order->id);

        $this->assertTrue($result);

        $this->assertNull(Order::find($order->id));
    }

    public function testDeleteOrderWithInvalidId()
    {
        $invalidOrderId = 'non_existent_id';

        $result = $this->orderService->deleteOrder($invalidOrderId);

        $this->assertFalse($result);
    }

    public function testGetOrdersByProduct()
    {
        // Crie um produto e um pedido com esse produto associado
        $product = Product::factory()->create();
        $order = Order::factory()->create();
        $order->products()->attach($product->id, ['price_cents' => $product->price_cents]);

        // Chame o método para buscar pedidos com base no produto
        $result = $this->orderService->getOrdersByProduct($product->id);

        // Verifique se o pedido é retornado corretamente
        $this->assertCount(1, $result);
        $this->assertEquals($order->id, $result[0]->id);
    }

    public function testGetOrdersByClient()
    {
        // Crie um cliente e um pedido associado a esse cliente
        $client = Client::factory()->create();
        $order = Order::factory()->create(['client_id' => $client->id]);

        // Chame o método para buscar pedidos com base no cliente
        $result = $this->orderService->getOrdersByClient($client->id);

        // Verifique se o pedido é retornado corretamente
        $this->assertCount(1, $result);
        $this->assertEquals($order->id, $result[0]->id);
    }

    public function testGetOrderById()
    {
        // Crie um pedido
        $order = Order::factory()->create();

        // Chame o método para buscar o pedido pelo ID
        $result = $this->orderService->getOrderById($order->id);

        // Verifique se o pedido é retornado corretamente
        $this->assertEquals($order->id, $result->id);
    }}
