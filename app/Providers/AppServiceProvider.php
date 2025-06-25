<?php

namespace App\Providers;

use App\Repositories\SupplierRepository;
use Illuminate\Support\ServiceProvider;

// Repositories & Interfaces
use App\Repositories\UserRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductAttributeRepository;
use App\Repositories\StockTransactionRepository;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductAttributeRepositoryInterface;
use App\Repositories\Interfaces\StockTransactionRepositoryInterface;
use App\Repositories\Interfaces\SupplierRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(ProductAttributeRepositoryInterface::class, ProductAttributeRepository::class);
        $this->app->bind(StockTransactionRepositoryInterface::class,  StockTransactionRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
