<?php

namespace Itb\Ssr;

use Bitrix\Main\Web\Json;
use Grpc\ChannelCredentials;
use Itb\Core\Assets\Vite;
use Itb\Ssr\Generated\RenderRequest;
use Itb\Ssr\Generated\SSRServiceClient;

class SsrService
{
    /**
     * @throws InvalidArgumentException
     */
    public static function getContent(string $page, ?array $data = null): ?string
    {
        if(!Vite::ssrEnable() || !Vite::isProduction()) return null;
        $host = Vite::getSsrHost();
        $port = Vite::getSsrPort();
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
    public static function ssrServerIsAvailable(int $timeout = 1000000): bool
    {
        $host = Vite::getSsrHost();
        $port = Vite::getSsrPort();
        
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
