<?php

namespace TechChallenge\Application\UseCase\Customer;

use TechChallenge\Domain\Customer\DAO\ICustomer as ICustomerDAO;
use TechChallenge\Domain\Customer\Exceptions\CustomerAlreadyRegistered;
use TechChallenge\Domain\Customer\Exceptions\CustomerNotFoundException;
use TechChallenge\Domain\Customer\SimpleFactory\Customer as SimpleFactoryCustomer;
use TechChallenge\Application\DTO\Customer\DtoInput;
use TechChallenge\Domain\Customer\ValueObjects\Cpf;
use DateTime;
use TechChallenge\Domain\Customer\Entities\Customer;

final class Update
{
    public function __construct(private readonly ICustomerDAO $CustomerDAO) {}

    public function execute(DtoInput $data): Customer
    {
        if (!$this->CustomerDAO->exist(["id" => $data->id]))
            throw new CustomerNotFoundException();

        $cpf = new Cpf($data->cpf);

        if ($this->CustomerDAO->exist(
            [
                "not-id" => $data->id,
                "cpf" => (string) $cpf
            ]
        ))
            throw new CustomerAlreadyRegistered();

        $customer = (new SimpleFactoryCustomer())
            ->new($data->id, $data->createdAt, $data->updatedAt)
            ->withNameCpfEmail($data->name, $data->cpf, $data->email)
            ->build();

        $customer->setUpdatedAt(new DateTime());

        return $customer;
    }
}
