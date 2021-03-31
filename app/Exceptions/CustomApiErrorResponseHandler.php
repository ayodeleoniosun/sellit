<?php

namespace App\Exceptions;

use App\Modules\Api\V1\Controllers\Controller;
use Exception;

class CustomApiErrorResponseHandler extends Exception
{
    protected $message;
    protected $statusCode;
    protected $redirectUrl;
    
    public function __construct($message, $statusCode = 400, $redirectUrl = null)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->redirectUrl = $redirectUrl;
    }

    public function render()
    {
        return response()->json(
            [
                'status' => 'error',
                'message' => $this->message
            ],
            $this->statusCode,
        );
    }
}

?>

