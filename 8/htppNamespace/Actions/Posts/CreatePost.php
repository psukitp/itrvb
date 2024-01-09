<?php

namespace htppNamespace\Actions\Posts;

use htppNamespace\Actions\ActionInterface;
use htppNamespace\ErrorResponse;
use htppNamespace\Request;
use htppNamespace\Response;
use htppNamespace\SuccessfullResponse;
use my\Exceptions\InvalidArgumentException;
use my\Model\Post;
use my\Model\UUID;
use my\Repositories\PostRepository;

class CreatePost implements \http\Actions\ActionInterface
{
    public function __construct(
        private PostRepository $postRepository
    ) { }
    public function handle(Request $request): Response
    {
        try {
            $data = $request->body(['author_uuid', 'title', 'text']);
            $uuid = UUID::random();
            $authorUuid = new UUID($data['author_uuid']);
            $title = $data['title'];
            $text = $data['text'];

            if (empty($title) || empty($text)) {
                throw new InvalidArgumentException('Title or text cannot be empty');
            }

            $post = new Post($uuid, $authorUuid, $title, $text);
            $this->postRepository->save($post);


            return new SuccessfullResponse(['message' => 'Post created successfully']);
        } catch (\Exception $ex) {
            return new ErrorResponse($ex->getMessage());
        }
    }
}