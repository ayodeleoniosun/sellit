<?php

namespace App\Modules\Api\V1\Repositories;

interface AdsRepository
{
    public function index();

    public function myAds(array $data);

    public function post(array $data);

    public function addSortOptions(int $id, array $data);

    public function updateAds(int $id, array $data);

    public function details(int $id);

    public function uploadPictures(int $id, array $data);

    public function deleteSortOption(int $ads_id, int $sort_option_id, array $data);
}
