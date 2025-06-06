<?php

namespace App\Repository;

use App\Entity\Tarif;
use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Tarif>
 */
class TarifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tarif::class);
    }

    public function findOneByQuantite(Produit $produit, int $quantite): ?Tarif
    {
        return $this->createQueryBuilder('t')
            ->where('t.produit = :produit')
            ->andWhere('t.seuilQuantite <= :quantite')
            ->setParameter('produit', $produit)
            ->setParameter('quantite', $quantite)
            ->orderBy('t.seuilQuantite', 'DESC') // seuil classé du plus élevé au moins élevé
            ->setMaxResults(1) // on récupère uniquement la première ligne donc le seuil le plus élevé
            ->getQuery()
            ->getOneOrNullResult();
    }


    //    /**
    //     * @return Tarif[] Returns an array of Tarif objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Tarif
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
