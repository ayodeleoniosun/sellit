<?php

namespace App\Modules\Api\V1\Controllers;

use App\Exceptions\CustomApiErrorResponseHandler;
use App\Modules\Api\ApiUtility;
use App\Modules\Api\V1\Repositories\AdsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdsController extends Controller
{
    protected $adsRepository;
    protected $request;

    public function __construct(Request $request, AdsRepository $adsRepository)
    {
        $this->request = $request;
        $this->adsRepository = $adsRepository;
    }

    public function index()
    {
        $response = $this->adsRepository->index();
    
        return response()->json(
            [
                'status' => 'success',
                'data' => $response
            ],
            200
        );
    }

    public function post()
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();
        $this->validateAds($body);
        
        $response = $this->adsRepository->post($body);
    
        return response()->json(
            [
                'status' => 'success',
                'data' => $response['ads'],
                'message' => $response['message']
            ],
            200
        );
    }

    public function addSortOptions(int $id)
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();
        
        $validator = Validator::make(
            $body,
            [
                'sort_options' => 'required|array'
            ],
            [
                'sort_options.required' => 'The options for sorting ads is required',
                'sort_options.array' => 'The options for sorting ads must be an array'
            ]
        );

        if ($validator->fails()) {
            throw new CustomApiErrorResponseHandler($validator->errors()->first());
        }
        $response = $this->adsRepository->addSortOptions($id, $body);
    
        return response()->json(
            [
                'status' => 'success',
                'added_sort_options' => $response['added_sort_options'],
                'message' => $response['message']
            ],
            200
        );
    }

    public function validateAds($body)
    {
        $validator = Validator::make(
            $body,
            [
                'category_id' => 'required|string',
                'sub_category_id' => 'required|string',
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|string'
            ],
            [
                'category.required' => 'Category is required',
                'sub_category_id.required' => 'Sub category is required',
                'name.required' => 'Ads name is required',
                'description.required' => 'Ads description is required',
                'price.required' => 'Price is required'
            ]
        );

        if ($validator->fails()) {
            throw new CustomApiErrorResponseHandler($validator->errors()->first());
        }
    }

    public function updateAds($id)
    {
        $body = $this->request->all();
        $this->validateAds($body);
        
        $response = $this->adsRepository->updateAds($id, $body);
    
        return response()->json(
            [
                'status' => 'success',
                'data' => $response['ads'],
                'message' => $response['message']
            ],
            200
        );
    }

    public function adsDetails($id)
    {
        $response = $this->adsRepository->adsDetails($id);
        
        return response()->json(
            [
                'status' => 'success',
                'data' => $response
            ],
            200
        );
    }
}
