<?php

namespace App\External\Request;

use App\External\Interface\Request\RequestInterface;
use App\External\Request\Abstract\AbstractRequest;
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Contract\StdoutLoggerInterface;
use Swoole\Http\Status;

class ExternalTransferAuthorizationRequest extends AbstractRequest implements RequestInterface
{
    const ENDPOINT = '/authorize';
    public function __construct(private StdoutLoggerInterface $logger)
    {
        parent::__construct();
    }
    public function makeRequest(array $params=null)
    {
        try {
        $response = $this->getClient()->request('GET', config('authorization_service_uri').self::ENDPOINT);

        $responseData = json_decode($response->getBody()->getContents(), true)['data'];
        return $responseData['authorization'] && $response->getStatusCode() == Status::OK;

        } catch (GuzzleException $exception) {
            $this->logger->error($exception->getMessage());
            return false;
        }
    }
}