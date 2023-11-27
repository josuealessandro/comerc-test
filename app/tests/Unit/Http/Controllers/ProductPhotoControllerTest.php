<?php

namespace Tests\Unit\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductPhotoControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateProductPhoto()
    {
        // Crie um produto para vincular a foto
        $product = Product::factory()->create();

        $photoData = [
            'product_id' => $product->id,
            'url' => 'http://example.com/photo.jpg',
        ];

        $response = $this->post('/api/product-photos', $photoData);

        $response->seeStatusCode(201);
        $this->seeInDatabase('products_photos', $photoData);
    }

    public function testCreateProductPhotoWithInvalidProduct()
    {
        // Tente criar uma foto de produto com um ID de produto inválido
        $photoData = [
            'product_id' => 'invalid_product_id',
            'url' => 'http://example.com/photo.jpg',
        ];

        $response = $this->post('/api/product-photos', $photoData);

        $response->seeStatusCode(422);
    }

    public function testUpdateProductPhoto()
    {
        $product = Product::factory()->create();
        $photo = ProductPhoto::factory()->create(['product_id' => $product->id]);

        $updatedData = [
            'url' => 'http://example.com/updated-photo.jpg',
        ];

        $response = $this->put("/api/product-photos/{$photo->id}", $updatedData);

        $response->seeStatusCode(200);
        $this->seeInDatabase('products_photos', $updatedData);
    }

    public function testUpdateNonExistingProductPhoto()
    {
        // Tente atualizar uma foto de produto que não existe
        $updatedData = [
            'url' => 'http://example.com/updated-photo.jpg',
        ];

        $response = $this->put('/api/product-photos/invalid_photo_id', $updatedData);

        $response->seeStatusCode(404);
    }

    public function testDeleteProductPhoto()
    {
        $product = Product::factory()->create();
        $photo = ProductPhoto::factory()->create(['product_id' => $product->id]);

        $response = $this->delete("/api/product-photos/{$photo->id}");

        $response->seeStatusCode(200);

        $this->assertCount(0,
            DB::table('products_photos')
                ->where('id', $photo->id)
                ->whereNull('deleted_at')
                ->get()
        );
    }

    public function testDeleteNonExistingProductPhoto()
    {
        // Tente excluir uma foto de produto que não existe
        $response = $this->delete('/api/product-photos/invalid_photo_id');

        $response->seeStatusCode(404);
    }

    public function testGetPhotosByProductId()
    {
        $product = Product::factory()->create();
        ProductPhoto::factory(3)->create(['product_id' => $product->id]);

        $response = $this->get("/api/product-photos/{$product->id}");

        $response->seeStatusCode(200);
        $this->assertEquals(3, $this->response->decodeResponseJson()->count());
    }

    public function testGetPhotosByNonExistingProductId()
    {
        // Tente obter fotos de um produto que não existe
        $response = $this->get('/api/product-photos/invalid_product_id');

        $response->seeStatusCode(404);
    }
}
