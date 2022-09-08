<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data, string $message = '', int $statusCode = 200) {
            return Response::json([
                'status' => 'success',
                'message' => $message,
                'data' => $data,
            ], $statusCode);
        });

        Response::macro('error', function (string $message, int $statusCode = 400) {
            return Response::json([
                'status' => 'error',
                'message' => $message,
            ], $statusCode);
        });
    }
}
