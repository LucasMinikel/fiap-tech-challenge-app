<?php

namespace TechChallenge\Adapters\Controllers\Customer;

use TechChallenge\Domain\Shared\AbstractFactory\Repository as AbstractFactoryRepository;
use TechChallenge\Application\DTO\Customer\DtoInput as CustomerDTOInput;
use TechChallenge\Application\UseCase\Customer\Update as UseCaseCustomerUpdate;

final class Update
{
    public function __construct(private readonly AbstractFactoryRepository $AbstractFactoryRepository) {}

    public function execute(CustomerDTOInput $dto): void
    {
        $customer = (new UseCaseCustomerUpdate(
            $this->AbstractFactoryRepository->getDAO()->createCustomerDAO()
        ))
            ->execute($dto);

        $this->AbstractFactoryRepository->createCustomerRepository()->update($customer);
    }
}
