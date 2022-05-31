<?php

namespace App\Providers;

use App\Models\MainCategory;
use App\Observers\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       //Category::observe(MainCategory::class);
        Schema::defaultStringLength(191);
    }
}
