<?php
namespace htppNamespace\Actions\Users;

use htppNamespace\Actions\ActionInterface;
use htppNamespace\ErrorResponse;
use htppNamespace\SuccessfullResponse;
use htppNamespace\Request;
use htppNamespace\Response;
use my\Exceptions\HttpException;
use my\Exceptions\UserNotFoundException;
use my\Repositories\UserRepositoryInterface;

class FindByUsername implements \http\Actions\ActionInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function handle(Request $request): Response
    {
        try {
            $username = $request->query('username');
        } catch (HttpException $ex) {
            return new ErrorResponse($ex->getMessage());
        }

        try {
            $user = $this->userRepository->getByUsername($username);
        } catch (UserNotFoundException $ex) {
            return new ErrorResponse($ex->getMessage());
        }

        return new SuccessfullResponse([
            'username' => $user->getUsername(),
            'name' => (string)$user->getName()
        ]);
    }
}