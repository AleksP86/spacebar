<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getAllStories()
    {
        /*
        return $this->createQueryBuilder('a')
                    ->orderBy('a.added_at','DESC')
                    ->getQuery()
                    ->getResult();
        */

        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT a.*, u.name as 'user_name', u.alias as 'user_alias'
            FROM article a, user_data u
            WHERE a.author = u.id
            LIMIT 10";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserStories($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT count(id) as 'stories'
            FROM article a
            WHERE a.author = ".$id;

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }
}
