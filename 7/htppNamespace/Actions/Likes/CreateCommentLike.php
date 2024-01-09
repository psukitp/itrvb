<?php

namespace htppNamespace\Actions\Likes;

use htppNamespace\Actions\ActionInterface;
use htppNamespace\ErrorResponse;
use htppNamespace\Request;
use htppNamespace\Response;
use htppNamespace\SuccessfullResponse;
use my\Model\CommentLike;
use my\Model\UUID;
use my\Repositories\CommentLikeRepository;

class CreateCommentLike implements ActionInterface
{
    public function __construct(
        private CommentLikeRepository $commentLikeRepository
    ) {
    }

    public function handle(Request $request): Response
    {
        try {
            $data = $request->body(['comment_uuid', 'user_uuid']);
            $uuid = UUID::random();
            $commentUuid = new UUID($data['comment_uuid']);
            $userUuid = new UUID($data['user_uuid']);

            $comment = new CommentLike($uuid, $commentUuid, $userUuid);
            $this->commentLikeRepository->save($comment);

            return new SuccessfullResponse(['message' => 'Post like created successfully']);
        } catch (\Exception $ex) {
            return new ErrorResponse($ex->getMessage());
        }
    }
}