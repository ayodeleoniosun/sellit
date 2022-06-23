<?php

namespace App\Http\Controllers;

use App\ApiUtility;
use App\Exceptions\CustomApiErrorResponseHandler;
use App\Repositories\AdsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function response;

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
        $response = $this->adsRepository->index($this->request->only([
            'search',
            'category',
            'sub_category',
            'seller',
            'filter',
            'order',
            'minimum_price',
            'maximum_price'
        ]));
        return response()->json(['status' => 'success', 'data' => $response], 200);
    }

    public function myAds()
    {
        ApiUtility::auth_user($this->request);
        $response = $this->adsRepository->myAds($this->request);
        return response()->json(['status' => 'success', 'data' => $response], 200);
    }

    public function post()
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();
        $this->validateAds($body);

        $response = $this->adsRepository->post($body);

        return response()->json([
            'status' => 'success',
            'data' => $response['ads'],
            'message' => $response['message']
        ], 200);
    }

    public function searchMyAds()
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();
        $validator = Validator::make(
            $body,
            [
                'ads' => 'required|string',
                'sub_category_id' => 'nullable|string'
            ],
            [
                'ads.required' => 'Please enter the name of the ads you are searching for',
                'sub_category_id.string' => 'Please select the category you want to search from'
            ]
        );

        if ($validator->fails()) {
            throw new CustomApiErrorResponseHandler($validator->errors()->first());
        }

        $response = $this->adsRepository->searchMyAds($body);

        return response()->json([
            'status' => 'success',
            'data' => $response['ads'],
            'message' => $response['message']
        ], 200);
    }

    public function update(int $id)
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();
        $this->validateAds($body);

        $response = $this->adsRepository->update($id, $body);

        return response()->json([
            'status' => 'success',
            'data' => $response['ads'],
            'message' => $response['message']
        ], 200);
    }

    public function postReviews(int $id)
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();

        $validator = Validator::make(
            $body,
            [
                'rating' => 'required|numeric|min:1|max:5',
                'comment' => 'required|string'
            ],
            [
                'rating.required' => 'The rating field is required',
                'rating.min' => 'Rating cannot be less than 1',
                'rating.max' => 'Rating cannot be greater than 5',
                'comment.required' => 'The comment field is required'
            ]
        );

        if ($validator->fails()) {
            throw new CustomApiErrorResponseHandler($validator->errors()->first());
        }

        $response = $this->adsRepository->postReviews($id, $body);

        return response()->json([
            'status' => 'success',
            'data' => $response['reviews'],
            'message' => $response['message']
        ], 200);
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

        return response()->json([
            'status' => 'success',
            'added_sort_options' => $response['added_sort_options'],
            'message' => $response['message']
        ], 200);
    }

    public function validateAds($body)
    {
        $validator = Validator::make(
            $body,
            [
                'category_id' => 'required',
                'sub_category_id' => 'required',
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|string'
            ],
            [
                'category_id.required' => 'Category is required',
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

    public function updateAds(int $id)
    {
        $body = $this->request->all();
        $this->validateAds($body);

        $response = $this->adsRepository->updateAds($id, $body);

        return response()->json([
            'status' => 'success',
            'data' => $response['ads'],
            'message' => $response['message']
        ], 200);
    }

    public function view(string $slug)
    {
        $response = $this->adsRepository->view($slug);
        return response()->json(['status' => 'success', 'data' => $response], 200);
    }

    public function uploadPictures()
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();

        $validator = Validator::make(
            $body,
            [
                'pictures' => 'required|array'
            ],
            [
                'pictures.required' => 'Select at least one picture',
                'pictures.array' => 'Selected pictures must be an array'
            ]
        );

        if ($validator->fails()) {
            throw new CustomApiErrorResponseHandler($validator->errors()->first());
        }

        $response = $this->adsRepository->uploadPictures($body);

        return response()->json(['status' => 'success', 'message' => $response], 200);
    }

    public function delete(int $ads_id)
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();
        $response = $this->adsRepository->delete($ads_id, $body);

        return response()->json(['status' => 'success', 'message' => $response], 200);
    }

    public function deletePicture(int $ads_id, int $picture_id)
    {
        ApiUtility::auth_user($this->request);
        $body = $this->request->all();
        $response = $this->adsRepository->deletePicture($ads_id, $picture_id, $body);

        return response()->json(['status' => 'success', 'message' => $response], 200);
    }

    public function deleteSortOption(int $ads_id, int $sort_option_id)
    {
        ApiUtility::auth_user($this->request);
        $response = $this->adsRepository->deleteSortOption($ads_id, $sort_option_id, $this->request->all());

        return response()->json(['status' => 'success', 'message' => $response], 200);
    }
}
