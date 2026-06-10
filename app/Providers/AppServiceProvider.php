<?php

namespace App\Providers;

use App\Models\Siswa;
use App\Observers\SiswaObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        require_once app_path('helpers.php');
    }

    public function boot(): void
    {
        Model::preventLazyLoading(! $this->app->isProduction());

        Siswa::observe(SiswaObserver::class);
    }
}
