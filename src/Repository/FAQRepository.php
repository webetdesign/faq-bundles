<?php

namespace WebEtDesign\FaqBundle\Repository;

use WebEtDesign\FaqBundle\Entity\Faq;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Faq|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faq|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faq[]    findAll()
 * @method Faq[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faq::class);
    }

    public function findOrderedByPosition()
    {
        $qb = $this->createQueryBuilder('f');
        $qb->where('f.visible = :visible or f.visible is null')
            ->setParameter('visible', true);
        $qb->orderBy('f.position', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function findOneBySlug(string $slug)
    {
        $qb = $this->createQueryBuilder('f');

        $qb
            ->innerJoin('f.translations', 't')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', $slug);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
