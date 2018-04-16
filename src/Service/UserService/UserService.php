<?php

namespace App\Service\UserService;


use App\Entity\User;
use App\Service\GenerateToken;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $passwordEncoder;


    private $entityManager;

    private $paginator;

    /**
     * UserService constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GenerateToken $generateToken
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    /**
     * @param User $user
     */
    public function setDefaultValues(User $user): void
    {
        $this->setEncodePassword($user);
        $user->setToken($this->generateToken->generate($user->getEmail()));
        $user->setIsActive(0);
    }

    public function setEncodePassword(User $user): void
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
    }

    /**
     * @param User $user
     * @return void
     */
    public function commit(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    /**
     * @param User $user
     * @return void
     */
    public function setUserActive(User $user): void
    {
        $user->setIsActive(1);

        $user->setToken('');

        $user->setRoles(['ROLE_USER']);
    }

    /**
     * @param User $user
     * @return void
     */
    public function changeIsActive(User $user): void
    {
        if ($user->getIsActive() == '0') {
            $user->setIsActive('1');
        } else {
            $user->setIsActive('0');
        }
    }

    /**
     * @param int $usersOnPage
     * @param Request $request
     * @return PaginatorInterface
     */
    public function createPaginator(int $usersOnPage, Request $request)
    {
        $this->paginator = $this->paginator->paginate(
            $this->findAllQuery(),
            $request->query->getInt('page', 1),
            $usersOnPage
        );

        return $this->paginator;
    }

    /**
     * @param User $user
     * @return void
     */
    public function delete(User $user): void
    {
        //TODO should create custom exception
        /*if (!$question) {
            throw $this->createNotFoundException('Данный вопрос не найден!');
        }*/

        $this->entityManager->remove($user);
    }

    /**
     * @param $id
     * @return User
     */
    public function find($id): User
    {
        $user = $this->entityManager
            ->getRepository(User::class)
            ->find($id);


        //TODO create custom exceptions or throw it up
        /*if (!$user) {
            throw $this->createNotFoundException('Данный пользователь не найден!');
        }

        if (in_array('ROLE_ADMIN',$user->getRoles())) {
            throw $this->createAccessDeniedException('Вы не можете менять данные администратора!');
        }*/

        return $user;
    }

    /**
     * @return Query
     */
    public function findAllQuery(): Query
    {
        $dql = "SELECT u FROM App\Entity\User u";

        $query = $this->entityManager->createQuery($dql);

        return $query;
    }

    /**
     * @return PaginatorInterface
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * @param PaginatorInterface $paginator
     * @return void
     */
    public function setPaginator(PaginatorInterface $paginator): void
    {
        $this->paginator = $paginator;
    }


}