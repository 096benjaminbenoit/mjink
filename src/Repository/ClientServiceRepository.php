<?php

namespace App\Repository;

use App\Entity\ClientService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ClientService>
 *
 * @method ClientService|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientService|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientService[]    findAll()
 * @method ClientService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientService::class);
    }

    public function save(ClientService $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ClientService $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getServiceDurationFromClient($client, $service)
    {
        $queryBuilder = $this->createQueryBuilder('cs');
        $queryBuilder
            ->select('cs.duration')
            ->where('cs.service = :service_id')
            ->andWhere('cs.client = :client_id')
            ->setParameter('service_id', $service)
            ->setParameter('client_id', $client);

        $result = $queryBuilder->getQuery()->getOneOrNullResult();

        if ($result !== null) {
            return $result['duration'];
        } else {
            return null;
        }
    }
}
