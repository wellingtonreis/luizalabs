<?php

namespace App\Providers;

use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UnitOfWork;
use App\Src\Repositories\AccountRepositoryInterface;
use App\Src\Repositories\TransactionRepositoryInterface;
use App\Src\Repositories\UnitOfWorkInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(UnitOfWorkInterface::class, UnitOfWork::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
