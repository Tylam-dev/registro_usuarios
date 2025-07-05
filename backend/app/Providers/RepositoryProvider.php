<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\EloquentUsuarioRepository;
use App\Services\Interfaces\IUsuarioRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            IUsuarioRepository::class,
            EloquentUsuarioRepository::class
        );
    }
}