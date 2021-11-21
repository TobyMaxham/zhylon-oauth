<?php

namespace TobyMaxham\ZhylonOauth;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class ZhylonOAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'zhylon',
            function ($app) use ($socialite) {
                throw_if(null == $app['config']['services.zhylon'], 'Zhylon config is missing: `config.services.zhylon`!');
                $config = $app['config']['services.zhylon'];

                if (null !== ($zhylonInstance = Arr::get($config, 'instance'))) {
                    ZhylonProvider::$ZHYLON_INSTANCE = $zhylonInstance;
                }

                return $socialite->buildProvider(ZhylonProvider::class, $config);
            }
        );
    }
}
