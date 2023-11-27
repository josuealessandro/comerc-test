<?php

namespace Tests\Unit\Models;

use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductPhoto;

class ProductPhotoTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testCreateProductPhoto()
    {
        $product = Product::create([
            'name' => 'Produto de Teste',
            'price_cents' => 1000,
        ]);

        $photo = ProductPhoto::create([
            'product_id' => $product->id,
            'url' => 'https://example.com/photo.jpg',
        ]);

        $this->assertInstanceOf(ProductPhoto::class, $photo);
        $this->assertEquals('https://example.com/photo.jpg', $photo->url);
    }

    public function testProductRelationship()
    {
        $product = Product::create([
            'name' => 'Produto de Teste',
            'price_cents' => 1000,
        ]);

        $photo = ProductPhoto::create([
            'product_id' => $product->id,
            'url' => 'https://example.com/photo.jpg',
        ]);

        $relatedProduct = $photo->product;

        $this->assertInstanceOf(Product::class, $relatedProduct);
        $this->assertEquals('Produto de Teste', $relatedProduct->name);
        $this->assertEquals(1000, $relatedProduct->price_cents);
    }

    public function testDeleteProductPhoto()
    {
        $product = Product::create([
            'name' => 'Produto de Teste',
            'price_cents' => 1000,
        ]);

        $photo = ProductPhoto::create([
            'product_id' => $product->id,
            'url' => 'https://example.com/photo.jpg',
        ]);

        $deleted = $photo->delete();

        $this->assertTrue($deleted);
        $this->assertNull(ProductPhoto::find($photo->id));
        $this->assertTrue(ProductPhoto::withTrashed()->where('id', $photo->id)->exists());
    }
}
