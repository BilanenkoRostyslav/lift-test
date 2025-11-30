<?php

namespace App\Service;

use App\Document\UserData;
use App\DTO\GetUserDataDTO;
use App\DTO\UserDataDTO;
use App\DTO\IpLocationDTO;
use App\DTO\UserDataResponseDTO;
use App\Factory\UserDataMessageFactory;
use App\Repository\UserDataRepository;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UserDataService
{
    public function __construct(
        private readonly UserDataMessageFactory $userDataMessageFactory,
        private readonly MessageBusInterface    $messageBus,
        private readonly UserDataRepository     $userDataRepository,
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function addUserData(UserDataDTO $userDataDTO, IpLocationDTO $locationAndIp): void
    {
        $message = $this->userDataMessageFactory->create($userDataDTO, $locationAndIp);
        $this->messageBus->dispatch($message);
    }

    /**
     * @return array<UserDataResponseDTO>
     */
    public function getUserData(GetUserDataDTO $getUserDataDTO): array
    {
        $orderBy = $getUserDataDTO->getOrderBy();
        $orderDirection = $getUserDataDTO->getOrderDirection();

        $userData = $this->userDataRepository->findAllAndSortBy($orderBy, $orderDirection);

        return array_map(function (UserData $userData) {
            return new UserDataResponseDTO(
                $userData->getFirstName(),
                $userData->getLastName(),
                $userData->getPhoneNumbers(),
                $userData->getIpAddress(),
                $userData->getCountry(),
            );
        }, $userData);
    }
}