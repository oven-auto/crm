<?php

namespace App\Classes\LadaDNM;

use Illuminate\Support\Facades\Http;

class DNM
{
    private static $instance;
    
    private static $token; 
    
    private static $baseUrl;

    public $service;



    private function __construct()
    {
    }



    public static function init(): self
    {
        if (self::$instance !== null)
            return self::$instance;
        
        self::$instance = new self;
        
        self::$token = env("DNM_TOKEN");

        self::$baseUrl = env("DNM_URL");

        $headers = [
            'Authorization' => 'Bearer ' . self::$token,
            'Accept' => 'application/json',
            'Connection' => 'keep-alive',
            'Content-Type' => 'application/json; charset=utf-8',
        ];

        self::$instance->service = Http::withHeaders($headers);

        return self::$instance;
    }



    public function sendPost(string $url, array $data)
    {
        return $this->service->post($this->concatUrl($url), $data);
    }



    public function sendPut(string $url, array $data)
    {
        return $this->service->put($this->concatUrl($url), $data);
    }



    public function sendGet(string $url, array $data = [])
    {
        return $this->service->get($this->concatUrl($url), $data);
    }



    private function concatUrl($getParam)
    {
        $getParam = trim($getParam, '/');

        return self::$baseUrl . '/' . $getParam;
    }



    /**
     * Рекрсивное получение даннх из апишек ДНМ
     */
    public function recoursive(string $url, int $page = 1, array &$data = [])
    {
        $response = $this->service->get($this->concatUrl($url.'?page='.$page));
        
        $count = $response->header('X-Pagination-Page-Count');

        $data = array_merge($data, $response->json());
        
        if($page < $count)
            $this->recoursive($url, ++$page, $data);
        
        return $data;
    }



    /**
     * Получить бренды из ДНМ
     */
    public function getBrands()
    {
        return $this->recoursive('api/brand');
    }



    /**
     * Получить список моделей
     */
    public function getModels()
    {
        return $this->recoursive('api/model');
    }



    /**
     * Получить синонимы моделей
     */
    public function getModelAliases()
    {
        return $this->recoursive('api/model-alias');
    }



    /**
     * Получить начало года выпуска
     */
    public function getModelYears()
    {
        return $this->recoursive('api/model-year');
    }



    /**
     * Получить доступные должности ДНМ
     */
    public function getPositions()
    {
        return $this->recoursive('api/position');
    }



    /**
     * Получить список пользователей
     */
    public function getManagers()
    {
        return $this->recoursive('/api/manager');
    }



    /**
     * Получить каналы трафика
     */
    public function getSources()
    {
        return $this->recoursive('/api/source');
    }



    /**
     * Получить список типов событий
     */
    public function getEvents()
    {
        return $this->recoursive('/api/event-type');
    }



    /**
     * Получить список типов отказа от покупки
     */
    public function getResults()
    {
        return $this->recoursive('/api/lms/result');
    }



    //СЕРВИС



    /**
     * Получить справочник цехов
     */
    public function getWorkshop()
    {
        return $this->recoursive('/api/purchase-type');
    }



    /**
     * Получить типы оплаты
     */
    public function getPayment()
    {
        return $this->recoursive('/api/purchase-group');
    }



    /**
     * Получить справочник видов ремонта
     */
    public function getRepairsType()
    {
        return $this->recoursive('/api/purchase-tag');
    }
}