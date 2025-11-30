<?php

namespace App\MessageHandler;

use App\Factory\UserDataDocumentFactory;
use App\Message\UserDataInsertMessage;
use App\Repository\UserDataRepository;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Throwable;

#[AsMessageHandler]
class UserDataInsertHandler
{

    public function __construct(
        private readonly UserDataDocumentFactory $userDataFactory,
        private readonly UserDataRepository      $userDataRepository,
    )
    {
    }

    /**
     * @throws MongoDBException
     * @throws Throwable
     */
    public function __invoke(UserDataInsertMessage $userDataInsertMessage): void
    {

        $userDataDocument = $this->userDataFactory->create(
            $userDataInsertMessage->getFirstName(),
            $userDataInsertMessage->getLastName(),
            $userDataInsertMessage->getPhoneNumbers(),
            $userDataInsertMessage->getIpAddress(),
            $userDataInsertMessage->getCountry()
        );

        $this->userDataRepository->getDocumentManager()->persist($userDataDocument);
        $this->userDataRepository->getDocumentManager()->flush();
    }
}