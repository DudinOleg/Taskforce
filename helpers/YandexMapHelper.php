<?php


namespace app\helpers;


use Yandex\Geo\Api;
use Yandex\Geo\Exception;

class YandexMapHelper
{

    /**
     * @var Api
     */
    private $apiClient;

    /**
     * YandexMapHelper constructor.
     * @param $key
     */
    public function __construct($key)
    {
        $this->apiClient = new Api();
        $this->apiClient->setToken($key);
    }

    public function getCoordinates($city, $location)
    {
        $result = [];
        $query = $city . ', ' . $location;

        try {
            $this->apiClient->setQuery($query);
            $this->apiClient->load();

            $response = $this->apiClient->getResponse();
            $results = $response->getList();

            if ($results) {
                $geoObject = $results[0];
                $result = [$geoObject->getLatitude(), $geoObject->getLongitude()];
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return $result;
    }
}