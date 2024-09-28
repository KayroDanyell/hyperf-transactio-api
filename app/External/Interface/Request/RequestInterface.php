<?php

namespace App\External\Interface\Request;

use GuzzleHttp\ClientInterface;

interface RequestInterface
{
    public function getClient() : ClientInterface;

    public function authorize();
}