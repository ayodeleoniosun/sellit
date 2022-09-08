<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CustomException extends Exception
{
    protected $message;

    protected int $statusCode;

    public function __construct(string $message, int $statusCode = 400)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    public function render(): JsonResponse
    {
        return response()->error($this->message, $this->statusCode);
    }
}
