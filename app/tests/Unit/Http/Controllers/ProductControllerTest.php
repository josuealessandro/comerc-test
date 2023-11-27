<?php

namespace Tests\Unit\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Product;
use App\Services\ProductService;
class ProductControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $productService;

    public function setUp(): void
    {
        parent::setUp();

        $this->productService = app(ProductService::class);
    }

    public function test_can_search_products_by_name()
    {
        $products = Product::factory(5)->create();

        // Use o nome do primeiro produto para pesquisa
        $nameToSearch = $products[0]->name;

        $response = $this->post('/api/products/search', ['name' => $nameToSearch]);

        $response->seeStatusCode(200);
        $this->assertEquals(1, $this->response->decodeResponseJson()->count());
    }

    public function test_can_create_product()
    {
        $productData = [
            'name' => 'Produto de Teste',
            'price_cents' => 1000,
        ];

        $response = $this->post('/api/products', $productData);

        $response->seeStatusCode(201)
            ->seeInDatabase('products',$productData);
    }

    public function test_can_update_product_by_id()
    {
        $product = Product::factory()->create();

        $updatedProductData = [
            'name' => 'Produto Atualizado',
            'price_cents' => 1500,
        ];

        $response = $this->put('/api/products/' . $product->id, $updatedProductData);

        $response->seeStatusCode(200)
            ->seeInDatabase('products',$updatedProductData);
    }

    public function test_can_delete_product_by_id()
    {
        $product = Product::factory()->create();

        $this->delete('/api/products/' . $product->id);

        $this->assertCount(0,
            DB::table('products')
                ->where('id', $product->id)
                ->whereNull('deleted_at')
                ->get()
        );
    }
}
