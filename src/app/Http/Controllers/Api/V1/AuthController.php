<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/code",
     *     tags={"Auth"},
     *     summary="getCode",
     *     description="get code",
     *   @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="application/x-www-form-urlencoded",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="mobile",
     *                   description="mobile",
     *                   type="string",
     *               ),
     *               @OA\Property(
     *                   property="mac",
     *                   description="mac",
     *                   type="string",
     *               ),
     *               required={"mobile","mac_lan","mac","unique_id"}
     *           )
     *       )
     *   ),
     *
     *   @OA\Response(response="200",description="ok", @OA\JsonContent()),
     *   @OA\Response(response="405",description="Invalid input", @OA\JsonContent()),
     *   @OA\Response(response="403",description="Unauthorize", @OA\JsonContent()),
     * )
     * @param GetCodeRequest $request
     * @return CodeR
     */

}
