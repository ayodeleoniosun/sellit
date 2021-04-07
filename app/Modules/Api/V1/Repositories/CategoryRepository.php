<?php

namespace App\Modules\Api\V1\Repositories;

interface CategoryRepository
{
    public function index();

    public function categoryDetails(int $id);

    public function subCategories(int $id);

    public function subCategoryDetails(int $id, int $sub_id);
}
