<?php

namespace TobyMaxham\ZhylonOauth;

use Laravel\Socialite\Two\User;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;

class ZhylonProvider extends AbstractProvider implements ProviderInterface
{
    public static $ZHYLON_INSTANCE = 'https://zhylon.de';

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(self::$ZHYLON_INSTANCE . '/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return self::$ZHYLON_INSTANCE . '/oauth/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(self::$ZHYLON_INSTANCE . '/api/v2/userinfo', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['name'],
            'name' => $user['name'],
            'email' => $user['email'],
            'avatar' => $user['profile_photo_url'],
        ]);
    }
}
