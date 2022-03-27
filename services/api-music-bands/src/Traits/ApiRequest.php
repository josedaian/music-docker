<?php

namespace MusicBands\Traits;

use GuzzleHttp\Client;

trait ApiRequest
{
    /**
     * @param array $requestOptions
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function apiGet(array $requestOptions, array $data = []) {
        return $this->buildClient()
            ->request('GET', $requestOptions['url'], array_merge(
                ['query' => $data],
                $requestOptions['options'],
                ['headers' => $requestOptions['headers']]
            ));
    }

    /**
     * @param array $requestOptions
     * @param array $data
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function apiPost(array $requestOptions, array $data = []){
        return $this->buildClient()
            ->request('POST', $requestOptions['url'], array_merge(
                ['form_params' => $data],
                $requestOptions['options'],
                ['headers' => $requestOptions['headers']]
            ));
    }

    function buildClient(): Client{
        return new Client();
   }
}