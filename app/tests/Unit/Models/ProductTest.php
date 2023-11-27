<?php

namespace Tests\Unit\Models;

use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductPhoto;

class ProductTest extends TestCase
{
    use DatabaseTransactions;
    public function testCreateProduct()
    {
        $product = Product::create([
            'name' => 'Produto de Teste',
            'price_cents' => 1000, // Preço em centavos
        ]);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Produto de Teste', $product->name);
        $this->assertEquals(1000, $product->price_cents);
    }

    public function testProductPhotosRelationship()
    {
        $product = Product::create([
            'name' => 'Produto de Teste',
            'price_cents' => 1000,
        ]);

        $photo = $product->photos()->create([
            'url' => 'https://example.com/photo.jpg',
        ]);

        $this->assertInstanceOf(ProductPhoto::class, $photo);
        $this->assertEquals('https://example.com/photo.jpg', $photo->url);
    }

    public function testDeleteProduct()
    {
        $product = Product::create([
            'name' => 'Produto a ser excluído',
            'price_cents' => 1500,
        ]);

        $deleted = $product->delete();

        $this->assertTrue($deleted);
        $this->assertNull(Product::find($product->id));
        $this->assertTrue(Product::withTrashed()->where('id', $product->id)->exists());
    }
}
