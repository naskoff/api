<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Exceptions\BadRequestException;
use App\Repository\UserRepository;
use App\Responses\UserResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/users', name: 'users.')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    )
    {
    }

    #[Route(name: 'index', methods: [Request::METHOD_GET])]
    public function index(UserRepository $repository): Response
    {
        return $this->json(UserResponse::fromCollection($repository->findAll()));
    }

    #[Route(name: 'create', methods: [Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $data = $request->toArray();

        $validator = $this->validator->validate($data, new Collection(
            fields: [
                'email' => [
                    new NotBlank(),
                    new Email(),
                ],
                'password' => [
                    new NotBlank(),
                    new Length(min: 2, max: 120),
                ]
            ]
        ));

        if (0 < count($validator)) {
            $exception = new BadRequestException($validator);

            return $this->json($exception->toArray(), Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $data['password']));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json(UserResponse::fromEntity($user), Response::HTTP_CREATED);
    }
}
