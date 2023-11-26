<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ClientService;
use App\Models\Client;
use Faker\Factory as FakerFactory;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ClientServiceTest extends TestCase
{
    use DatabaseTransactions;
    protected $clientService;
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

    public function testCreateClient()
    {
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

        $createdClient = $this->clientService->createClient($clientData);

        $this->assertInstanceOf(Client::class, $createdClient);
    }

    public function testGetClientByEmail()
    {
        $email = $this->faker->unique()->safeEmail;
        $clientData = [
            'name' => $this->faker->name,
            'email' => $email,
            'phone' => $this->faker->phoneNumber,
            'birthday' => $this->faker->date,
            'address1' => $this->faker->streetAddress,
            'postalcode' => $this->faker->postcode,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
        ];
        $this->clientService->createClient($clientData);

        $foundClient = $this->clientService->getClientByEmail($email);

        $this->assertInstanceOf(Client::class, $foundClient);
        $this->assertEquals($clientData['name'], $foundClient->name);
        $this->assertEquals($email, $foundClient->email);
    }

    public function testUpdateClient()
    {
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
        $createdClient = $this->clientService->createClient($clientData);

        $updatedData = [
            'name' => 'Updated Name',
            'email' => $this->faker->unique()->safeEmail,
        ];

        $updatedClient = $this->clientService->updateClient($createdClient->id, $updatedData);

        $this->assertInstanceOf(Client::class, $updatedClient);
        $this->assertEquals($updatedData['name'], $updatedClient->name);
        $this->assertEquals($updatedData['email'], $updatedClient->email);
    }

    public function testDeleteClient()
    {
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
        $createdClient = $this->clientService->createClient($clientData);

        $deleted = $this->clientService->deleteClient($createdClient->id);

        $this->assertTrue($deleted);
    }

    public function testDeleteNonExistentClient()
    {
        $deleted = $this->clientService->deleteClient(9999);

        $this->assertNull($deleted);
    }
    public function testUniqueEmailValidation()
    {
        $email = $this->faker->unique()->safeEmail;
        $clientData1 = [
            'name' => $this->faker->name,
            'email' => $email,
            'phone' => $this->faker->phoneNumber,
            'birthday' => $this->faker->date,
            'address1' => $this->faker->streetAddress,
            'postalcode' => $this->faker->postcode,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
        ];
        $this->clientService->createClient($clientData1);

        $clientData2 = [
            'name' => $this->faker->name,
            'email' => $email, // Mesmo e-mail novamente
            'phone' => $this->faker->phoneNumber,
            'birthday' => $this->faker->date,
            'address1' => $this->faker->streetAddress,
            'postalcode' => $this->faker->postcode,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
        ];

        $this->expectException(\Exception::class); // Você pode ajustar o tipo de exceção específico conforme necessário
        $this->clientService->createClient($clientData2);
    }

    private function createSampleClient()
    {
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

        return $this->clientService->createClient($clientData);
    }
}
