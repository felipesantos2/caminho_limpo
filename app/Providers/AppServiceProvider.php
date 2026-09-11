<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Head\Enums\OgType;
use Laravel\Head\Facades\Head;
use Laravel\Head\HeadBuilder;


use Illuminate\Routing\UrlGenerator;


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
    public function boot(UrlGenerator $url): void
    {
        if (env('APP_ENV') == 'production') { 
          $url->forceScheme('https');
        }

        Head::defaults(fn (HeadBuilder $head) => $head
            ->title('Nosso Caminho Limpo')
            ->description('Nosso caminho começa com você'))
            ->canonical()
            ->og(siteName: 'Nosso Caminho', type: OgType::Website)
            ->searchableByRobots()
            ->preconnect('https://localhost');
    }
}
