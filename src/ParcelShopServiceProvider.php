<?php

namespace Signifly\ParcelShop;

use Zend\Soap\Client;
use Illuminate\Support\ServiceProvider;
use Signifly\ParcelShop\Contracts\ParcelShop;

class ParcelShopServiceProvider extends ServiceProvider
{
    /** @var string */
    protected $defaultEndpoint = 'http://www.gls.dk/webservices_v4/wsShopFinder.asmx?WSDL';

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/parcel-shop.php' => config_path('parcel-shop.php'),
            ], 'parcel-shop-config');
        }

        $this->mergeConfigFrom(__DIR__.'/../config/parcel-shop.php', 'parcel-shop');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ParcelShop::class, function () {
            return new GLSParcelShop(
                new Client(config('parcel-shop.endpoint') ?: $this->defaultEndpoint, [
                    'connection_timeout' => 60,
                    'keep_alive' => false,
                ])
            );
        });
    }
}
