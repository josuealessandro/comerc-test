<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="API para gerenciar pedidos"
 * )
 */
class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Cria um novo pedido",
     *     description="Cria um novo pedido com os dados fornecidos",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="client_id",
     *                 description="ID do cliente para o qual o pedido será associado",
     *                 type="string"
     *             ),
     *             @OA\Property(
     *                 property="product_ids",
     *                 description="Array de IDs dos produtos a serem adicionados ao pedido",
     *                 type="array",
     *                 @OA\Items(type="string")
     *             ),
     *         )
     *     ),
     *     @OA\Response(response="201", description="Pedido criado com sucesso"),
     *     @OA\Response(response="400", description="Erro na solicitação"),
     *     @OA\Response(response="404", description="Cliente ou produto(s) não encontrado(s)"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'client_id' => 'required|exists:clients,id',
            'product_ids' => 'required|array',
        ]);

        $data = $request->all();

        try {
            $order = $this->orderService->createOrder($data);
            return response()->json($order, 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Exclui um pedido por ID",
     *     description="Exclui um pedido com base no ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do pedido",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Pedido excluído com sucesso"),
     *     @OA\Response(response="404", description="Pedido não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function delete($id)
    {
        $result = $this->orderService->deleteOrder($id);

        if (!$result) {
            return response()->json(['error' => 'Pedido não encontrado.'], 404);
        }

        return response()->json(['message' => 'Pedido deletado.'], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/by-product/{productId}",
     *     tags={"Orders"},
     *     summary="Obtém pedidos por produto",
     *     description="Obtém todos os pedidos que contêm o produto com base no ID do produto",
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         description="ID do produto",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Pedidos que contêm o produto"),
     *     @OA\Response(response="404", description="Produto não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function getByProduct($productId)
    {
        $orders = $this->orderService->getOrdersByProduct($productId);

        if (!count($orders)) {
            return response()->json(['error' => 'Pedido não encontrado.'], 404);
        }

        return response()->json($orders);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/by-client/{clientId}",
     *     tags={"Orders"},
     *     summary="Obtém pedidos por cliente",
     *     description="Obtém todos os pedidos associados a um cliente com base no ID do cliente",
     *     @OA\Parameter(
     *         name="clientId",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Pedidos associados ao cliente"),
     *     @OA\Response(response="404", description="Cliente não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function getByClient($clientId)
    {
        $orders = $this->orderService->getOrdersByClient($clientId);

        if (!count($orders)) {
            return response()->json(['error' => 'Pedido não encontrado.'], 404);
        }

        return response()->json($orders);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     tags={"Orders"},
     *     summary="Obtém um pedido por ID",
     *     description="Obtém um pedido com base no ID do pedido",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do pedido",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Pedido encontrado"),
     *     @OA\Response(response="404", description="Pedido não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function getById($id)
    {
        $order = $this->orderService->getOrderById($id);

        if (!$order) {
            return response()->json(['error' => 'Pedido não encontrado.'], 404);
        }

        return response()->json($order);
    }

    /**
     * @OA\Get(
     *     path="/api/orders",
     *     tags={"Orders"},
     *     summary="Lista todos os pedidos",
     *     description="Lista todos os pedidos existentes",
     *     @OA\Response(response="200", description="Lista de pedidos"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function getAll()
    {
        $orders = $this->orderService->getAllOrders();

        return response()->json($orders);
    }
}
