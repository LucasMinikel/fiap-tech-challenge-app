<?php

namespace TechChallenge\Infra\Utils;

use TechChallenge\Domain\Shared\Interfaces\Http as IHttp;
use Illuminate\Support\Facades\Http as FacadeHttp;
use TechChallenge\Domain\Shared\Exceptions\HttpException;

class Http implements IHttp
{
    public function post(string $url, array $body): void
    {
        $response = FacadeHttp::post($url, $body);

        if (!$response->successful())
            throw new HttpException("Houve um erro!", $response->status);
    }
}
