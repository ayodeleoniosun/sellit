<?php

namespace App\Modules\Api\V1\Controllers;

use App\Exceptions\CustomApiErrorResponseHandler;
use App\Modules\Api\ApiUtility;
use App\Modules\Api\V1\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    protected $categoryRepository;
    protected $request;

    public function __construct(Request $request, CategoryRepository $categoryRepository)
    {
        $this->request = $request;
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $response = $this->categoryRepository->index();
    
        return response()->json(
            [
                'status' => 'success',
                'data' => $response
            ],
            200
        );
    }

    public function addCategory()
    {
        $body = $this->request->all();
        $this->validateCategory($body);
        
        $response = $this->categoryRepository->addCategory($body);
    
        return response()->json(
            [
                'status' => 'success',
                'data' => $response['category'],
                'message' => $response['message']
            ],
            200
        );
    }

    public function validateCategory($body)
    {
        $validator = Validator::make(
            $body,
            [
                'name' => 'required|string',
            ],
            [
                'name.required' => 'Category name is required'
            ]
        );

        if ($validator->fails()) {
            throw new CustomApiErrorResponseHandler($validator->errors()->first());
        }
    }

    public function updateCategory($id)
    {
        $body = $this->request->all();
        $this->validateCategory($body);
        
        $response = $this->categoryRepository->updateCategory($id, $body);
    
        return response()->json(
            [
                'status' => 'success',
                'data' => $response['category'],
                'message' => $response['message']
            ],
            200
        );
    }

    public function categoryDetails($id)
    {
        $response = $this->categoryRepository->categoryDetails($id);
        
        return response()->json(
            [
                'status' => 'success',
                'data' => $response
            ],
            200
        );
    }

    public function validateSubCategory($body)
    {
        $validator = Validator::make(
            $body,
            [
                'category_id' => 'required|string',
                'name' => 'required|string',
                'sort_options' => 'required|array'
            ],
            [
                'category_id.required' => 'Category is required',
                'name.required' => 'Category name is required',
                'sort_options.required' => 'The options for sorting ads in this sub category is required'
            ]
        );

        if ($validator->fails()) {
            throw new CustomApiErrorResponseHandler($validator->errors()->first());
        }
    }

    public function addSubCategory()
    {
        $body = $this->request->all();
        $this->validateSubCategory($body);
        
        $response = $this->categoryRepository->addSubCategory($body);
    
        return response()->json(
            [
                'status' => 'success',
                'sub_category' => $response['sub_category'],
                'sort_options' => $response['sort_options'],
                'message' => $response['message']
            ],
            200
        );
    }

    public function updateSubCategory($sub_id)
    {
        $body = $this->request->all();
        $this->validateSubCategory($body);
        
        $response = $this->categoryRepository->updateSubCategory($sub_id, $body);
    
        return response()->json(
            [
                'status' => 'success',
                'sub_category' => $response['sub_category'],
                'message' => $response['message']
            ],
            200
        );
    }

    public function subCategories($id)
    {
        $response = $this->categoryRepository->subCategories($id);
    
        return response()->json(
            [
                'status' => 'success',
                'data' => $response
            ],
            200
        );
    }

    public function subCategoryDetails($id, $sub_id)
    {
        $response = $this->categoryRepository->subCategoryDetails($id, $sub_id);
    
        return response()->json(
            [
                'status' => 'success',
                'data' => $response
            ],
            200
        );
    }
}
