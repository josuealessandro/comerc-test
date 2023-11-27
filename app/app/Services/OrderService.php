<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Client;
use App\Models\Product;

class OrderService
{
    public function createOrder(array $requestData)
    {

        $this->validateRequestData($requestData);

        $client = Client::find($requestData['client_id']);

        $productIds = $requestData['product_ids'];
        $products = Product::whereIn('id', $productIds)->get();

        if (count($products) !== count($productIds)) {
            throw new \InvalidArgumentException('Um ou mais produtos não encontrados.');
        }

        $totalPriceCents = 0;

        foreach ($products as $product) {
            $totalPriceCents += $product->price_cents;
        }

        $order = Order::create([
            'client_id' => $client->id,
            'total_price_cents' => $totalPriceCents,
        ]);

        foreach ($products as $product) {
            $order->products()->attach([$product->id => ['price_cents' => $product->price_cents]]);
        }

        return $order;
    }

    public function deleteOrder($id)
    {

        $order = Order::find($id);

        if (!$order) {
            return false;
        }

        $order->delete();

        return true;
    }

    public function getOrdersByProduct($productId)
    {
        return Order::with('client', 'products')->whereHas('products', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->get();
    }

    public function getOrdersByClient($clientId)
    {
        return Order::with('client', 'products')->where('client_id', $clientId)->get();
    }

    public function getOrderById($id)
    {
        return Order::with('client', 'products')->find($id);
    }

    public function getAllOrders()
    {
        return Order::all();
    }

    private function validateRequestData(array $data)
    {
        $rules = [
            'client_id' => 'required|exists:clients,id',
            'product_ids' => 'required|array',
        ];

        $messages = [
            'client_id.required' => 'O campo "client_id" é obrigatório.',
            'client_id.exists' => 'O cliente especificado não existe.',
            'product_ids.required' => 'O campo "product_ids" é obrigatório.',
            'product_ids.array' => 'O campo "product_ids" deve ser um array.',
        ];

        $validator = \Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }
    }
}
