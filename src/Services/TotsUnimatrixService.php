<?php

namespace Tots\Unimatrix\Services;

use GuzzleHttp\Psr7\Request;

class TotsUnimatrixService
{
    /**
     * URL de la API
     */
    const BASE_URL_GLOBAL = 'https://api.unimtx.com/';
    const BASE_URL_CHINA = 'https://api-cn.unimtx.com/';
    const BASE_URL_EUROPE = 'https://api-eu.unimtx.com/';
    const BASE_URL_SINGAPORE = 'https://api-sg.unimtx.com/';

    /**
     *
     * @var array
     */
    protected $config = [];
    /**
     * 
     * @var string
     */
    protected $accessKey = '';
    /**
     * 
     * @var string
     */
    protected $baseUrl = '';
    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    public function __construct($config)
    {
        $this->config = $config;
        $this->processConfig();
        $this->guzzle = new \GuzzleHttp\Client();
    }

    public function sendSMS($phone, $text)
    {
        // Verify if phone start with +
        if ($phone[0] !== '+') {
            $phone = '+' . $phone;
        }

        return $this->generateRequest('sms.message.send', [
            'to' => $phone,
            'text' => $text,
        ]);
    }

    protected function generateRequest($action, $params = null)
    {
        $body = null;
        if($params != null){
            $body = json_encode($params);
        }

        $request = new Request(
            'POST', 
            $this->baseUrl . '?action=' . $action . '&accessKeyId=' . $this->accessKey,
            [
                'Content-Type' => 'application/json',
            ], $body);

        $response = $this->guzzle->send($request);
        if($response->getStatusCode() == 200){
            return json_decode($response->getBody()->getContents());
        }

        return null;
    }

    protected function processConfig()
    {
        if(array_key_exists('access_key', $this->config)){
            $this->accessKey = $this->config['access_key'];
        }
        if(array_key_exists('region', $this->config)){
            $this->baseUrl = $this->config['region'] == 'eu' ? self::BASE_URL_EUROPE : self::BASE_URL_GLOBAL;
        } else {
            $this->baseUrl = self::BASE_URL_GLOBAL;
        }
    }
}
