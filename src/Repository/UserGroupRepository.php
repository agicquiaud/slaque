<?php
namespace App\Repository;

use App\Entity\Group;
use App\Entity\User;
use App\Entity\UserGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserGroup::class);
    }

    /**
     * @return Group[] returns a UserGroup to which the logged-in user belongs
     */
    public function findUserGroupByUser(User $user)
    {
        $qb = $this->createQueryBuilder('ug');
        $qb->andWhere("ug.user LIKE u");
        $qb->setParameter("u", $user);

        $qb->select("ug");
        $query = $qb->getQuery();
        $userGroup = $query->getresult();
        return [
            'UserGroups'=>$userGroup,
        ];
    }

    /**
     *
     * @param Group $group
     * @return array UserGroup
     */
    public function findUserGroupByGroup(Group $group)
    {
        $qb = $this->createQueryBuilder('ug');
        $qb->andWhere("ug.groupe LIKE g");
        $qb->setParameter("g", $group);

        $qb->select("ug");
        $query = $qb->getQuery();
        $userGroup = $query->getresult();
        return [
            'UserGroups'=>$userGroup,
        ];
    }
}