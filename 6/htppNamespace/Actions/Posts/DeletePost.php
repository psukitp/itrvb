<?php

namespace htppNamespace\Actions\Posts;

use htppNamespace\Actions\ActionInterface;
use htppNamespace\ErrorResponse;
use htppNamespace\Request;
use htppNamespace\Response;
use htppNamespace\SuccessfullResponse;
use my\Exceptions\HttpException;
use my\Exceptions\PostNotFoundException;
use my\Repositories\PostRepository;

class DeletePost implements ActionInterface
{
    public function __construct(
        private PostRepository $postRepository
    ) { }

    public function handle(Request $request): Response
    {
        try {
            $uuid = $request->query('uuid');
            $this->postRepository->delete($uuid);
            return new SuccessfullResponse(['message' => 'Post deleted successfully']);
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
    }
}