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
        $cityID = $_GET['city_id'] ?? null;
        $page = $_GET['page'] ?? null;
        $pageSize = $_GET['page_size'] ?? null;
        if (is_numeric($cityID) || is_null($cityID)) {
            if ((is_numeric($page) || is_null($page)) && $page > 0) {
                if (is_numeric($pageSize) || is_null($pageSize)) {
                    if ((is_null($pageSize) == is_null($page)) || (is_numeric($page) == is_numeric($pageSize))) {
                        if (!is_null($cityID)) {
                            if ($cityValidator->isExistCity($cityID)) {
                                $responseData = $cityServices->getCityServices($cityID, $page, $pageSize);
                                Response::respondByDie($responseData, Response::HTTP_OK);
                            } else {
                                Response::respondByDie(['Error' => 'This city is not exsist!'], Response::HTTP_NOT_FOUND);
                            }
                        } else {
                            $responseData = $cityServices->getCityServices($cityID, $page, $pageSize);
                            Response::respondByDie($responseData, Response::HTTP_OK);
                        }
                    } else {
                        Response::respondByDie(["Error" => "Page and page_size should fill together!"], Response::HTTP_NOT_ACCEPTABLE);
                    }
                } else {
                    Response::respondByDie(["Error" => "Page_size parameter should be an integer!"], Response::HTTP_NOT_ACCEPTABLE);
                }
            } else {
                Response::respondByDie(["Error" => "Page parameter should be an integer and greather than 0"], Response::HTTP_NOT_ACCEPTABLE);
            }
        } else {
            Response::respondByDie(["Error" => "City Id should be an integer!"], Response::HTTP_NOT_ACCEPTABLE);
        }

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
