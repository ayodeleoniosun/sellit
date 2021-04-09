<?php

namespace App\Modules\Api;

use App\Exceptions\CustomApiErrorResponseHandler;
use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class ApiUtility
{
    public static function validate_email($email)
    {
        $emailCheck = preg_match("^[a-z\'\"0-9 +.]+([._-][+[a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$^", $email);
        
        if (!$emailCheck) {
            return false;
        }

        return true;
    }

    public static function generate_bearer_token()
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }

    public static function decode_bearer_token($request)
    {
        $header = $request->header('Authorization', '');
        
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }

        return null;
    }

    public static function auth_user($request)
    {
        $now = Carbon::now()->toDateTimeString();
        $user = $request->auth_user;

        $user = User::where(
            [
                'id' => $user->id,
                'active_status' => ActiveStatus::ACTIVE
            ]
        )->whereDate('token_expires_at', '>=', $now)->first();
        
        if (!$user) {
            throw new CustomApiErrorResponseHandler("Unauthorized access.");
        }

        return $user;
    }

    public static function mail_subject_by_environment()
    {
        $environment = App::environment();

        if ($environment == 'production') {
            return '';
        }

        return '[' . strtoupper($environment) . '] ';
    }

    public static function next_one_month()
    {
        return Carbon::now()->addMonths(1)->toDateTimeString();
    }

    public static function generateTimeStamp()
    {
        $currentTime = Carbon::now();
        $date = $currentTime->toArray();
        $timeStamp = $date['year']."_".date("m")."_".date("d")."_".$date['micro'];
        return $timeStamp;
    }
}
