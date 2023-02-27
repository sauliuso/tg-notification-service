<?php

namespace App\Repository;

use App\Entity\Notification;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 *
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function save(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Notification[]
    */
   public function findLastByUserAsArray(int $userId, int $limit = 100): array
   {
        return array_map(
            function (Notification $notification) {
                return [
                    'id' => $notification->getId(),
                    'userId' => $notification->getUserId(),
                    'body' => $notification->getPayload()['body'] ?? '',
                    'createdAt' => $notification->getCreatedAt()?->format(DateTime::ATOM),
                    'status' => $notification->getStatus(),
                    'statusMessage' => $notification->getStatusMessage(),
                    'channel' => $notification->getChannel(),
                    'provider' => $notification->getProvider(),
                    'providerResponse' => $notification->getProviderResponse(),
                    'sentAt' => (string) $notification->getSentAt()?->format(DateTime::ATOM),
                ];
            },
            $this->findBy(['userId' => $userId], ['createdAt' => 'DESC'], $limit)
        );
   }
}
