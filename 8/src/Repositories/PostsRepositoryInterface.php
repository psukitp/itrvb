<?php
namespace my\Repositories;

use my\Model\Post;
use my\Model\UUID;

interface PostsRepositoryInterface
{
    public function get(UUID $uuid): Post;
    public function save(Post $post): void;
    public function delete(UUID $uuid): void;
}

?>