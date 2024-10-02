<?php

namespace TechChallenge\Application\UseCase\Customer;

use TechChallenge\Domain\Customer\ValueObjects\Cpf;
use TechChallenge\Domain\Shared\Interfaces\Http;

final class Cognito
{
    public function __construct(private readonly Http $http) {}

    public function execute(Cpf $cpf, string $urlCognito): void
    {
        $this->http->post($urlCognito, ["cpf" => (string) $cpf]);
    }
}
