<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * Method to return active players and with money to bet
     * @return Player[] Returns an array of Player objects
     */
    public function findActivePlayers()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.enabled = 1')
            ->andWhere('p.amount > 0')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param $player
     * @return string
     * @throws \Doctrine\DBAL\Exception
     */
    public function updateAmount($player)
    {
        try {
            $sql = "UPDATE player SET amount = (amount + :amountBet) WHERE id = :playerId";
            $sql = str_replace([':amountBet', ':playerId'], [$player['amount'], $player['id']], $sql);
//            dump($sql);
//            die();
            $em = $this->getEntityManager();
            $query = $em->getConnection()->prepare($sql);
            $query->executeQuery();
        } catch (\Exception $exception) {
            dump($exception);
            die();
            return false;
        }
        return true;
    }
    /*
    public function findOneBySomeField($value): ?Player
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
