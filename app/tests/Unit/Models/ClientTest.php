<?php

namespace Tests\Unit\Models;

// tests/Unit/ClientTest.php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Models\Client;

class ClientTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /** @test */
    public function can_create_a_client_and_check_email_uniqueness()
    {
        // Crie um cliente
        $clientData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123-456-7890',
            'birthday' => '1990-01-01',
            'address1' => '123 Main St',
            'postalcode' => '12345',
            'city' => 'Example City',
            'state' => 'CA',
        ];

        $client = Client::create($clientData);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals($clientData['name'], $client->name);
        $this->assertEquals($clientData['email'], $client->email);

        // Tente criar outro cliente com o mesmo e-mail
        $this->expectException(\PDOException::class);
        $this->expectExceptionMessage('Duplicate entry');

        Client::create([
            'name' => 'Jane Smith',
            'email' => 'john@example.com', // Mesmo e-mail
            'phone' => '987-654-3210',
            'birthday' => '1985-05-05',
            'address1' => '456 Elm St',
            'postalcode' => '54321',
            'city' => 'Another City',
            'state' => 'NY',
        ]);
    }

    /** @test */
    public function can_soft_delete_a_client()
    {
        $clientData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '123-456-7890',
            'birthday' => '1990-01-01',
            'address1' => '123 Main St',
            'postalcode' => '12345',
            'city' => 'Example City',
            'state' => 'CA',
        ];

        $client = Client::create($clientData);

        $client->delete();

        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }
}
