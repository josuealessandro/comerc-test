<?php

namespace Tests\Unit\Models;

// tests/Unit/ClientTest.php

use App\Services\ClientService;
use Faker\Factory as FakerFactory;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Client;

class ClientTest extends TestCase
{
    use DatabaseTransactions;
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->clientService = new ClientService();
        $this->faker = FakerFactory::create();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    public function can_create_a_client_check_email_uniqueness_and_soft_delete()
    {
        // Crie um cliente
        $clientData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'birthday' => $this->faker->date,
            'address1' => $this->faker->streetAddress,
            'postalcode' => $this->faker->postcode,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
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
            'name' => $this->faker->name,
            'email' => $client->email,
            'phone' => $this->faker->phoneNumber,
            'birthday' => $this->faker->date,
            'address1' => $this->faker->streetAddress,
            'postalcode' => $this->faker->postcode,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
        ]);

        // Exclua suavemente o cliente
        $client->delete();

        // Verifique se o cliente foi excluído suavemente
        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }
}
