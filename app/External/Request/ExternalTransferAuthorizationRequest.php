<?php

namespace App\External\Request;

use App\External\Interface\Request\RequestInterface;
use App\External\Request\Abstract\AbstractRequest;
use Swoole\Http\Status;

class ExternalTransferAuthorizationRequest extends AbstractRequest implements RequestInterface
{

    public function __construct()
    {
        parent::__construct();
    }
    public function authorize()
    {
        $response = $this->getClient()->request('GET', config('auth_service_uri'));
        $responseData = json_decode($response->getBody()->getContents(), true)['data'];
        
        return $responseData['authorization'] && $response->getStatusCode() == Status::OK;
    }
}