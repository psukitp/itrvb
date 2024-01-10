<?php
namespace my\Repositories;

use my\Exceptions\PostIncorrectDataException;
use my\Exceptions\PostNotFoundException;
use my\Model\Post;
use my\Model\UUID;
use PDO;
use PDOException;
use Psr\Log\LoggerInterface;
use my\Repositories\PostsRepositoryInterface;

class PostRepository implements PostsRepositoryInterface
{

    public function __construct(
        private PDO $pdo,
        private LoggerInterface $logger
    ) {
    }

    public function get(UUID $uuid): Post
    {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE uuid = :uuid");

        try {
            $stmt->execute([
                ":uuid" => $uuid
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                $this->logger->warning("Post not found", ['uuid' => $uuid]);
                throw new PostNotFoundException("Not found with UUID $uuid");
            }
        } catch (PDOException $e) {
            throw new PostIncorrectDataException("Error with: " . $e->getMessage());
        }

        $this->logger->info("Post get successfully", ['uuid' => $uuid]);
        return new Post(
            new UUID($result['uuid']),
            new UUID($result['author_uuid']),
            $result['title'],
            $result['text']
        );
    }

    public function save(Post $post): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO posts (uuid, author_uuid, title, text) 
            VALUES (:uuid, :author_uuid, :title, :text)");

        try {
            $stmt->execute([
                ':uuid' => $post->getUuid(),
                ':author_uuid' => $post->getAuthorUuid(),
                ':title' => $post->getTitle(),
                ':text' => $post->getText()
            ]);
            $this->logger->info("Post saved successfully", ['uuid' => $post->getUuid()]);
        } catch (PDOException $e) {
            throw new PostIncorrectDataException("Error with saving: " . $e->getMessage());
        }
    }

    public function delete(UUID $uuid): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE uuid = :uuid");
        $stmt->execute([':uuid' => $uuid]);

        if ($stmt->rowCount() === 0) {
            $this->logger->warning("Post not found", ['uuid' => $uuid]);
            throw new PostNotFoundException("Post with UUID $uuid not found");
        }
        $this->logger->info("Post delete successfully", ['uuid' => $uuid]);
    }
}