<?php

namespace Tests\Unit\Http\Controllers;

use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Faker\Factory as FakerFactory;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ClientControllerTest extends TestCase
{
    use DatabaseTransactions;
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
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
            'address1' => $this->faker->address,
            'postalcode' => $this->faker->postcode,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
        ];

        $this->post('/api/clients', $clientData)
            ->seeStatusCode(201)
            ->seeInDatabase('clients', $clientData);
    }

    public function testGetClientByEmail()
    {
        $client = Client::factory()->create();

        $this->post('/api/clients/email', ['email' => $client->email])
            ->seeStatusCode(200)
            ->seeInDatabase('clients', ['email' => $client->email]);
    }

    public function testUpdateClient()
    {
        $client = Client::factory()->create();
        $newData = [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'birthday' => $this->faker->date,
            'address1' => $this->faker->address,
            'postalcode' => $this->faker->postcode,
            'city' => $this->faker->city,
            'state' => $this->faker->state,
        ];

        $this->put("/api/clients/{$client->id}", $newData)
            ->seeStatusCode(200)
            ->seeInDatabase('clients', $newData);
    }

    public function testDeleteClient()
    {
        $client = Client::factory()->create();

        $this->delete("/api/clients/{$client->id}")
            ->seeStatusCode(200);

        $this->assertCount(0,
            DB::table('clients')
                ->where('id', $client->id)
                ->whereNull('deleted_at')
                ->get()
        );
    }
}
