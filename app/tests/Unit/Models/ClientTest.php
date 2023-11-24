<?php

namespace Tests\Unit\Models;

// tests/Unit/ClientTest.php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Client;

class ClientTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function can_create_a_client_check_email_uniqueness_and_soft_delete()
    {
        // Crie um cliente
        $clientData = [
            'name' => 'Josué Alessandro',
            'email' => 'josuealessandro@gmail.com',
            'phone' => '11992886337',
            'birthday' => '1987-10-17',
            'address1' => 'Rua Geolandia, 1009',
            'address3' => 'Vila Medeiros',
            'postalcode' => '02217000',
            'city' => 'São Paulo',
            'state' => 'SP',
        ];

        // Teste a criação do cliente
        $client = Client::create($clientData);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals($clientData['name'], $client->name);
        $this->assertEquals($clientData['email'], $client->email);

        // Tente criar outro cliente com o mesmo e-mail (teste de duplicidade)
        $this->expectException(\PDOException::class);
        $this->expectExceptionMessage('Duplicate entry');

        Client::create([
            'name' => 'Josué Omena',
            'email' => 'josuealessandro@gmail.com',
            'phone' => '11992886337',
            'birthday' => '1987-10-17',
            'address1' => 'Rua Geolandia, 1009',
            'address3' => 'Vila Medeiros',
            'postalcode' => '02217000',
            'city' => 'São Paulo',
            'state' => 'SP',
        ]);

        // Exclua suavemente o cliente
        $client->delete();

        // Verifique se o cliente foi excluído suavemente
        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }
}
