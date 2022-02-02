<?php

use App\Services\CityService;
use App\Utilities\Response;
use App\Validator;

include_once "../../../authoload.php";

$requestMethod = $_SERVER['REQUEST_METHOD'];
$cityServices = new CityService;
$cityValidator = new Validator;
$getRequestData = json_decode(file_get_contents('php://input'), true);
// We don't have any break command in the our switch case, because we use respondByDie method and it will die script after running
switch ($requestMethod) {
    case 'GET':
        $requestGetData = [
            'cityID' => $_GET['city_id'] ?? null,
            'pageSize' => $_GET['page_size'] ?? null,
            'page' => $_GET['page'] ?? null,
            'fields' => $_GET['fields'] ?? '*'
        ];
        $dataGetValidator = [
            "city" => !is_null($requestGetData['cityID']) || !is_numeric($requestGetData['cityID']),
            "page" => !is_null($requestGetData['page']) || !is_numeric($requestGetData['page']),
            "pageSize" => !is_null($requestGetData['pageSize']) || !is_numeric($requestGetData['pageSize']),
            "fields" => !is_null($requestGetData['fields']) || !is_string($requestGetData['fields'])
        ];
        if (!$dataGetValidator['fields'])
            Response::respondByDie(["Error" => "Parameters is not valid!"], Response::HTTP_NOT_ACCEPTABLE);

        if (!$cityValidator->areValidFields($requestGetData['fields']))
            Response::respondByDie(["Error" => "Fields not exist!!"], Response::HTTP_NOT_ACCEPTABLE);



        if (!$dataGetValidator['city'] || !$dataGetValidator['page'] || !$dataGetValidator['pageSize'])
            Response::respondByDie(["Error" => "Parameters is not valid!"], Response::HTTP_NOT_ACCEPTABLE);

        if (is_numeric($requestGetData['cityID']))
            if (!$cityValidator->isExistCity($requestGetData['cityID']))
                Response::respondByDie(["Error" => "This city is not exist!"], Response::HTTP_NOT_ACCEPTABLE);

        $responseData = $cityServices->getCityServices($requestGetData['cityID'], $requestGetData['page'], $requestGetData['pageSize'], $requestGetData['fields']);
        Response::respondByDie($responseData, Response::HTTP_OK);

    case 'POST':
        $cityIdAdded = $cityServices->addCityServices($getRequestData);
        if ($cityValidator->isValidCity($getRequestData)) {
            $respons = $cityServices->getCityServices($cityIdAdded);
            Response::respondByDie($respons, Response::HTTP_CREATED);
        }
        Response::respondByDie(["Error" => "Parameters is not valid!"], Response::HTTP_NOT_ACCEPTABLE);

    case 'PUT':
        if (!is_numeric($getRequestData['id']) or empty($getRequestData['name']))
            Response::respondByDie(["Error" => "Parameters is not valid!"], Response::HTTP_NOT_ACCEPTABLE);
        if ($cityValidator->isExistCity($getRequestData['id'])) {
            $cityServices->updateCityServices($getRequestData);
            $cityUpdatedData = $cityServices->getCityServices($getRequestData['id']);
            Response::respondByDie($cityUpdatedData, Response::HTTP_OK);
        }
        Response::respondByDie(["Error" => "City not Exsist!"], Response::HTTP_NOT_FOUND);

    case 'DELETE':
        $cityIdForDelete = $getRequestData['id'];
        if (!is_numeric($cityIdForDelete) || empty($cityIdForDelete))
            Response::respondByDie(["Error" => "Parameters is not valid!"], Response::HTTP_NOT_ACCEPTABLE);
        if ($cityValidator->isExistCity($cityIdForDelete)) {
            $cityServices->deleteCityServices($cityIdForDelete);
            Response::respondByDie(['Deleted'], Response::HTTP_OK);
        }
        Response::respondByDie(['Error' => "City Not Exsist!"], Response::HTTP_NOT_FOUND);

    default:
        Response::respondByDie(['Invalid method request!'], Response::HTTP_BAD_REQUEST);
}
