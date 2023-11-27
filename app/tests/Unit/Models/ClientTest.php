<?php

namespace Tests\Unit\Models;

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
        $this->faker = FakerFactory::create();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /** @test */
    public function testCanCreateAClient()
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

        $client = Client::create($clientData);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals($clientData['name'], $client->name);
        $this->assertEquals($clientData['email'], $client->email);
    }

    public function testCheckEmailUniqueness()
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

        $client = Client::create($clientData);

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
    }

    public function testSoftDeleteClient()
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

        $client = Client::create($clientData);

        $client->delete();

        $this->assertNull(Client::find($client->id));
        $this->assertTrue(Client::withTrashed()->where('id', $client->id)->exists());
    }
}
