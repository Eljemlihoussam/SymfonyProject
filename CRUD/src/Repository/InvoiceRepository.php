<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 *
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    public function save(Invoice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Invoice $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Invoice[] Returns an array of Invoice objects
     */
    public function findByClient(Client $client): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.client = :client')
            ->setParameter('client', $client)
            ->orderBy('i.invoiceDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneByIdAndClient(int $id, Client $client): ?Invoice
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.id = :id')
            ->andWhere('i.client = :client')
            ->setParameter('id', $id)
            ->setParameter('client', $client)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Invoice[] Returns an array of Invoice objects
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('i')
            ->join('i.client', 'c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('i.invoiceDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
} 