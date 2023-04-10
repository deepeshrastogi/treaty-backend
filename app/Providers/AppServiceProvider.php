<?php

namespace App\Providers;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Customers\OldDbCustomerRepository;
use App\Repositories\Interfaces\Customers\OldDbCustomerRepositoryInterface;
use App\Repositories\Interfaces\Orders\OldDbOrderRepositoryInterface;
use App\Repositories\Orders\OldDbOrderRepository;
use App\Repositories\Users\OldDbUserRepository;
use App\Repositories\Interfaces\Users\OldDbUserRepositoryInterface;
use App\Repositories\Interfaces\Locations\OldDbLocationRepositoryInterface;
use App\Repositories\Locations\OldDbLocationRepository;
use App\Repositories\Interfaces\Subsidiaries\SubsidiaryRepositoryInterface;
use App\Repositories\Subsidiaries\SubsidiaryRepository;
use App\Repositories\Interfaces\Files\FileRepositoryInterface;
use App\Repositories\Files\FileRepository;
use App\Repositories\Interfaces\Mdts\OldDbMdtRepositoryInterface;
use App\Repositories\Mdts\OldDbMdtRepository;
use App\Repositories\Interfaces\Orders\OrderTypeRepositoryInterface;
use App\Repositories\Orders\OrderTypeRepository;
use App\Repositories\Interfaces\Files\FileTypeRepositoryInterface;
use App\Repositories\Files\FileTypeRepository;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->registerOrderRepo();
        $this->registerCustomerRepo();
        $this->registerUserRepo();
        $this->registerLocationRepo();
        $this->registerSubsidiaryRepo();
        $this->registerFileRepo();
        $this->registerMdtRepo();
        $this->registerOrderTypeRepo();
        $this->registerFileTypeRepo();
    }

    public function registerOrderRepo() {
        $this->app->bind(OldDbOrderRepositoryInterface::class, OldDbOrderRepository::class);
    }

    public function registerCustomerRepo() {
        $this->app->bind(OldDbCustomerRepositoryInterface::class, OldDbCustomerRepository::class);
    }
    public function registerUserRepo() {
        $this->app->bind(OldDbUserRepositoryInterface::class, OldDbUserRepository::class);
    }


    public function registerLocationRepo() {
        $this->app->bind(OldDbLocationRepositoryInterface::class, OldDbLocationRepository::class);
    }

    public function registerSubsidiaryRepo() {
        $this->app->bind(SubsidiaryRepositoryInterface::class, SubsidiaryRepository::class);
    }

    public function registerFileRepo() {
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
    }

    public function registerMdtRepo() {
        $this->app->bind(OldDbMdtRepositoryInterface::class, OldDbMdtRepository::class);
    }

    public function registerOrderTypeRepo() {
        $this->app->bind(OrderTypeRepositoryInterface::class, OrderTypeRepository::class);
    }

    public function registerFileTypeRepo() {
        $this->app->bind(FileTypeRepositoryInterface::class, FileTypeRepository::class);
    }

    


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Schema::defaultStringLength(191);
    }
}
