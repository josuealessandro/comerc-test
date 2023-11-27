<?php

// app/Http/Controllers/ClientController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ClientService;

/**
 * @OA\Tag(
 *     name="Clients",
 *     description="API para gerenciar clientes"
 * )
 * @OA\Info(
 *     version="1.0",
 *     title="API Comerc",
 *     description="API para teste de emprego na Comerc",
 *     @OA\Contact(name="Josué Alessandro")
 * )
 * @OA\Schema(
 *     schema="Client",
 *     required={"name", "email", "phone", "birthday", "address1", "postalcode", "city", "state"},
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="phone", type="string"),
 *     @OA\Property(property="birthday", type="string"),
 *     @OA\Property(property="address1", type="string"),
 *     @OA\Property(property="address2", type="string"),
 *     @OA\Property(property="address3", type="string"),
 *     @OA\Property(property="postalcode", type="string"),
 *     @OA\Property(property="city", type="string"),
 *     @OA\Property(property="state", type="string"),
 * )
 */
class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * @OA\Post(
     *     path="/api/clients",
     *     tags={"Clients"},
     *     summary="Cria um novo cliente",
     *     description="Cria um novo cliente com os dados fornecidos",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Client")
     *     ),
     *     @OA\Response(response="201", description="Cliente criado com sucesso"),
     *     @OA\Response(response="400", description="Erro na solicitação"),
     *     @OA\Response(response="422", description="Error: Unprocessable Content"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|string',
            'birthday' => 'required|date',
            'address1' => 'required|string',
            'address2' => 'nullable|string',
            'address3' => 'nullable|string',
            'postalcode' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
        ]);

        $data = $request->all();
        $client = $this->clientService->createClient($data);

        return response()->json($client, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/clients/email/",
     *     tags={"Clients"},
     *     summary="Obtém um cliente por e-mail",
     *     description="Obtém os detalhes de um cliente com base no e-mail",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="email",
     *                 description="e-mail do cliente",
     *                 type="string"
     *             ),
     *         )
     *     ),
     *     @OA\Response(response="200", description="Detalhes do cliente"),
     *     @OA\Response(response="404", description="Cliente não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function getByEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email',
        ]);

        $email = $request->input('email');

        $client = $this->clientService->getClientByEmail($email);

        if (!$client) {
            return response()->json(['error' => 'Cliente não encontrado.'], 404);
        }

        return response()->json($client);
    }

    /**
     * @OA\Get(
     *     path="/api/clients",
     *     tags={"Clients"},
     *     summary="Lista todos os clientes",
     *     description="Obtém a lista de todos os clientes cadastrados",
     *     @OA\Response(response="200", description="Lista de clientes"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function getAll()
    {
        $clients = $this->clientService->getAllClients();

        return response()->json($clients);
    }

    /**
     * @OA\Put(
     *     path="/api/clients/{id}",
     *     tags={"Clients"},
     *     summary="Atualiza um cliente por ID",
     *     description="Atualiza os detalhes de um cliente com base no ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Client")
     *     ),
     *     @OA\Response(response="200", description="Cliente atualizado com sucesso"),
     *     @OA\Response(response="400", description="Erro na solicitação"),
     *     @OA\Response(response="422", description="Error: Unprocessable Content"),
     *     @OA\Response(response="404", description="Cliente não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'string',
            'phone' => 'string',
            'birthday' => 'date',
            'address1' => 'string',
            'address2' => 'string',
            'address3' => 'string',
            'postalcode' => 'string',
            'city' => 'string',
            'state' => 'string',
        ]);

        $data = $request->all();
        $client = $this->clientService->updateClient($id, $data);

        if (!$client) {
            return response()->json(['error' => 'Cliente não encontrado.'], 404);
        }

        return response()->json($client);
    }

    /**
     * @OA\Delete(
     *     path="/api/clients/{id}",
     *     tags={"Clients"},
     *     summary="Exclui um cliente por ID",
     *     description="Exclui um cliente com base no ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do cliente",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Cliente excluído com sucesso"),
     *     @OA\Response(response="404", description="Cliente não encontrado"),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function delete($id)
    {
        $result = $this->clientService->deleteClient($id);

        if (!$result) {
            return response()->json(['error' => 'Cliente não encontrado.'], 404);
        }

        return response()->json(['message' => 'Cliente deletado.'], 200);
    }
}
