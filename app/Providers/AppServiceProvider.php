<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductAttributeRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductAttributeRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
         $this->app->bind(
        ProductRepositoryInterface::class,
        ProductRepository::class);
         $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
         $this->app->bind(ProductAttributeRepositoryInterface::class, ProductAttributeRepository::class);
         $this->app->bind(StockTransactionInterface::class, StockTransactionRepository::class);
         $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
