<?php

namespace my\Repositories;

use my\Model\PostLike;
use my\Model\UUID;

interface PostLikeRepositoryInterface
{
    public function save(PostLike $postLike);
    public function getByPostUuid(UUID $postUuid);
}