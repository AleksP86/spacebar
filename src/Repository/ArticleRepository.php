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

    public function FirstPageArticles()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT *
            FROM article a";
        /*$sql = "
            SELECT count(id) as 'stories', a.*
            FROM article a";*/
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function CountArticles()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT count(id) as 'tot'
            FROM article a";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function GetArticles($fid, $lid, $perPage, $dir)
    {
        if($dir==null)
        {
            //first load
            $conn = $this->getEntityManager()->getConnection();
            $sql = "SELECT *
                FROM article a LIMIT ".$perPage;
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        elseif($dir=='inc')
        {
            //next page set
            $conn = $this->getEntityManager()->getConnection();
            $sql = "SELECT *
                FROM article a
                WHERE id>".$lid."
                LIMIT ".$perPage;
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        elseif($dir=="desc")
        {
            //prevoius page set
            $conn = $this->getEntityManager()->getConnection();
            $sql = "SELECT *
                FROM article a
                WHERE id<".$fid."
                ORDER BY id DESC
                LIMIT ".$perPage;
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }
}
