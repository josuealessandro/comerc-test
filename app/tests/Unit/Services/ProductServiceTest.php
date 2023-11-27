<?php

namespace Tests\Unit\Services;

use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Services\ProductService;
use App\Models\Product;

class ProductServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function testCanCreateAProduct()
    {
        $productService = new ProductService();
        $productData = [
            'name' => 'Product 1',
            'price_cents' => 1000,
        ];

        $product = $productService->createProduct($productData);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($productData['name'], $product->name);
    }

    public function testCanUpdateAProduct()
    {
        $productService = new ProductService();
        $productData = [
            'name' => 'Product 1',
            'price_cents' => 1000,
        ];

        $product = $productService->createProduct($productData);

        $updatedData = [
            'name' => 'Updated Product',
            'price_cents' => 1500,
        ];

        $updatedProduct = $productService->updateProduct($product->id, $updatedData);

        $this->assertInstanceOf(Product::class, $updatedProduct);
        $this->assertEquals($updatedData['name'], $updatedProduct->name);
    }

    public function testCanDeleteAProduct()
    {
        $productService = new ProductService();
        $productData = [
            'name' => 'Product 1',
            'price_cents' => 1000,
        ];

        $product = $productService->createProduct($productData);
        $productService->deleteProduct($product->id);

        $this->assertNull(Product::find($product->id));
    }
}
