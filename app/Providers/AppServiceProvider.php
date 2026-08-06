<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Head\Facades\Head;
use Laravel\Head\HeadBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        // Head::defaults(fn (HeadBuilder $head) => $head
        //     ->title('Laravel', suffix: ' - Laravel')
        //     ->description('Build something great.'));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Head::defaults(fn (HeadBuilder $head) => $head
            ->title('Nosso Caminho Limpo')
            ->description('Nosso caminho começa com você'));
    }
}
