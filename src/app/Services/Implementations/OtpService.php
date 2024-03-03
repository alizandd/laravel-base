<?php
namespace App\Services\Implementations;
use App\Models\OtpCode;
use App\Services\Contracts\OtpInterface;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OtpService implements OtpInterface
{
    public function generateOtp($mobileNumber)
    {

    }

    public function generate(string $identifier): object
    {
        // TODO: Implement generate() method.
        $code = rand(100000, 999999); // Generate a 6-digit OTP
        $key = Str::random(32);
        $expiryTime = Carbon::now()->addMinutes(5); // OTP expires in 5 minutes
        $otpCode = OtpCode::updateOrCreate([ 'mobile' => $identifier ], [ 'code' => $code, 'key' => $key, 'expire_at' => $expiryTime, 'status' => 1 ]);
        tap($otpCode)->increment('count');
        return $otpCode;
    }

    public function validate(string $identifier, string $otp ,string $key): bool
    {
        // TODO: Implement validate() method.
        $otpRecord = OtpCode::where('mobile', $identifier)
            ->where('code', $otp)
            ->where('key', $key)
            ->where('expire_at', '>', Carbon::now())
            ->first();

        if ($otpRecord) {
            $otpRecord->delete(); // Prevent OTP reuse
            return true;
        }
        return false;
    }
}
