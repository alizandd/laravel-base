<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserProfileResource;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiResponse;
    /**
     * @OA\Get(
     *     path="/user/profile",
     *     summary="Get User Profile",
     *     description="Retrieves the profile of the authenticated user.",
     *     operationId="getUserProfile",
     *     tags={"User"},
     *     security = { { "Authorization": {} } },
     *   @OA\Response(
     *     response=200,
     *     description="Successful retrieval of user profile",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="message", type="string", example=null),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="first_name", type="string", example=null),
     *         @OA\Property(property="last_name", type="string", example=null),
     *         @OA\Property(property="mobile", type="string", example="093****589"),
     *         @OA\Property(property="pic", type="string", example=null),
     *         @OA\Property(property="score", type="integer", example=0)
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="error"),
     *       @OA\Property(property="message", type="string", example="Unauthorized")
     *     )
     *   )
     * )
     */

    public function index(Request $request)
    {
        return $this->success(UserProfileResource::make(Auth::user()));
    }
}
