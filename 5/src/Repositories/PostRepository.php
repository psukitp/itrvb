<?php
namespace my\Repositories;

use my\Exceptions\PostIncorrectDataException;
use my\Exceptions\PostNotFoundException;
use my\Model\Post;
use my\Model\UUID;
use PDO;
use PDOException;

class PostRepository implements PostsRepositoryInterface {

    public function __construct(private PDO $pdo) {
    }

    public function get(UUID $uuid): Post {
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE uuid = :uuid");

        try {
            $stmt->execute([
                ":uuid" => $uuid
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new PostNotFoundException("Not found with UUID $uuid");
            }
        } catch (PDOException $e) {
            throw new PostIncorrectDataException("Error with: " . $e->getMessage());
        }

        return new Post($result['uuid'], $result['author_uuid'],
            $result['title'], $result['text']);
    }

    public function save(Post $post): void {
        $stmt = $this->pdo->prepare("INSERT INTO posts (uuid, author_uuid, title, text) 
            VALUES (:uuid, :author_uuid, :title, :text)");

        try {
            $stmt->execute([
                ':uuid' => $post->getUuid(),
                ':author_uuid' => $post->getAuthorUuid(),
                ':title' => $post->getTitle(),
                ':text' => $post->getText()
            ]);
        } catch (PDOException $e) {
            throw new PostIncorrectDataException("Error with saving: " . $e->getMessage());
        }
    }
}