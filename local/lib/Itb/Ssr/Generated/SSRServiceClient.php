<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Itb\Ssr\Generated;

/**
 * Описание gRPC-сервиса с одним методом RenderPage
 */
class SSRServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Itb\Ssr\Generated\RenderRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function RenderPage(\Itb\Ssr\Generated\RenderRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/Ssr.SSRService/RenderPage',
        $argument,
        ['\Itb\Ssr\Generated\RenderResponse', 'decode'],
        $metadata, $options);
    }

}
