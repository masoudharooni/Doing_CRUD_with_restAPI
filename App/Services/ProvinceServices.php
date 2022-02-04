<?php

namespace App\Services;

use App\Province;

class ProvinceServices
{
    private $provinceObject;

    public function __construct()
    {
        $this->provinceObject = new Province;
    }

    public function getProvinceServices(int $columnId = null, int $page = null, int $pageSize = null, string $fields = null, string $order = null)
    {
        return $this->provinceObject->get($columnId, $page, $pageSize, $fields, $order);
    }

    public function addProvinceServices(array $parameters): int
    {
        return $this->provinceObject->add($parameters);
    }

    public function updateProvinceServices(array $parameters): bool
    {
        return $this->provinceObject->update($parameters);
    }

    public function deleteProvinceServices(int $columnId): bool
    {
        return $this->provinceObject->delete($columnId);
    }
}
