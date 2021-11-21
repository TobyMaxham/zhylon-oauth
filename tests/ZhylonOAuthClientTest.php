<?php

namespace TobyMaxham\ZhylonOauth\Tests;

use stdClass;
use Mockery as m;
use Illuminate\Http\Request;
use Laravel\Socialite\Two\User;
use Orchestra\Testbench\TestCase;
use Illuminate\Contracts\Session\Session;
use Laravel\Socialite\Two\InvalidStateException;
use TobyMaxham\ZhylonOauth\Tests\Fixtures\ZhylonOAuthTestProviderStub;

class ZhylonOAuthClientTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        m::close();
    }

    /** @test */
    public function it_returns_a_user_instance_for_the_auth_request()
    {
        $request = Request::create('foo', 'GET', ['state' => str_repeat('A', 40), 'code' => 'code']);
        $request->setLaravelSession($session = m::mock(Session::class));
        $session->expects('pull')->with('state')->andReturns(str_repeat('A', 40));
        $provider = new ZhylonOAuthTestProviderStub($request, 'client_id', 'client_secret', 'redirect_uri');
        $provider->http = m::mock(stdClass::class);
        $provider->http->expects('post')->with('http://token.url', [
            'headers' => ['Accept' => 'application/json'], 'form_params' => ['grant_type' => 'authorization_code', 'client_id' => 'client_id', 'client_secret' => 'client_secret', 'code' => 'code', 'redirect_uri' => 'redirect_uri'],
        ])->andReturns($response = m::mock(stdClass::class));
        $response->expects('getBody')->andReturns('{ "access_token" : "access_token", "refresh_token" : "refresh_token", "expires_in" : 3600 }');
        $user = $provider->user();

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('foo', $user->id);
        $this->assertSame('access_token', $user->token);
        $this->assertSame('refresh_token', $user->refreshToken);
        $this->assertSame(3600, $user->expiresIn);
        $this->assertSame($user->id, $provider->user()->id);
    }


    /** @test */
    public function it_can_change_the_zhylon_instance()
    {
        $before = ZhylonOAuthTestProviderStub::$ZHYLON_INSTANCE;
        ZhylonOAuthTestProviderStub::$ZHYLON_INSTANCE = 'http://token-baz.url';

        $request = Request::create('foo', 'GET', ['state' => str_repeat('A', 40), 'code' => 'code']);
        $request->setLaravelSession($session = m::mock(Session::class));
        $session->expects('pull')->with('state')->andReturns(str_repeat('A', 40));
        $provider = new ZhylonOAuthTestProviderStub($request, 'client_id', 'client_secret', 'redirect_uri');
        $provider->http = m::mock(stdClass::class);
        $provider->http->expects('post')->with('http://token-baz.url', [
            'headers' => ['Accept' => 'application/json'], 'form_params' => ['grant_type' => 'authorization_code', 'client_id' => 'client_id', 'client_secret' => 'client_secret', 'code' => 'code', 'redirect_uri' => 'redirect_uri'],
        ])->andReturns($response = m::mock(stdClass::class));
        $response->expects('getBody')->andReturns('{ "access_token" : "access_token", "refresh_token" : "refresh_token", "expires_in" : 3600 }');
        $user = $provider->user();

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('foo', $user->id);
        $this->assertSame('access_token', $user->token);
        $this->assertSame('refresh_token', $user->refreshToken);
        $this->assertSame(3600, $user->expiresIn);
        $this->assertSame($user->id, $provider->user()->id);

        ZhylonOAuthTestProviderStub::$ZHYLON_INSTANCE = $before;
    }


    /** @test */
    public function it_throws_an_invalid_state_exception()
    {
        $this->expectException(InvalidStateException::class);

        $request = Request::create('foo', 'GET', ['state' => str_repeat('B', 40), 'code' => 'code']);
        $request->setLaravelSession($session = m::mock(Session::class));
        $session->expects('pull')->with('state')->andReturns(str_repeat('A', 40));
        $provider = new ZhylonOAuthTestProviderStub($request, 'client_id', 'client_secret', 'redirect');
        $provider->user();
    }

    /** @test */
    public function it_throws_an_exception_when_state_is_not_set()
    {
        $this->expectException(InvalidStateException::class);

        $request = Request::create('foo', 'GET', ['state' => 'state', 'code' => 'code']);
        $request->setLaravelSession($session = m::mock(Session::class));
        $session->expects('pull')->with('state');
        $provider = new ZhylonOAuthTestProviderStub($request, 'client_id', 'client_secret', 'redirect');
        $provider->user();
    }
}
