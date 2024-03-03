<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
 * @OA\Info(
 *   title="HAMSAM",
 *
 *    description="
 * 	  1) call login method and get access_token add token_type in this format : {token_type}+{access_token}
 * 	  2) use them as authorization keys",
 *   version="1",
 *   contact={
 *      "name"="alizand",
 *     "email": "alizandd@gmail.com"
 *   },
 * )
 * @OA\Get(
 *   path="/api/resource.json",
 *   @OA\Response(response="200", description="An example resource")
 * )
 * @OA\Server(
 *   url="{Schema}://{Host}/{Version}",
 *   description="DEVELOP",
 *   @OA\ServerVariable(
 *     serverVariable="Schema",
 *     enum={"https", "http"},
 *     default="http"
 *   ),
 *   @OA\ServerVariable(
 *     serverVariable="Version",
 *     enum={"v1", "v2"},
 *     default="v1"
 *   ),
 *   @OA\ServerVariable(
 *     serverVariable="Host",
 *     enum={"localhost/backend/src/public/api","ssn.tvapps.ir/api","127.0.0.1:8000/api"},
 *     default="localhost/backend/src/public/api"
 *   ),
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
