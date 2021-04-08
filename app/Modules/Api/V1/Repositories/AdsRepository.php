<?php

namespace App\Modules\Api\V1\Repositories;

interface AdsRepository
{
    public function index();

    public function myAds(array $data);

    public function post(array $data);

    public function addSortOptions(int $id, array $data);

    public function updateAds(int $id, array $data);

    public function adsDetails(int $id);
}
