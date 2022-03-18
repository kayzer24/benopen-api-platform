<?php

namespace App\Tests\Functional;

use Faker\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserTest extends AbstractEndPoint
{
    private string $userPayload = '{"email": "%s", "password": "password"}';
    public function testGetUsers(): void
    {
        $response = $this->getResponseFromRequest(Request::METHOD_GET, '/api/users');

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    public function testPostUsers(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/users',
            $this->getPayload()
        );

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    private function getPayload():string
    {
        $faker = Factory::create();

        return sprintf($this->userPayload, $faker->email());
    }
}
