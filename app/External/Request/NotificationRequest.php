<?php

namespace App\External\Request;

use App\External\Interface\Request\RequestInterface;
use App\External\Request\Abstract\AbstractRequest;
use Swoole\Http\Status;

class NotificationRequest extends AbstractRequest implements RequestInterface
{
    const ENDPOINT = '/notify';
    public function __construct()
    {
        parent::__construct();
    }
    public function makeRequest(array $params=null)
    {
        $response = $this->getClient()
            ->request('POST', config('notification_service_uri').self::ENDPOINT, $params);
        $responseData = json_decode($response->getBody()->getContents(), true)['data'];

        return $responseData;
    }
}