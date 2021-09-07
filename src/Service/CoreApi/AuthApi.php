<?php


namespace App\Service\CoreApi;

use App\Entity\Assistants;

final class AuthApi extends RequestCalls
{
    const LOGIN_PATH = '/auth/login';

    public function getAuthToken(Assistants $assistant)
    {
        $response = $this->doRequestWithoutToken('POST', self::LOGIN_PATH,
            ['username' => $assistant->getMobile(), 'password' => $assistant->getPlainPassword()]);

        return $response;
    }
}