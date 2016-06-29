<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 29/06/2016
 * Time: 19:46
 */

namespace ContactBundle\Repository;


use ContactBundle\Entity\Groupe;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\User;

class MemberRequestRepository extends EntityRepository
{
    /**
     * @param $user   User
     * @param $groupe Groupe
     * @return mixed
     */
    public function getMemberRequest($user, $groupe) {
        $qb = $this->createQueryBuilder('request');

        $qb
            ->join('request.applicant', 'applicant')
            ->join('request.recipient', 'recipient')
            ->join('request.groupe', 'groupe')
            ->andWhere('applicant.id = ' . $user->getId() . ' or recipient.id = ' . $user->getId() . ' and groupe.id = ' . $groupe->getId())
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}