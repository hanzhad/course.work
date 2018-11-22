<?php
/**
 * Created by PhpStorm.
 * User: Flint
 * Date: 13.11.2018
 * Time: 18:35
 */

namespace Flint\Application\Controllers;


use Flint\Application\Functional\Database;
use Flint\Application\Models\ClaimModel;

class ClaimController
{

    /**
     *
     */
    public static function create()
    {
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


        $database = new Database();
        $db = $database->getConnection();

        $claim = new ClaimModel($db);

        // get posted data
        $data = json_decode(file_get_contents("php://input"));

        // make sure data is not empty
        if (
            !empty($data->location) &&
            !empty($data->num) &&
            !empty($data->files)
        ) {

            // set product property values
            $claim->location = $data->location;
            $claim->num = $data->num;
            $claim->created = date('Y-m-d H:i:s');

            // create the product
            if ($claim->create()) {

                // set response code - 201 created
                http_response_code(201);

                // tell the user
                echo json_encode(array("message" => "Product was created."));
            } // if unable to create the product, tell the user
            else {

                // set response code - 503 service unavailable
                http_response_code(503);

                // tell the user
                echo json_encode(array("message" => "Unable to create product."));
            }
        } // tell the user data is incomplete
        else {

            // set response code - 400 bad request
            http_response_code(400);

            // tell the user
            echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
        }
    }
}