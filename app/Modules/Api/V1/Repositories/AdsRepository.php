<?php

namespace App\Modules\Api\V1\Repositories;

interface AdsRepository
{
    public function index(array $request);

    public function myAds(object $data);

    public function post(array $data);
    
    public function update(int $id, array $data);

    public function postReviews(int $id, array $data);

    public function addSortOptions(int $id, array $data);

    public function view(string $slug);

    public function uploadPictures(array $data);

    public function deletePicture(int $ads_id, int $picture_id, array $data);

    public function delete(int $ads_id, array $data);

    public function deleteSortOption(int $ads_id, int $sort_option_id, array $data);
}
