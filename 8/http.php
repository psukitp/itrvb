<?php

require 'vendor/autoload.php';

use htppNamespace\Actions\Comments\CreateComment;
use htppNamespace\Actions\Likes\CreateCommentLike;
use htppNamespace\Actions\Likes\CreatePostLike;
use htppNamespace\Actions\Likes\GetByUuidCommentLikes;
use htppNamespace\Actions\Likes\GetByUuidPostLikes;
use htppNamespace\Actions\Posts\CreatePost;
use htppNamespace\Actions\Posts\DeletePost;
use htppNamespace\Actions\Users\CreateUser;
use htppNamespace\Actions\Users\FindByUsername;
use htppNamespace\ErrorResponse;
use htppNamespace\Request;
use my\Repositories\UserRepository;
use my\Repositories\CommentRepository;
use my\Repositories\PostRepository;
use Psr\Log\LoggerInterface;


ini_set('display_errors', 1);
error_reporting(E_ALL);

$container = require __DIR__ . '/bootstrap.php';

$logger = $container->get(LoggerInterface::class);

try {
    $request = new Request($_GET, $_POST, $_SERVER);
} catch (Exception $ex) {
    $logger->warning($ex->getMessage());
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

try {
    $path = $request->path();
} catch (Exception $ex) {
    $logger->warning($ex->getMessage());
    (new ErrorResponse($ex->getMessage()))->send();
    return;
}

try {
    $method = $request->method();
} catch (Exception $ex) {
    $logger->warning($ex->getMessage());
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
        '/likes/comment/' => CreateCommentLike::class,
        '/user/' => CreateUser::class
    ],
    'DELETE' => [
        '/posts' => new DeletePost(
            new PostRepository(
                new PDO('sqlite:' . __DIR__ . '/db/blog.sqlite')
            )
        ),
    ]
];

$response = new ErrorResponse('An unknown error occurred.');

$action = $routs[$method][$path];

try {
    $path = $request->path();
    $method = $request->method();

    if (!array_key_exists($method, $routs) || !array_key_exists($path, $routs[$method])) {
        $message = "Route not found: $method $path";
        $logger->notice($message);
        $response = new ErrorResponse($message);
    } else {
        $actionClassName = $routs[$method][$path];
        $action = $container->get($actionClassName);
        $response = $action->handle($request);
    }
} catch (Exception $ex) {
    $logger->error($ex->getMessage(), ['exception' => $ex]);
    $response = new ErrorResponse($ex->getMessage());
}

$response->send();