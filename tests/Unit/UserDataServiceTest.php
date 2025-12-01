<?php

namespace App\Tests\Unit;

use App\Document\UserData;
use App\DTO\GetUserDataDTO;
use App\DTO\IpLocationDTO;
use App\DTO\UserDataDTO;
use App\DTO\UserDataResponseDTO;
use App\Enum\OrderBy;
use App\Enum\OrderDirection;
use App\Factory\UserDataMessageFactory;
use App\Message\UserDataInsertMessage;
use App\Repository\UserDataRepository;
use App\Service\UserDataService;
use App\ValueObject\PhoneNumber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;

class UserDataServiceTest extends TestCase
{
    private UserDataMessageFactory $factory;
    private MessageBusInterface $messageBus;
    private UserDataRepository $repository;
    private UserDataService $service;

    protected function setUp(): void
    {
        $this->factory = $this->createMock(UserDataMessageFactory::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->repository = $this->createMock(UserDataRepository::class);

        $this->service = new UserDataService($this->factory, $this->messageBus, $this->repository);
    }

    public function testAddUserDataDispatchesMessage(): void
    {
        $userDataDTO = $this->createMock(UserDataDTO::class);
        $locationDTO = $this->createMock(IpLocationDTO::class);

        $messageMock = new \App\Message\UserDataInsertMessage(
            'John',
            'Doe',
            ['+380501234567'],
            'USA',
            '8.8.8.8'
        );

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with($userDataDTO, $locationDTO)
            ->willReturn($messageMock);

        $this->messageBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($messageMock)
            ->willReturn(new \Symfony\Component\Messenger\Envelope($messageMock));

        $this->service->addUserData($userDataDTO, $locationDTO);
    }

    public function testGetUserDataReturnsResponseDTOs(): void
    {
        $phoneNumber = new PhoneNumber('+380501234567');
        $userDataEntity = $this->createMock(UserData::class);

        $userDataEntity->method('getFirstName')->willReturn('John');
        $userDataEntity->method('getLastName')->willReturn('Doe');
        $userDataEntity->method('getPhoneNumbers')->willReturn([$phoneNumber]);
        $userDataEntity->method('getIpAddress')->willReturn('8.8.8.8');
        $userDataEntity->method('getCountry')->willReturn('USA');

        $this->repository
            ->method('findAllAndSortBy')
            ->willReturn([$userDataEntity]);

        $getUserDataDTO = $this->createMock(GetUserDataDTO::class);
        $getUserDataDTO->method('getOrderBy')->willReturn(OrderBy::FIRST_NAME);
        $getUserDataDTO->method('getOrderDirection')->willReturn(OrderDirection::ASC);

        $result = $this->service->getUserData($getUserDataDTO);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(UserDataResponseDTO::class, $result[0]);
        $this->assertEquals('John', $result[0]->getFirstName());
        $this->assertEquals('Doe', $result[0]->getLastName());
        $this->assertEquals(['+380501234567'], $result[0]->getPhoneNumbers());
        $this->assertEquals('8.8.8.8', $result[0]->getIpAddress());
        $this->assertEquals('USA', $result[0]->getCountry());
    }
}