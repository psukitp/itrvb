<?php

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use my\Repositories\PostsRepositoryInterface;
use Psr\Log\LoggerInterface;
use my\Container\DIContainer;
use my\Repositories\CommentLikeRepository;
use my\Repositories\CommentLikeRepositoryInterface;
use my\Repositories\CommentRepository;
use my\Repositories\CommentsRepositoryInterface;
use my\Repositories\PostLikeRepository;
use my\Repositories\PostLikeRepositoryInterface;
use my\Repositories\PostRepository;
use my\Repositories\UserRepository;
use my\Repositories\UserRepositoryInterface;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$container = new DIContainer();

$container->bind(PDO::class, new PDO('sqlite:' . __DIR__ . '/' . $_SERVER['SQLITE_DB_PATH']));
$container->bind(UserRepositoryInterface::class, UserRepository::class);
$container->bind(CommentLikeRepositoryInterface::class, CommentLikeRepository::class);
$container->bind(CommentsRepositoryInterface::class, CommentRepository::class);
$container->bind(PostLikeRepositoryInterface::class, PostLikeRepository::class);
$container->bind(PostsRepositoryInterface::class, PostRepository::class);

$logger = (new Logger('blog'));


if ($_SERVER['LOG_TO_FILES'] === 'true') {
    $logger->pushHandler(new StreamHandler(__DIR__ . '/logs/blog.log'))
        ->pushHandler(
            new StreamHandler(
                __DIR__ . '/logs/blog.error.log',
                level: Level::Error,
                bubble: false
            )
        );
}

if ($_SERVER['LOG_TO_CONSOLE'] === 'true') {
    $logger->pushHandler(new StreamHandler("php://stdout"));
}

$container->bind(LoggerInterface::class, $logger);

return $container;