<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEndPoint extends WebTestCase
{
    private array $serverInformation = [
        'ACCEPT' => 'application/json',
        'CONTENT_TYPE' => 'application/json',
    ];

    protected string $tokenNotFound = 'JWT Token not found';
    protected string $notYourResource = 'You are not authorized to edit this resource.';
    protected string $loginPayload = '{"username": "%s", "password": "%s"}';

    public function getResponseFromRequest(
        string $method,
        string $uri,
        string $payload = '',
        array $parameter = [],
        bool $withAuthentication = true
    ): Response {
        $client = $this->createAuthenticationClient($withAuthentication);

        $client->request(
            $method,
            $uri.'.json',
            $parameter,
            [],
            $this->serverInformation,
            $payload
        );

        return $client->getResponse();
    }

    protected function createAuthenticationClient(bool $withAuthentication): KernelBrowser
    {
        $client = self::createClient();

        if (!$withAuthentication) {
            return $client;
        } else {
            $client->request(
                Request::METHOD_POST,
                '/api/login_check',
                [],
                [],
                $this->serverInformation,
                sprintf($this->loginPayload, AppFixtures::DEFAULT_USER['email'], AppFixtures::DEFAULT_USER['password'])
            );

            $data = json_decode($client->getResponse()->getContent(), true);

            $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

            return $client;
        }
    }
}
