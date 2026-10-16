<?php

declare(strict_types=1);

namespace App\Providers;

use App\ConsultaBancoDeDados;
use App\Infrastructure\ConsultaBancoDeDadosLaravel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ConnectionInterface::class,
            static function (
                Application $aplicacao,
            ): ConnectionInterface {
                $gerenciador = $aplicacao->make(
                    DatabaseManager::class,
                );

                return $gerenciador->connection();
            },
        );

        $this->app->bind(
            ConsultaBancoDeDados::class,
            ConsultaBancoDeDadosLaravel::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
