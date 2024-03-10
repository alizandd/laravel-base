<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UpdateProfileRequest;
use App\Http\Requests\V1\UploadAvatarRequest;
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

    /**
     * @OA\Patch(
     *   path="/user/profile",
     *   summary="Update User Information",
     *   operationId="updateUserInfo",
     *   tags={"User"},
     *   security = { { "Authorization": {} } },
     *   @OA\RequestBody(
     *     required=true,
     *     description="Data for updating user information",
     *     @OA\JsonContent(
     *       required={"first_name", "last_name", "username"},
     *       @OA\Property(property="first_name", type="string", example="Ali"),
     *       @OA\Property(property="last_name", type="string", example="Zand"),
     *       @OA\Property(property="username", type="string", example="alizandd"),
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="User updated successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="message", type="string", example="اطلاعات شما با موفقیت به روز رسانی شد ."),
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthorized - Invalid or expired token",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="error"),
     *       @OA\Property(property="message", type="string", example="Unauthenticated"),
     *     )
     *   )
     * )
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
       Auth::user()->update(['first_name'=>$request->first_name,'last_name'=>$request->last_name,'username'=>$request->username]);
       return $this->success([],__('messages.update_success'));
    }

    /**
     * @OA\Patch(
     *   path="/user/profile/avatar",
     *   summary="Update User Avatar",
     *   operationId="updateUserAvatar",
     *   tags={"User"},
     *   security = { { "Authorization": {} } },
     *   @OA\RequestBody(
     *     required=true,
     *     description="Upload user avatar",
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"avatar"},
     *         type="object",
     *         @OA\Property(
     *           property="avatar",
     *           description="The user avatar file",
     *           type="string",
     *           format="binary",
     *         ),
     *       ),
     *     ),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Avatar updated successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="message", type="string", example="اطلاعات شما با موفقیت به روز رسانی شد ."),
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="error"),
     *       @OA\Property(property="message", type="string", example="Unauthenticated"),
     *     )
     *   )
     * )
     */
    public function updateAvatar(UploadAvatarRequest $request)
    {
        $file = $request->file('avatar');
        $path = $file->store('avatars', 'public'); // Stores in storage/app/public/avatars
        Auth::user()->update(['pic'=>$path]);
        return $this->success([],__('messages.update_success'));
    }

}
