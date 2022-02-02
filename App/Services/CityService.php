<?php

namespace App\Services;

use App\City;

class CityService
{
    private $cityObject;

    public function __construct()
    {
        $this->cityObject = new City;
    }

    public function getCityServices(int $columnId = null, int $page = null, int $pageSize = null)
    {
        return $this->cityObject->get($columnId, $page, $pageSize);
    }

    public function addCityServices(array $parameters): int
    {
        return $this->cityObject->add($parameters);
    }

    public function updateCityServices(array $parameters): bool
    {
        return $this->cityObject->update($parameters);
    }

    public function deleteCityServices(int $columnId): bool
    {
        return $this->cityObject->delete($columnId);
    }
}
