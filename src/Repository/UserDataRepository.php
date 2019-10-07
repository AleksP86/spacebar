<?php

namespace App\Repository;

use App\Entity\UserData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserData|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserData|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserData[]    findAll()
 * @method UserData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserDataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserData::class);
    }

    public function CheckUser($v1, $v2)
    {
        return $this->createQueryBuilder('u')
                ->andWhere('u.name = :v1')
                ->andWhere('u.Password = :v2')
                ->setParameter('v1', $v1)
                ->setParameter('v2', $v2)
                ->getQuery()
                ->getResult();
    }

    public function CheckUserName($v1)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT id FROM user_data WHERE name='".$v1."'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function AddUser($v1, $v2, $v3)
    {
        if($v3=='')
        {
            $v3=null;
        }
        $conn = $this->getEntityManager()->getConnection();
        $sql = "INSERT INTO user_data (name, password, alias) VALUES('".$v1."','".$v2."','".$v3."')";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT id from user_data WHERE name='".$v1."'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function ProfileData($id)
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "
            SELECT  name, alias, sign_date, avatar
            FROM    user_data
            WHERE   id=".$id;

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // /**
    //  * @return UserData[] Returns an array of UserData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserData
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
