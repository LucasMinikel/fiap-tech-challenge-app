<?php

namespace TechChallenge\Adapters\Controllers\Customer;

use TechChallenge\Domain\Shared\AbstractFactory\Repository as AbstractFactoryRepository;
use TechChallenge\Application\DTO\Customer\DtoInput as CustomerDTOInput;
use TechChallenge\Application\UseCase\Customer\Store as UseCaseCustomerStore;
use TechChallenge\Application\UseCase\Customer\Cognito as UseCaseCustomerCognito;
use TechChallenge\Domain\Customer\ValueObjects\Cpf;
use TechChallenge\Infra\Utils\Http;

final class Store
{
    public function __construct(private readonly AbstractFactoryRepository $AbstractFactoryRepository) {}

    public function execute(CustomerDTOInput $dto, string $urlCognito): string
    {
        (new UseCaseCustomerCognito(new Http()))->execute(new Cpf($dto->cpf), $urlCognito);

        $customer = (new UseCaseCustomerStore($this->AbstractFactoryRepository->getDAO()->createCustomerDAO()))->execute($dto);

        $this->AbstractFactoryRepository->createCustomerRepository()->store($customer);

        return $customer->getId();
    }
}
