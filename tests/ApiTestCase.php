<?php

declare(strict_types=1);

namespace App\Tests;

use JsonException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

abstract class ApiTestCase extends WebTestCase
{
    protected ?KernelBrowser $client;

    protected $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = static::getContainer()->get('doctrine')->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();

        $this->client = null;
    }

    /**
     * @throws JsonException
     */
    protected function getDecodedResponseContent(): array
    {
        return \json_decode($this->client->getResponse()->getContent(), true, flags: JSON_THROW_ON_ERROR);
    }

    protected function get(string $uri, array $params = []): void
    {
        $this->client->request(Request::METHOD_GET, $uri, $params, [], $this->headers());
    }

    protected function post(string $uri, array $params): void
    {
        $this->client->request(Request::METHOD_POST, $uri, [], [], $this->headers(), (string) \json_encode($params));
    }

    protected function put(string $uri, array $params): void
    {
        $this->client->request(Request::METHOD_PUT, $uri, [], [], $this->headers(), (string) \json_encode($params));
    }

    protected function patch(string $uri, array $params): void
    {
        $this->client->request(Request::METHOD_PATCH, $uri, [], [], $this->headers(), (string) \json_encode($params));
    }

    protected function delete(string $uri, array $params): void
    {
        $this->client->request(Request::METHOD_DELETE, $uri, [], [], $this->headers(), (string) \json_encode($params));
    }

    protected function getFile(string $uri, array $params = []): void
    {
        $this->client->request(Request::METHOD_GET, $uri, $params, [], $this->csvHeaders());
    }

    private function headers(): array
    {
        return [
            'CONTENT_TYPE' => 'application/json',
        ];
    }

    private function csvHeaders(): array
    {
        return [
            'CONTENT_TYPE' => 'text/csv',
        ];
    }
}
