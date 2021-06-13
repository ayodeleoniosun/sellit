<?php

namespace App\Modules\Api\V1\Controllers;

use App\Exceptions\CustomApiErrorResponseHandler;
use App\Modules\Api\ApiUtility;
use App\Modules\Api\V1\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    protected $adminRepository;
    protected $request;

    public function __construct(Request $request, AdminRepository $adminRepository)
    {
        $this->request = $request;
        $this->adminRepository = $adminRepository;
    }

    public function overview()
    {
        $response = $this->adminRepository->overview();
        return response()->json(['status' => 'success', 'data' => $response], 200);
    }
}
