<?php

namespace MusicBands\Services\ApiClients;

use MusicBands\Exceptions\PublicException;
use MusicBands\Models\Provider;
use MusicBands\Services\ApiClients\ApiConfigs\ApiConfig;
use MusicBands\Services\ApiClients\ApiJsonMappers\ApiJsonMapper;
use MusicBands\Services\RedisService;
use MusicBands\Traits\ApiRequest;
use Psr\Http\Message\ResponseInterface;

abstract class ApiClient
{
    use ApiRequest;

    /** @var ApiConfig  */
    protected $config;

    /** @var ApiJsonMapper  */
    protected $mapper;

    /** @var Provider */
    protected static $provider;

    /** @var RedisService  */
    protected $redis;

    public function __construct(ApiConfig $config, ApiJsonMapper $mapper)
    {
        $this->config = $config;
        $this->mapper = $mapper;
        $this->redis = RedisService::buildInstance();
    }

    protected function invokeEndpoint(string $endpoint, callable $callback, array $opts = []): \stdClass{
        $requestParams = $this->getEndpointWithRequestOptions($endpoint, $opts);

        try {
            /** @var ResponseInterface $httpRes */
            $httpRes = call_user_func($callback, $requestParams);
            if( !($httpRes instanceOf ResponseInterface) ) {
                throw PublicException::internalError(__('Internal: callback did not return an ClientResponse'), 'internal_error');
            }

            $responseContentType = $httpRes->getHeader('Content-Type');
            if(!$responseContentType){
                throw PublicException::externalError(__('No content-type in response'), 'bad_content_type');
            }

            $json = $httpRes->getBody()->getContents();
            if( !$json ) {
                throw PublicException::externalError(__('Response is not json'), 'bad_content_type');
            }

            return json_decode($json);
        } catch( \Throwable $ex) {
            // ADD LOG TOOL
//            \Log::error(__FUNCTION__.':'.$ex->getMessage(), ['requestParams' => $requestParams]);
            throw $ex;
        }
    }

    abstract protected function getEndpointWithRequestOptions(string $endpoint, array $options = []): array;
}