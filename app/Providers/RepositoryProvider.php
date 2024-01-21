<?php

namespace App\Providers;

use App\Interfaces\AuthInterface;
use App\Interfaces\ReservationInterface;
use App\Interfaces\ReservationStatusInterface;
use App\Interfaces\TableModelInterface;
use App\Interfaces\WalkInInterface;
use App\Repositories\AuthRepository;
use App\Repositories\ReservationRepository;
use App\Repositories\ReservationStatusRepository;
use App\Repositories\TableModelRepository;
use App\Repositories\WalkInRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthInterface::class, AuthRepository::class);
        $this->app->bind(TableModelInterface::class, TableModelRepository::class);
        $this->app->bind(ReservationStatusInterface::class, ReservationStatusRepository::class);
        $this->app->bind(ReservationInterface::class, ReservationRepository::class);
        $this->app->bind(WalkInInterface::class, WalkInRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
