<?php

namespace htppNamespace\Actions\Posts;

use htppNamespace\Actions\ActionInterface;
use htppNamespace\ErrorResponse;
use htppNamespace\Request;
use htppNamespace\Response;
use htppNamespace\SuccessfullResponse;
use my\Exceptions\HttpException;
use my\Model\UUID;
use my\Repositories\PostsRepositoryInterface;

class DeletePost implements \http\Actions\ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $uuid = $request->query('uuid');
            $this->postRepository->delete(new UUID($uuid));
            return new SuccessfullResponse(['message' => 'Post deleted successfully']);
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
    }
}