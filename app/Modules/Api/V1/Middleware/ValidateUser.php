<?php

namespace App\Modules\Api\V1\Middleware;

use App\Modules\Api\ApiUtility;
use App\Modules\Api\V1\Models\ActiveStatus;
use App\Modules\Api\V1\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ValidateUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = ApiUtility::decode_bearer_token($request);
        
        if ($token) {
            $now = Carbon::now()->toDateTimeString();
            $user = User::where(
                [
                    'bearer_token' => $token,
                    'active_status' => ActiveStatus::ACTIVE
                ]
            )->whereDate('token_expires_at', '>=', $now)->first();
            
            if (!$user) {
                return $this->unAuthorizedReponse();
            }

            $request->merge(['auth_user' => $user]);
        } else {
            return $this->unAuthorizedReponse();
        }

        return $next($request);
    }

    private function unAuthorizedReponse()
    {
        return response()->json(
            ['status' => 'error', 'message' => 'Unauthorized access'],
            401,
            [
                'Access-Control-Allow-Origin' => '*',
                'Content-type' => 'application/json; charset=utf-8',
                'X-Powered-By' => 'Sellit'
            ]
        );
    }
}
