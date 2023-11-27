<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function createClient(array $data)
    {
        return Client::create($data);
    }

    public function getClientByEmail($email)
    {
        return Client::where('email', $email)->first();
    }

    public function updateClient($id, array $data)
    {
        $client = Client::find($id);
        if (!$client) {
            return null;
        }
        $client->update($data);
        return $client;
    }

    public function deleteClient($id)
    {
        $client = Client::find($id);
        if (!$client) {
            return null;
        }
        return $client->delete();
    }

    public function getAllClients()
    {
        // Retorna todos os clientes da tabela 'clients'
        return Client::all();
    }

    public function getClientById($id)
    {
        return Client::find($id);
    }
}
