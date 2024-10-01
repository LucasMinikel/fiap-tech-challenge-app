<?php

namespace TechChallenge\Application\UseCase\Customer;

use TechChallenge\Domain\Customer\DAO\ICustomer as ICustomerDAO;
use TechChallenge\Domain\Customer\Exceptions\CustomerAlreadyRegistered;
use TechChallenge\Domain\Customer\SimpleFactory\Customer as FactorySimpleCustomer;
use TechChallenge\Application\DTO\Customer\DtoInput;
use TechChallenge\Domain\Customer\ValueObjects\Cpf;
use TechChallenge\Domain\Customer\Entities\Customer;

final class Store
{
    public function __construct(private readonly ICustomerDAO $CustomerDAO) {}

    public function execute(DtoInput $data): Customer
    {
        if ($this->CustomerDAO->exist(
            [
                "cpf" => (string) (new Cpf($data->cpf))
            ]
        ))
            throw new CustomerAlreadyRegistered();

        return (new FactorySimpleCustomer())
            ->new()
            ->withNameCpfEmail($data->name, $data->cpf, $data->email)
            ->build();
    }
}
