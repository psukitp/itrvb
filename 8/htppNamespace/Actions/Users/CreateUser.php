<?php

namespace htppNamespace\Actions\Users;

use htppNamespace\Actions\ActionInterface;
use htppNamespace\ErrorResponse;
use htppNamespace\Request;
use htppNamespace\Response;
use htppNamespace\SuccessfullResponse;
use Psr\Log\LoggerInterface;
use my\Exceptions\HttpException;
use my\Model\Name;
use my\Model\User;
use my\Model\UUID;
use my\Repositories\UserRepository;
use my\Repositories\UserRepositoryInterface;

class CreateUser implements  ActionInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private LoggerInterface $logger
    )
    {

    }
    public function handle(Request $request): Response
    {
        $newUserUuid = UUID::random();

        $data = $request->body(['username', 'first_name', 'last_name']);

        try {
            $user = new User(
                $newUserUuid,
                $data['username'],
                new Name(
                    $data['first_name'],
                    $data['last_name'],
                ),
            );
        } catch (HttpException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        $this->userRepository->save($user);

        $this->logger->info("User created: $newUserUuid");

        return new SuccessfullResponse([
            'uuid' => (string)$newUserUuid
        ]);
    }
}