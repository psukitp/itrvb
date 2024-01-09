<?php

require 'vendor/autoload.php';

use htppNamespace\Actions\Comments\CreateComment;
use htppNamespace\Actions\Likes\CreateCommentLike;
use htppNamespace\Actions\Likes\CreatePostLike;
use htppNamespace\Actions\Likes\GetByUuidCommentLikes;
use htppNamespace\Actions\Likes\GetByUuidPostLikes;
use htppNamespace\Actions\Posts\CreatePost;
use htppNamespace\Actions\Posts\DeletePost;
use htppNamespace\Actions\Users\FindByUsername;
use htppNamespace\ErrorResponse;
use htppNamespace\Request;
use my\Repositories\UserRepository;
use my\Repositories\CommentRepository;
use my\Repositories\PostRepository;


ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $request = new Request($_GET, $_POST, $_SERVER);
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

try {
    $path = $request->path();
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

try {
    $method = $request->method();
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

$routs = [
    'GET' => [
        '/users/show' => new FindByUsername(
            new UserRepository(
                new PDO('sqlite:' . __DIR__ . '/db/blog.sqlite')
            )
        ),
        '/likes/comment' => GetByUuidCommentLikes::class,
        '/likes/post' => GetByUuidPostLikes::class,
    ],
    'POST' => [
        '/posts/comment' => new CreateComment(
            new CommentRepository(
                new PDO('sqlite:' . __DIR__ . '/db/blog.sqlite')
            )
        ),
        '/posts/create' => new CreatePost(
            new PostRepository(
                new PDO('sqlite:' . __DIR__ . '/db/blog.sqlite')
            )
        ),
        '/likes/post/' => CreatePostLike::class,
        '/likes/comment/' => CreateCommentLike::class
    ],
    'DELETE' => [
        '/posts' => new DeletePost(
            new PostRepository(
                new PDO('sqlite:' . __DIR__ . '/db/blog.sqlite')
            )
        ),
    ]
];

if (!array_key_exists($method, $routs) || !array_key_exists($path, $routs[$method])) {
    (new ErrorResponse('Not found path'))->send();
    return;
}

$action = $routs[$method][$path];

try {
    $response = $action->handle($request);
} catch (Exception $ex) {
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

$response->send();