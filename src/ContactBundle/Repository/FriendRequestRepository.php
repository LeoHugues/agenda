<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 29/06/2016
 * Time: 18:43
 */

namespace ContactBundle\Repository;


use Doctrine\ORM\EntityRepository;

class FriendRequestRepository extends EntityRepository
{
    public function getFriendRequest($applicant, $friend) {
        $qb = $this->createQueryBuilder('request');

        $qb
            ->join('request.applicant', 'applicant')
            ->join('request.recipient', 'recipient')
            ->andWhere('applicant.id = ' . $applicant->getId() . ' and recipient.id = ' . $friend->getId())
            ->orWhere('applicant.id = ' . $friend->getId() . ' and recipient.id = ' . $applicant->getId())
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}