<?php

namespace TechChallenge\Adapters\Controllers\Customer;

use TechChallenge\Domain\Shared\AbstractFactory\Repository as AbstractFactoryRepository;
use TechChallenge\Application\UseCase\Customer\Delete as UseCaseCustomerDelete;

final class Delete
{
    public function __construct(private readonly AbstractFactoryRepository $AbstractFactoryRepository) {}

    public function execute(string $id): void
    {
        $CustomerRepository = $this->AbstractFactoryRepository->createCustomerRepository();

        $customer = (new UseCaseCustomerDelete(
            $CustomerRepository,
            $this->AbstractFactoryRepository->getDAO()->createCustomerDAO()
        ))
            ->execute($id);

        $CustomerRepository->delete($customer);
    }
}
