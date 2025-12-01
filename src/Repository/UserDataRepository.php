<?php

namespace App\Repository;

use App\Document\UserData;
use App\Enum\OrderBy;
use App\Enum\OrderDirection;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\ODM\MongoDB\MongoDBException;

/**
 * @extends ServiceDocumentRepository<UserData>
 */
class UserDataRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserData::class);
    }

    public function persist(UserData $userData): void
    {
        $this->dm->persist($userData);
    }

    /**
     * @throws MongoDBException
     * @throws \Throwable
     */
    public function flush(): void
    {
        $this->dm->flush();
    }

    /**
     * @return array<UserData>
     */
    public function findAllAndSortBy(OrderBy $column, OrderDirection $direction = OrderDirection::ASC): array
    {
        return $this->findBy([], [$column->value => $direction->value]);
    }
}