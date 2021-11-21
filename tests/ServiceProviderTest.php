<?php

namespace TobyMaxham\ZhylonOauth\Tests;

use Orchestra\Testbench\TestCase;
use Laravel\Socialite\Contracts\Factory;
use TobyMaxham\ZhylonOauth\ZhylonProvider;
use Laravel\Socialite\SocialiteServiceProvider;
use TobyMaxham\ZhylonOauth\ZhylonOauthServiceProvider;

class ServiceProviderTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('services.zhylon', [
            'client_id' => 'zhylon-client-id',
            'client_secret' => 'zhylon-client-secret',
            'redirect' => 'http://your-callback-url',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            ZhylonOAuthServiceProvider::class,
            SocialiteServiceProvider::class,
        ];
    }

    public function test_it_can_instantiate_the_zhylon_driver()
    {
        $factory = $this->app->make(Factory::class);

        $provider = $factory->driver('zhylon');
        $this->assertInstanceOf(ZhylonProvider::class, $provider);
    }
}
