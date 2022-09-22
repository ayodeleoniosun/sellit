<?php

namespace App\Providers\Repositories;

use App\Contracts\Repositories\Category\SubCategoryRepositoryInterface;
use App\Repositories\Category\SubCategoryRepository;
use Illuminate\Support\ServiceProvider;

class SubCategoryRepositoryProvider extends ServiceProvider
{
    /**
     * Register any application serivce.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SubCategoryRepositoryInterface::class,
            SubCategoryRepository::class
        );
    }

    /**
     * Bootstrap any application service.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
