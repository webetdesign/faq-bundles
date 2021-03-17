<?php

namespace WebEtDesign\FaqBundle\Repository;

use WebEtDesign\FaqBundle\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findWithFaqs()
    {
        $qb = $this->createQueryBuilder('c');
        $qb->join('c.faqs', 'f')
            ->where('f.visible = :visible or f.visible is null')
            ->setParameter('visible', true)
            ->orderBy('c.position');

        return $qb->getQuery()->getResult();
    }

    public function findAllByPosition($order = 'ASC')
    {
        $qb = $this->createQueryBuilder('c');

        $qb->orderBy('c.position', $order);

        return $qb->getQuery()->getResult();
    }

    public function findOneBySlug(?string $slug)
    {
        if (!$slug) {
            return null;
        }
        $qb = $this->createQueryBuilder('c');

        $qb
            ->innerJoin('c.translations', 't')
            ->andWhere('t.slug = :slug')
            ->setParameter('slug', $slug);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
