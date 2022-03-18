<?php
declare(strict_types=1);


namespace App\Tests\Functional;

use Faker\Factory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleTest extends AbstractEndPoint
{
    private string $articlePayload = '{"name": "%s", "content": "%s", "author": "%s"}';
    public function testGetArticles(): void
    {
        $response = $this->getResponseFromRequest(Request::METHOD_GET, '/api/articles');

        $responseContent = $response->getContent();
        $responseDecoded = json_decode($responseContent);

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($responseContent);
        self::assertNotEmpty($responseDecoded);
    }

    public function testPostArticles(): void
    {
        $response = $this->getResponseFromRequest(
            Request::METHOD_POST,
            '/api/articles',
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

        return sprintf($this->articlePayload, $faker->text(50), $faker->text(300), '/api/users/1');
    }
}