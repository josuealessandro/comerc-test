<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductPhotoService;

/**
 * @OA\Tag(
 *     name="Product Photos",
 *     description="API para gerenciar fotos de produtos"
 * )
 * @OA\Schema(
 *     schema="ProductPhoto",
 *     required={"product_id", "url"},
 *     @OA\Property(property="product_id", type="string"),
 *     @OA\Property(property="url", type="string"),
 * )
 */
class ProductPhotoController extends Controller
{
    protected $productPhotoService;

    public function __construct(ProductPhotoService $productPhotoService)
    {
        $this->productPhotoService = $productPhotoService;
    }

    /**
     * @OA\Post(
     *     path="/api/product-photos",
     *     tags={"Product Photos"},
     *     summary="Cria uma nova foto de produto",
     *     description="Cria uma nova foto de produto com os dados fornecidos",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductPhoto")
     *     ),
     *     @OA\Response(response="201", description="Foto de produto criada com sucesso"),
     *     @OA\Response(response="400", description="Erro na solicitação"),
     *     @OA\Response(response="404", description="Produto não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|exists:products,id',
            'url' => 'required|string',
        ]);

        $data = $request->all();

        try {
            $productPhoto = $this->productPhotoService->createProductPhoto($data);
            return response()->json($productPhoto, 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/product-photos/{id}",
     *     tags={"Product Photos"},
     *     summary="Atualiza uma foto de produto por ID",
     *     description="Atualiza os detalhes de uma foto de produto com base no ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da foto de produto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductPhoto")
     *     ),
     *     @OA\Response(response="200", description="Foto de produto atualizada com sucesso"),
     *     @OA\Response(response="400", description="Erro na solicitação"),
     *     @OA\Response(response="404", description="Foto de produto não encontrada"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'url' => 'required|string',
        ]);

        $data = $request->all();
        $productPhoto = $this->productPhotoService->updateProductPhoto($id, $data);

        if (!$productPhoto) {
            return response()->json(['error' => 'Foto do produto não encontrada.'], 404);
        }

        return response()->json($productPhoto);
    }

    /**
     * @OA\Delete(
     *     path="/api/product-photos/{id}",
     *     tags={"Product Photos"},
     *     summary="Exclui uma foto de produto por ID",
     *     description="Exclui uma foto de produto com base no ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da foto de produto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Foto de produto excluída com sucesso"),
     *     @OA\Response(response="404", description="Foto de produto não encontrada"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function delete($id)
    {
        $result = $this->productPhotoService->deleteProductPhoto($id);

        if (!$result) {
            return response()->json(['error' => 'Foto do produto não encontrada.'], 404);
        }

        return response()->json(['message' => 'Foto do produto deletada.'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/product-photos/{productId}",
     *     tags={"Product Photos"},
     *     summary="Obtém todas as fotos de um produto por ID",
     *     description="Obtém todas as fotos de um produto com base no ID do produto",
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Fotos do produto"),
     *     @OA\Response(response="404", description="Produto não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function getByProductId($productId)
    {
        $photos = $this->productPhotoService->getAllPhotosByProductId($productId);

        if (!$photos) {
            return response()->json(['error' => 'Foto do produto não encontrada.'], 404);
        }

        return response()->json($photos);
    }
}
