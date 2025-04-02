<?php

namespace Itb\Ssr;

use Bitrix\Main\Web\Json;
use Grpc\ChannelCredentials;
use Itb\Core\Config;
use Itb\Ssr\Generated\RenderRequest;
use Itb\Ssr\Generated\SSRServiceClient;

class GrpcSsrHelper
{
    /**
     * @throws InvalidArgumentException
     */
    public function getContent(string $page, ?array $data = null): ?string
    {
        if(!Config::isEnableViteSsr() || !Config::isProduction()) return null;
        $host = Config::getViteSsrHost();
        $port = Config::getViteSsrPort();
        $client = new SSRServiceClient("{$host}:{$port}", [
            'credentials' => ChannelCredentials::createInsecure()
        ]);

        $request = new RenderRequest();
        $request->setPage($page);
        if ($data) {
            $request->setData(Json::encode($data));
        }

        list($response, $status) = $client->RenderPage($request)->wait();
        if ($status->code === \Grpc\STATUS_OK) {
            return $response->getHtml();
        }
        return null;
    }

    /**
     * @param int $timeout in microseconds
     */
    public function ssrServerIsAvailable(int $timeout = 1000000): bool
    {
        $host = Config::getViteSsrHost();
        $port = Config::getViteSsrPort();
        
        $client = new SSRServiceClient("{$host}:{$port}", [
            'credentials' => ChannelCredentials::createInsecure()
        ]);
        
        try {
            return $client->waitForReady($timeout);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
