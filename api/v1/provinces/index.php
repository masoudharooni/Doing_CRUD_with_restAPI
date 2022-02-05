<?php

use App\Services\ProvinceServices;
use App\Services\TokenGenerator;
use App\Utilities\Authorization;
use App\Utilities\Response;
use App\Utilities\Caching;
use App\Utilities\UserUtility;
use App\Validator\Validator;

include "../../../authoload.php";

$jwtToken = Authorization::getBearerToken();
$tokenDecoder = new TokenGenerator;
$tokenDecoded = $tokenDecoder->decode($jwtToken);
if (is_null($tokenDecoded))
    Response::respondByDie(['Invalid Token!'], Response::HTTP_UNAUTHORIZED);

if (!UserUtility::isExistUserById($tokenDecoded->id))
    Response::respondByDie(["User's Token is not valid!"], Response::HTTP_UNAUTHORIZED);

# user authorizaed
$requestMethod = $_SERVER['REQUEST_METHOD'];
$provinceServices = new ProvinceServices;
$provinceValidator = new Validator;
$getBodyRequestData = json_decode(file_get_contents('php://input'), true);
switch ($requestMethod) {
    case 'GET':
        if (Caching::isExistCacheFile())
            Response::setHeaders(Response::HTTP_OK);
        Caching::start();
        $requestGetData = [
            'provinceID' => $_GET['id'] ?? null,
            'pageSize' => $_GET['page_size'] ?? null,
            'page' => $_GET['page'] ?? null,
            'fields' => $_GET['fields'] ?? '*',
            'order' => $_GET['order'] ?? 'ASC'
        ];
        $dataGetValidator = [
            "provinceID" => (is_null($requestGetData['provinceID']) || is_numeric($requestGetData['provinceID'])),
            "pageSize" => (is_null($requestGetData['pageSize']) || is_numeric($requestGetData['pageSize'])),
            "page" => (is_null($requestGetData['page']) || is_numeric($requestGetData['page'])),
            "fields" => (is_null($requestGetData['fields']) || is_string($requestGetData['fields']))
        ];
        if (!$dataGetValidator['provinceID'])
            Response::respondByDie(["Error" => "Province ID parameter should be interger!"], Response::HTTP_NOT_ACCEPTABLE);
        if (!is_null($requestGetData['provinceID']))
            if (!$provinceValidator->isExistProvince($requestGetData['provinceID']))
                Response::respondByDie(["Error" => "This province not exsit!"], Response::HTTP_NOT_FOUND);


        if (!$dataGetValidator['pageSize'])
            Response::respondByDie(["Error" => "Page size parameter should be an interger!"], Response::HTTP_NOT_ACCEPTABLE);

        if (!$dataGetValidator['page'])
            Response::respondByDie(["Error" => "Page parameter should be an interger!"], Response::HTTP_NOT_ACCEPTABLE);
        if (!in_array($requestGetData['fields'], ["id", "name", "*"]))
            Response::respondByDie(["Error" => "Field parameter is not valid!"], Response::HTTP_NOT_ACCEPTABLE);

        if (!in_array($requestGetData['order'], ['asc', 'desc', 'ASC', 'DESC']))
            Response::respondByDie(["Error" => "Order parameter is not valid!"], Response::HTTP_NOT_ACCEPTABLE);

        $respondData = $provinceServices->getProvinceServices($requestGetData['provinceID'], $requestGetData['page'], $requestGetData['pageSize'], $requestGetData['fields'], $requestGetData['order']);
        echo Response::respond($respondData, Response::HTTP_OK);
        Caching::end();
        exit;

    case 'POST':
        if (!$provinceValidator->isValidProvinceForAdd($getBodyRequestData))
            Response::respondByDie(["Error" => "Your data for this province is not valid!"], Response::HTTP_NOT_ACCEPTABLE);
        $lastProvinceId = $provinceServices->addProvinceServices($getBodyRequestData);
        $response = $provinceServices->getProvinceServices($lastProvinceId);
        Response::respondByDie($response, Response::HTTP_OK);

    case 'PUT':
        if (!$provinceValidator->isValidProvinceForUpdate($getBodyRequestData))
            Response::respondByDie(["Error" => "Your data for this province is not valid!"], Response::HTTP_NOT_ACCEPTABLE);
        $status = $provinceServices->updateProvinceServices($getBodyRequestData);
        if (!$status)
            Response::respondByDie(["Error" => "Province did not update, try again!"], Response::HTTP_NOT_MODIFIED);
        $response = $provinceServices->getProvinceServices($getBodyRequestData['id']);
        Response::respondByDie($response, Response::HTTP_OK);

    case 'DELETE':
        if (sizeof($getBodyRequestData) != 1 || !is_int($getBodyRequestData['id']))
            Response::respondByDie(["Error" => "Your data for this province is not valid!"], Response::HTTP_NOT_ACCEPTABLE);
        if (!$provinceValidator->isExistProvince($getBodyRequestData['id']))
            Response::respondByDie(["Error" => "This province has already been deleted!"], Response::HTTP_NOT_ACCEPTABLE);

        $status = $provinceServices->deleteProvinceServices($getBodyRequestData['id']);
        if (!$status)
            Response::respondByDie(["Error" => "Province did not delete, try again!"], Response::HTTP_NOT_ACCEPTABLE);
        Response::respondByDie(["Province deleted!"], Response::HTTP_OK);
    default:
        echo "Request Method is not valid!";
}
