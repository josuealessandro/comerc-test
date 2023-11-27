<?php
namespace Tests\Unit\Services;

use App\Models\Product;
use App\Models\ProductPhoto;
use App\Services\ProductPhotoService;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductPhotoServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected $productPhotoService;

    public function setUp(): void
    {
        parent::setUp();
        $this->productPhotoService = new ProductPhotoService();
    }

    public function testCreateProductPhotoWithValidProduct()
    {
        // Crie um produto para associar a foto
        $product = Product::factory()->create();

        // Dados da foto
        $photoData = [
            'product_id' => $product->id,
            'url' => 'https://example.com/photo.jpg',
        ];

        $photo = $this->productPhotoService->createProductPhoto($photoData);

        $this->assertInstanceOf(ProductPhoto::class, $photo);
        $this->assertEquals($photoData['url'], $photo->url);
    }

    public function testCreateProductPhotoWithInvalidProduct()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Produto não encontrado.');

        // Dados da foto com um product_id inválido
        $photoData = [
            'product_id' => 'invalid_product_id',
            'url' => 'https://example.com/photo.jpg',
        ];

        $this->productPhotoService->createProductPhoto($photoData);
    }

    public function testUpdateProductPhoto()
    {
        // Crie uma foto de produto para atualizar
        $photo = ProductPhoto::factory()->create();

        // Novos dados para atualização
        $newData = [
            'url' => 'https://example.com/new_photo.jpg',
        ];

        $updatedPhoto = $this->productPhotoService->updateProductPhoto($photo->id, $newData);

        $this->assertInstanceOf(ProductPhoto::class, $updatedPhoto);
        $this->assertEquals($newData['url'], $updatedPhoto->url);
    }

    public function testDeleteProductPhoto()
    {
        // Crie uma foto de produto para excluir
        $photo = ProductPhoto::factory()->create();

        $result = $this->productPhotoService->deleteProductPhoto($photo->id);

        $this->assertTrue($result);
    }
}
