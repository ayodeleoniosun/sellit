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

    public function signIn()
    {
        $body = $this->request->all();
        
        $validator = Validator::make(
            $body,
            [
                'email_address' => 'required|email|string',
                'password' => 'required|string',
            ],
            [
                'email_address.required' => 'Email address is required',
                'password.required' => 'Password is required',
            ]
        );

        if ($validator->fails()) {
            throw new CustomApiErrorResponseHandler($validator->errors()->first());
        }
        
        return response()->json(['status' => 'success', 'data' => $this->categoryRepository->signIn($body)], 200);
    }

    public function profile($id)
    {
        ApiUtility::auth_user($this->request);
        return response()->json(['status' => 'success', 'data' => $this->categoryRepository->profile($id)], 200);
    }

    public function updatePersonalInformation()
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();
        
        $validator = Validator::make(
            $body,
            [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'phone_number' => 'required|string|min:10|max:15',
                'state' => 'required|string',
                'city' => 'required|string'
            ],
            [
                'first_name.required' => 'Firstname is required',
                'last_name.required' => 'Lastname is required',
                'phone_number.required' => 'Phone number is required',
                'phone_number.min' => 'Phone number should be a minium of 10 characters',
                'phone_number.max' => 'phone number should be a maximum of 15 characters',
                'state.required' => 'State is required',
                'city.required' => 'City is required',
            ]
        );

        if ($validator->fails()) {
            throw new CustomApiErrorResponseHandler($validator->errors()->first());
        }
        
        return response()->json(['status' => 'success', 'data' => $this->categoryRepository->updatePersonalInformation($body)], 200);
    }

    public function updateBusinessInformation()
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();
        
        $validator = Validator::make(
            $body,
            [
                'business_name' => 'required|string',
                'business_slug' => 'required|string',
                'business_description' => 'required|string',
                'business_address' => 'required|string'
            ],
            [
                'business_name.required' => 'Business name is required',
                'business_slug.required' => 'Business custom slug is required',
                'business_description.required' => 'The description about your business is required',
                'business_address.required' => 'Your business address is required'
            ]
        );

        if ($validator->fails()) {
            throw new CustomApiErrorResponseHandler($validator->errors()->first());
        }
        
        return response()->json(['status' => 'success', 'data' => $this->categoryRepository->updateBusinessInformation($body)], 200);
    }

    public function changePassword()
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();
        
        $validator = Validator::make(
            $body,
            [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6',
                'new_password_confirmation' => 'required|string|min:6|same:new_password',
            ],
            [
                'current_password.required' => 'Current password is required',
                'new_password.required' => 'New password is required',
                'new_password_confirmation.required' => 'Retype the new password',
                'new_password.min' => 'New password should be a minimum of 6 characters',
                'phone_number.same' => 'New password must be the same with new password confirmation',
            ]
        );

        if ($validator->fails()) {
            throw new CustomApiErrorResponseHandler($validator->errors()->first());
        }

        return response()->json(['status' => 'success', 'data' => $this->categoryRepository->changePassword($body)], 200);
    }

    public function updateProfilePicture()
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();
        
        $validator = Validator::make(
            $body,
            [
                'picture' => 'required|mimes:jpg,png'
            ],
            [
                'picture.required' => 'No picture selected',
                'picture.mimes' => 'Only jpg and png files are allowed'
            ]
        );

        if ($validator->fails()) {
            throw new CustomApiErrorResponseHandler($validator->errors()->first());
        }
        
        return response()->json(['status' => 'success', 'data' => $this->categoryRepository->updateProfilePicture($body)], 200);
    }
}
