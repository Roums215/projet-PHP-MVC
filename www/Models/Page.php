<?php
namespace App\Models;

use App\Core\Database;

class Page {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll() {
        return $this->pdo->query("SELECT * FROM pages ORDER BY created_at DESC")->fetchAll();
    }

    public function getBySlug($slug) {
        $stmt = $this->pdo->prepare("SELECT * FROM pages WHERE slug = :slug LIMIT 1");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch();
    }

    public function create($title, $slug, $content) {
        $stmt = $this->pdo->prepare("INSERT INTO pages (title, slug, content) VALUES (:title, :slug, :content)");
        return $stmt->execute([
            ':title' => $title,
            ':slug' => $slug,
            ':content' => $content
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM pages WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}