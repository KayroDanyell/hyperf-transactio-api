<?php

namespace App\External\Request\Abstract;

use App\External\Interface\Request\RequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

abstract class AbstractRequest implements RequestInterface
{
    protected ClientInterface $client;
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('EXTERNAL_BASE_URL'),
            'timeout' => 5.0,
            'swoole' => [
                'timeout' => 10,
                'socket_buffer_size' => 1024 * 1024 * 2,
            ]
        ]);
    }

    public function getClient() : ClientInterface
    {
        return $this->client;
    }
}