<?php

namespace App\Service;

use GuzzleHttp\Client;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class Translator
{

    public function __construct(){

    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLanguages()
    {
        $params = array(
            "method" => "GET",
            "endpoint" => '/languages?api-version=3.0&scope=translation',
            "data" => []
        );
        return $this->query($params);
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function translate($translateParams)
    {
        $from = ($translateParams['sourceLanguage']) ? "&from=" . $translateParams['sourceLanguage'] : '';
        $to = $translateParams['targetLanguage'];
        $params = array(
            "method" => "POST",
            "endpoint" => '/translate?api-version=3.0&to=' . $to . $from,
            "data" => array(
                ["Text" => $translateParams['sourceText']]
            )
        );

        return $this->query($params);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function query($params)
    {
        $key = '7c586f2d663f4b99beca2652a2217fc9';
        $region = 'westus2';

        $client = new Client(['base_uri' => 'https://api.cognitive.microsofttranslator.com']);
        $requestParameters = [
            'headers' => [
                'Ocp-Apim-Subscription-Key' => $key,
                'Ocp-Apim-Subscription-Region' => $region,
                'Content-Type' => 'application/json'
            ],
            'timeout' => 60,
            'json' => $params['data']
        ];
        $res = $client->request($params['method'], $params['endpoint'], $requestParameters);
        $serializer = new JsonDecode();
        return $serializer->decode($res->getBody()->getContents(),"object");
    }
}

