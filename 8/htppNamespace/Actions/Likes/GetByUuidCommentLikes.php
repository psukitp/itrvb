<?php

namespace htppNamespace\Actions\Likes;

use htppNamespace\Actions\ActionInterface;
use htppNamespace\ErrorResponse;
use htppNamespace\Request;
use htppNamespace\Response;
use htppNamespace\SuccessfullResponse;
use my\Exceptions\CommentLikeNotFoundException;
use my\Exceptions\HttpException;
use my\Model\CommentLike;
use my\Model\UUID;
use my\Repositories\CommentLikeRepository;
use my\Repositories\CommentLikeRepositoryInterface;

class GetByUuidCommentLikes implements  ActionInterface
{
    public function __construct(
        private CommentLikeRepositoryInterface $commentLikeRepository
    ) { }

    public function handle(Request $request): Response
    {
        try {
            $commentUuid = $request->query('uuid');
        } catch (HttpException $ex) {
            return new ErrorResponse($ex->getMessage());
        }

        try {
            $commentLikes = $this->commentLikeRepository->getByCommentUuid(new UUID($commentUuid));
        } catch (CommentLikeNotFoundException $ex) {
            return new ErrorResponse($ex->getMessage());
        }

        $likesData = array_map(function (CommentLike $like) {
            return [
                'uuid' => (string)$like->getUuid(),
                'user_uuid' => (string)$like->getUserUuid()
            ];
        }, $commentLikes);

        return new SuccessfullResponse([
            'comment' => $commentUuid,
            'likes' => $likesData
        ]);
    }
}