<?php

namespace App\Providers\V1;

use App\Modules\Api\V1\Repositories\AdminRepository;
use App\Modules\Api\V1\Services\AdminService;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            AdminRepository::class,
            AdminService::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
