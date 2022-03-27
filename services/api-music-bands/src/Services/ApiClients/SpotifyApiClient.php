<?php
namespace MusicBands\Services\ApiClients;

use GuzzleHttp\Exception\ClientException;
use MusicBands\Exceptions\PublicException;
use MusicBands\Models\Provider;
use MusicBands\Models\Token;
use MusicBands\Services\ApiClients\ApiConfigs\ApiConfig;
use MusicBands\Services\ApiClients\ApiConfigs\SpotifyApiConfig;
use MusicBands\Services\ApiClients\ApiJsonMappers\ApiJsonMapper;
use MusicBands\Services\ApiClients\ApiJsonMappers\SpotifyJsonMapper;
use MusicBands\Services\ApiClients\Contracts\MusicApiClient;
use stdClass;
use Throwable;

/**
 * @property SpotifyJsonMapper $mapper
 * @property SpotifyApiConfig $config
 */
class SpotifyApiClient extends ApiClient implements MusicApiClient
{
    /** @var null|Token  */
    private $token;
    protected static $provider = Provider::SPOTIFY;

    public function __construct(ApiConfig $config, ApiJsonMapper $mapper)
    {
        parent::__construct($config, $mapper);
        $this->token = $this->redis->get($this->tokenCacheKey());
    }

    static function buildInstance(): SpotifyApiClient{
        return new SpotifyApiClient(SpotifyApiConfig::buildConfig(), new SpotifyJsonMapper);
    }

    public function searchAlbums(string $search): array{
        $apiResponse = $this->redis->remember($search, 600, function() use($search) {
            return $this->search($search, 'album');
        });

        $albums = [];
        foreach ($apiResponse->albums->items as $album){
            $albums[] = $this->mapper->album($album);
        }

        return $albums;
    }

    private function search(string $query, string $type): stdClass{
        $params = ['q' => $query, 'type' => $type];
        return $this->invokeSpotifyEndpoint('/search', function ($requestOptions) use ($params) {
            return $this->apiGet($requestOptions, $params);
        });
    }

    /**
     * @param string $endpoint
     * @param callable $callback
     * @param array $opts
     * @return stdClass
     * @throws PublicException
     * @throws Throwable
     */
    private function invokeSpotifyEndpoint(string $endpoint, callable $callback, array $opts = []): stdClass{
        try {
            $this->ensureHasValidToken();
            return $this->invokeEndpoint($endpoint, $callback, $opts);
        }catch (Throwable $th){
            if($th instanceof ClientException){
                $response = $th->getResponse();
                $responseObj = json_decode($response->getBody()->getContents());

                if(isset($responseObj->error)){
                    throw PublicException::externalError($responseObj->error->message . ' (endpoint:'.$endpoint.')', 'spotify_api_client', $responseObj->error->status);
                }
            }
            throw $th;
        }
    }

    private function ensureHasValidToken()
    {
        if(null === $this->token){
            $params = ['grant_type' => 'client_credentials'];
            $apiResponse =  $this->invokeEndpoint('/token', function ($requestOptions) use ($params) {
                return $this->apiPost($requestOptions, $params);
            }, ['authApi' => true]);

            $this->token = $this->mapper->token($apiResponse);
            $this->redis->remember($this->tokenCacheKey(), $this->token->getTimeRemaining(), function (){
                return $this->token;
            });
        }
    }

    protected function getEndpointWithRequestOptions(string $endpoint, array $options = []): array
    {
        $requireAuth = isset($options['authApi']) && true === $options['authApi'];
        $baseUrl = $requireAuth ? $this->config->getProperty(SpotifyApiConfig::AUTH_URL) : $this->config->getProperty(SpotifyApiConfig::API_URL);
        $requestOptions = [
            'url' => $baseUrl . $endpoint,
            'endpoint' => $endpoint,
            'headers' => [],
            'options' => [ // GuzzleHttp Options
                'connect_timeout' => $this->config->getProperty(SpotifyApiConfig::TIMEOUT) ?? 30,
                'verify' => false,
                'http_errors' => true
            ]
        ];

        if ($requireAuth) {
            $requestOptions['options']['auth'] = [
                $this->config->getProperty(SpotifyApiConfig::CLIENT_ID),
                $this->config->getProperty(SpotifyApiConfig::CLIENT_SECRET)
            ];
        }else{
            $requestOptions['headers']['Authorization'] = 'Bearer ' . $this->token->getValue();
        }

        return $requestOptions;
    }

    private function tokenCacheKey(): string {
        return self::$provider.'-token-v4';
    }
}
