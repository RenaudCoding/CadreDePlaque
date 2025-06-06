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

    // récupération des tarifs d'un produit particulier
    public function findTarifsByProduit(Produit $produit): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.produit = :produit')
            ->setParameter('produit', $produit)
            ->orderBy('t.seuilQuantite', 'DESC') // tarifs classés suivant le seuil de quantité du plus élevé au moins élevé
            ->getQuery()
            ->getResult();
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
