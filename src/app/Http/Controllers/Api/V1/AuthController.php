<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OtpRequest;
use App\Http\Requests\V1\RegisterRequest;
use App\Http\Resources\V1\GenerateOtpResource;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\Contracts\TokenInterface;
use App\Services\Implementations\TokenService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\Contracts\NotifierInterface;
use App\Services\Contracts\OtpInterface;
use Illuminate\Http\Response;

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
     *     summary="Register a user",
     *     description="Registers a new user with mobile, code, key, mac, and device type.",
     *     operationId="registerUser",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User registration data",
     *         @OA\JsonContent(
     *             required={"mobile", "code", "key", "mac", "device_type"},
     *             @OA\Property(property="mobile", type="string", format="mobile", example="09354541589"),
     *             @OA\Property(property="code", type="integer", example=123456),
     *             @OA\Property(property="key", type="string", example="4CAaZCJ3IZ1sWgai1pdzsBFMOyXFLVyU"),
     *             @OA\Property(property="mac", type="string", example="00:1B:44:11:3A:B7"),
     *             @OA\Property(property="device_type", type="string", example="Tizen")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User registered successfully."),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="mobile", type="string", example="1234567890"),
     *
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Invalid registration details provided"),
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
        $token = $this->tokenService->createToken($user , $request->device_type);
        return $this->success($token);
    }


}
