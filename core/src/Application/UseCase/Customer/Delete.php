<?php

namespace TechChallenge\Application\UseCase\Customer;

use TechChallenge\Domain\Customer\DAO\ICustomer as ICustomerDAO;
use TechChallenge\Domain\Customer\Repository\ICustomer as ICustomerRepository;
use TechChallenge\Domain\Customer\Exceptions\CustomerNotFoundException;
use TechChallenge\Domain\Customer\Entities\Customer;

final class Delete
{
    public function __construct(private readonly ICustomerRepository $CustomerRepository, private readonly ICustomerDAO $CustomerDAO) {}

    public function execute(string $id): Customer
    {
        if (!$this->CustomerDAO->exist(["id" => $id]))
            throw new CustomerNotFoundException();

        $customer = $this->CustomerRepository->show(["id" => $id], true);

        $customer->delete();

        return $customer;
    }
}
