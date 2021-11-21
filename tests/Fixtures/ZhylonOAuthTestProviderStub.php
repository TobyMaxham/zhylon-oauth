<?php

namespace TobyMaxham\ZhylonOauth\Tests\Fixtures;

use Mockery as m;
use stdClass;
use TobyMaxham\ZhylonOauth\ZhylonProvider;

class ZhylonOAuthTestProviderStub extends ZhylonProvider
{
    /**
     * @var \GuzzleHttp\Client|\Mockery\MockInterface
     */
    public $http;

    protected function getTokenUrl()
    {
        if ('https://zhylon.de' !== self::$ZHYLON_INSTANCE) {
            return self::$ZHYLON_INSTANCE;
        }

        return 'http://token.url';
    }

    protected function getUserByToken($token)
    {
        return [
            'id' => 'foo',
            'name' => 'baz',
            'email' => 'foo@baz.de',
            'profile_photo_url' => null,
        ];
    }

    /**
     * Get a fresh instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client|\Mockery\MockInterface
     */
    protected function getHttpClient()
    {
        if ($this->http) {
            return $this->http;
        }

        return $this->http = m::mock(stdClass::class);
    }
}
