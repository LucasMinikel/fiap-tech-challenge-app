<?php

namespace TechChallenge\Adapters\Controllers\Customer;

use TechChallenge\Domain\Shared\AbstractFactory\Repository as AbstractFactoryRepository;
use TechChallenge\Application\UseCase\Customer\Show as UseCaseCustomerShow;
use TechChallenge\Adapters\Presenters\Customer\ToArray as PresenterCustomerToArray;

final class Show
{
    public function __construct(private readonly AbstractFactoryRepository $AbstractFactoryRepository) {}

    public function execute(string $id)
    {
        $customer = (new UseCaseCustomerShow(
            $this->AbstractFactoryRepository->createCustomerRepository(),
            $this->AbstractFactoryRepository->getDAO()->createCustomerDAO()
        ))
            ->execute($id);

        return (new PresenterCustomerToArray())->execute($customer);
    }
}
