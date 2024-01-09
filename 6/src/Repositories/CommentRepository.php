<?php
namespace my\Repositories;

use my\Exceptions\CommentIncorrectDataException;
use my\Exceptions\CommentNotFoundException;
use my\Model\UUID;
use PDO;
use PDOException;
use my\Model\Comment;

class CommentRepository implements CommentsRepositoryInterface {
    public function __construct(
        private PDO $pdo
    ) {
    }

    public function get(UUID $uuid): Comment {
        $stmt = $this->pdo->prepare("SELECT * FROM comments WHERE uuid = :uuid");

        try {
            $stmt->execute([
                ":uuid" => $uuid
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new CommentNotFoundException("Not found with UUID $uuid");
            }
        } catch (PDOException $e) {
            throw new CommentIncorrectDataException("Error with: " . $e->getMessage());
        }

        return new Comment($result['uuid'], $result['author_uuid'],
            $result['post_uuid'], $result['text']);
    }

    public function save(Comment $comment): void {
        $stmt = $this->pdo->prepare("INSERT INTO comments (uuid, author_uuid, post_uuid, text) 
            VALUES (:uuid, :author_uuid, :post_uuid, :text)");

        try {
            $stmt->execute([
                ':uuid' => $comment->getUuid(),
                ':author_uuid' => $comment->getAuthorUuid(),
                ':post_uuid' => $comment->getPostUuid(),
                ':text' => $comment->getText()
            ]);
        } catch (PDOException $e) {
            throw new CommentIncorrectDataException("Error with saving: " . $e->getMessage());
        }
    }
}