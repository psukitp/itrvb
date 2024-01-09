<?php
namespace my\Repositories;

use my\Model\CommentLike;
use my\Model\UUID;

interface CommentLikeRepositoryInterface
{
    public function save(CommentLike $commentLike);
    public function getByCommentUuid(UUID $commentUuid);
}