<?php
namespace my\Repositories;

use my\Exceptions\CommentIncorrectDataException;
use my\Exceptions\CommentNotFoundException;
use my\Model\UUID;
use PDO;
use PDOException;
use my\Model\Comment;
use Psr\Log\LoggerInterface;
use my\Repositories\CommentsRepositoryInterface;

class CommentRepository implements CommentsRepositoryInterface
{
    public function __construct(
        private PDO $pdo,
        private LoggerInterface $logger
    ) {
    }

    public function get(UUID $uuid): Comment
    {
        $stmt = $this->pdo->prepare("SELECT * FROM comments WHERE uuid = :uuid");

        try {
            $stmt->execute([
                ":uuid" => $uuid
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                $this->logger->warning("Comment not found", ['uuid' => $uuid]);
                throw new CommentNotFoundException("Not found with UUID $uuid");
            }
        } catch (PDOException $e) {
            throw new CommentIncorrectDataException("Error with: " . $e->getMessage());
        }

        $this->logger->info("Comment get successfully", ['uuid' => $uuid]);
        return new Comment(
            $result['uuid'],
            $result['author_uuid'],
            $result['post_uuid'],
            $result['text']
        );
    }

    public function save(Comment $comment): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO comments (uuid, author_uuid, post_uuid, text) 
            VALUES (:uuid, :author_uuid, :post_uuid, :text)");

        try {
            $stmt->execute([
                ':uuid' => $comment->getUuid(),
                ':author_uuid' => $comment->getAuthorUuid(),
                ':post_uuid' => $comment->getPostUuid(),
                ':text' => $comment->getText()
            ]);
            $this->logger->info("Comment saved successfully", ['uuid' => $comment->getUuid()]);
        } catch (PDOException $e) {
            $this->logger->warning("Comment not saved", ['uuid' => $comment->getUuid()]);
            throw new CommentIncorrectDataException("Error with saving: " . $e->getMessage());
        }
    }
}