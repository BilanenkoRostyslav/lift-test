<?php

namespace App\Tests\Unit;

use App\Document\UserData;
use App\Factory\UserDataDocumentFactory;
use App\Message\UserDataInsertMessage;
use App\MessageHandler\UserDataInsertHandler;
use App\Repository\UserDataRepository;
use App\ValueObject\PhoneNumber;
use Doctrine\ODM\MongoDB\DocumentManager;
use PHPUnit\Framework\TestCase;

class UserDataInsertHandlerTest extends TestCase
{
    private UserDataDocumentFactory $factory;
    private UserDataRepository $repository;
    private DocumentManager $documentManager;
    private UserDataInsertHandler $handler;

    protected function setUp(): void
    {
        $this->factory = $this->createMock(UserDataDocumentFactory::class);
        $this->repository = $this->createMock(UserDataRepository::class);
        $this->documentManager = $this->createMock(DocumentManager::class);

        $this->repository
            ->method('getDocumentManager')
            ->willReturn($this->documentManager);

        $this->handler = new UserDataInsertHandler($this->factory, $this->repository);
    }

    public function testInvokePersistsAndFlushesDocument(): void
    {
        $message = new UserDataInsertMessage(
            'John',
            'Doe',
            ['+380501234567'],
            'Ukraine',
            '8.8.8.8'
        );

        $userDataDocument = new UserData()
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setPhoneNumbers([new PhoneNumber('+380501234567')])
            ->setCountry('Ukraine')
            ->setIpAddress('8.8.8.8');

        $this->factory
            ->expects($this->once())
            ->method('create')
            ->with(
                'John',
                'Doe',
                ['+380501234567'],
                '8.8.8.8',
                'Ukraine'
            )
            ->willReturn($userDataDocument);

        $this->documentManager
            ->expects($this->once())
            ->method('persist')
            ->with($userDataDocument);

        $this->documentManager
            ->expects($this->once())
            ->method('flush');

        $this->handler->__invoke($message);
    }

}