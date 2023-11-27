<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API para gerenciar produtos"
 * )
 * @OA\Schema(
 *     schema="Product",
 *     required={"name", "price_cents"},
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="price_cents", type="integer"),
 * )
 */
class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Lista todos os produtos",
     *     description="Obtém a lista de todos os produtos cadastrados",
     *     @OA\Response(response="200", description="Lista de produtos"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function index()
    {
        $products = $this->productService->getAllProducts();

        return response()->json($products);
    }

    /**
     * @OA\Post(
     *     path="/api/products/search",
     *     tags={"Products"},
     *     summary="Pesquisa produtos por nome",
     *     description="Pesquisa produtos com base no nome fornecido",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="name",
     *                 description="Nome do produto para pesquisa",
     *                 type="string"
     *             ),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Lista de produtos correspondentes à pesquisa"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $name = $request->input('name');
        $products = $this->productService->getProductByName($name);

        return response()->json($products);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Obtém os detalhes de um produto por ID",
     *     description="Obtém os detalhes de um produto com base no ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Detalhes do produto"),
     *     @OA\Response(response="404", description="Produto não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function show($id)
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['error' => 'Produto não encontrado.'], 404);
        }

        return response()->json($product);
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Cria um novo produto",
     *     description="Cria um novo produto com os dados fornecidos",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(response="201", description="Produto criado com sucesso"),
     *     @OA\Response(response="400", description="Erro na solicitação"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'price_cents' => 'required|integer',
        ]);

        $data = $request->all();
        $product = $this->productService->createProduct($data);

        return response()->json($product, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Atualiza um produto por ID",
     *     description="Atualiza os detalhes de um produto com base no ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(response="200", description="Produto atualizado com sucesso"),
     *     @OA\Response(response="400", description="Erro na solicitação"),
     *     @OA\Response(response="404", description="Produto não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string',
            'price_cents' => 'integer',
        ]);

        $data = $request->all();
        $product = $this->productService->updateProduct($id, $data);

        if (!$product) {
            return response()->json(['error' => 'Produto não encontrado.'], 404);
        }

        return response()->json($product);
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Exclui um produto por ID",
     *     description="Exclui um produto com base no ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Produto excluído com sucesso"),
     *     @OA\Response(response="404", description="Produto não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function delete($id)
    {
        $result = $this->productService->deleteProduct($id);

        if (!$result) {
            return response()->json(['error' => 'Produto não encontrado.'], 404);
        }

        return response()->json(['message' => 'Produto deletado.'], 200);
    }
}
