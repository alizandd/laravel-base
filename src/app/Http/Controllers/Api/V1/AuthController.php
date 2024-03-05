<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OtpRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Http\Requests\V1\ScopedTokenRequest;
use App\Http\Resources\V1\GenerateOtpResource;
use App\Models\Device;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\Contracts\TokenInterface;
use App\Services\Implementations\TokenService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\Contracts\NotifierInterface;
use App\Services\Contracts\OtpInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;
    protected $otpService;
    protected $notifier;
    protected $tokenService;

    public function __construct(OtpInterface $otpService, NotifierInterface $notifier,TokenInterface $tokenService) {
        $this->otpService = $otpService;
        $this->notifier = $notifier;
        $this->tokenService = $tokenService;
    }
    /**
     * @OA\Post(
     *     path="/generate-otp",
     *     summary="Generate OTP",
     *     description="Generates an OTP and sends it to the user's mobile number",
     *     operationId="generateOtp",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Mobile number of the user",
     *         @OA\JsonContent(
     *             required={"mobile"},
     *             @OA\Property(property="mobile", type="string", format="mobile", example="09354541589"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP generated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example=null),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="key", type="string", example="4CAaZCJ3IZ1sWgai1pdzsBFMOyXFLVyU"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Invalid mobile number provided"),
     *         )
     *     )
     * )
     */
    public function generateOtp(OtpRequest $request) {

        $otp = $this->otpService->generate($request->mobile);
       // $this->notifier->send($request->mobile, __('messages.hamsam.code', [ 'code' => $otp->code ]));

        return $this->success(GenerateOtpResource::make($otp));
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register user",
     *     description="Registers a new user and returns an access token along with token type and expiry.",
     *     operationId="registerUser",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Payload for registering a new user",
     *         @OA\JsonContent(
     *             required={"code", "key", "mobile", "mac", "device_type"},
     *             @OA\Property(property="code", type="integer", example=841679),
     *             @OA\Property(property="key", type="string", example="4CAaZCJ3IZ1sWgai1pdzsBFMOyXFLVyU"),
     *             @OA\Property(property="mobile", type="string", example="09354541589"),
     *             @OA\Property(property="mac", type="string", example="00:1B:44:11:3A:B7"),
     *             @OA\Property(property="device_type", type="string", example="Tizen")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example=null),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
     *                 @OA\Property(property="token_type", type="string", example="Bearer"),
     *                 @OA\Property(property="expires_at", type="string", example="2025-03-03 13:45:44")
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - Invalid or expired code",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="کد وارد شده نامعتبر یا منقضی شده است .")
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        if (!$this->otpService->validate($request->mobile, $request->code, $request->key)) {
            return $this->error(__('auth.otp_invalid'),403);
        }

        $user = User::updateOrCreate([ 'mobile' => $request->mobile ], [ 'mac' => $request->mac]);
        Device::updateOrCreate(['user_id' => $user->id, 'mac' => $request->mac], ['device_type' => $request->device_type]);

        $token = $this->tokenService->createToken($user , $request->device_type);
        return $this->success($token);
    }

    /**
     * @OA\Post(
     *   path="/generate-scoped-token",
     *   summary="Generate Scoped Access Token For Web",
     *   operationId="generateScopedToken",
     *   tags={"Authentication"},
     *   security = { { "Authorization": {} } },
     *   @OA\RequestBody(
     *     required=true,
     *     description="Payload for generating a scoped access token",
     *     @OA\JsonContent(
     *       required={"scope", "device_type"},
     *       @OA\Property(property="scope", type="string", description="Scope of the access token", example="Web"),
     *       @OA\Property(property="device_type", type="string", description="Type of the device requesting the token", example="Tizen")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Scoped access token generated successfully",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="message", type="string", example=null),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
     *         @OA\Property(property="token_type", type="string", example="Bearer"),
     *         @OA\Property(property="expires_at", type="string", example="2025-03-03 13:45:44")
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Forbidden - Invalid or expired input",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="status", type="string", example="error"),
     *       @OA\Property(property="message", type="string", example="Unauthenticated .")
     *     )
     *   )
     * )
     */
    public function generateScopedToken(ScopedTokenRequest $request)
    {

        $token = $this->tokenService->createScopeToken(Auth::user() , $request->device_type ,$request->scope);
        return $this->success($token);
    }


}
