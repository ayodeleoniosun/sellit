<?php

namespace App\Repositories;

interface CategoryRepository
{
    public function index();

    public function addCategory(array $data);

    public function updateCategory(int $id, array $data);

    public function categoryDetails(int $id);

    public function addSubCategory(array $data);

    public function addSubCategorySortOptions(int $id, array $data);

    public function updateSubCategory(int $sub_id, array $data);

    public function subCategories(int $id);

    public function subCategoryDetails(int $id, int $sub_id);
}
