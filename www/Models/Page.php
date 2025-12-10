<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Page {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAll($onlyPublished = false) {
        $baseSql = "SELECT p.*, u.firstname AS author_firstname, u.lastname AS author_lastname, u.email AS author_email
                    FROM pages p
                    LEFT JOIN users u ON p.user_id = u.id";

        if ($onlyPublished) {
            $stmt = $this->pdo->prepare($baseSql . " WHERE p.is_published = true ORDER BY p.created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll();
        }

        $stmt = $this->pdo->prepare($baseSql . " ORDER BY p.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM pages WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getBySlug($slug, $onlyPublished = true)
    {
        $sql = "SELECT p.*, u.firstname AS author_firstname, u.lastname AS author_lastname, u.email AS author_email
                FROM pages p
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.slug = :slug";
        if ($onlyPublished) {
            $sql .= " AND p.is_published = true";
        }
        $sql .= " LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        $page = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $page ?: false;
    }

//    public function getBySlugAdmin($slug) {
//        $stmt = $this->pdo->prepare("SELECT * FROM pages WHERE slug = :slug LIMIT 1");
  //      $stmt->execute([':slug' => $slug]);
    //    return $stmt->fetch();
    //}

    public function create($title, $slug, $content, $is_published = true, $user_id = null) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO pages (title, slug, content, is_published, user_id, created_at)
             VALUES (:title, :slug, :content, :is_published, :user_id, NOW())"
        );

        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':slug', $slug);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':is_published', (bool)$is_published, PDO::PARAM_BOOL);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update($id, $title, $slug, $content, $is_published) {
        $stmt = $this->pdo->prepare(
            "UPDATE pages SET title = :title, slug = :slug, content = :content, is_published = :is_published, updated_at = NOW()
             WHERE id = :id"
        );

        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':slug', $slug);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':is_published', (bool)$is_published, PDO::PARAM_BOOL);

        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM pages WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function slugExists($slug, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->pdo->prepare("SELECT id FROM pages WHERE slug = :slug AND id != :id LIMIT 1");
            $stmt->execute([':slug' => $slug, ':id' => $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT id FROM pages WHERE slug = :slug LIMIT 1");
            $stmt->execute([':slug' => $slug]);
        }
        return $stmt->fetch() ? true : false;
    }

    /**
     * Récupère toutes les pages d'un utilisateur
     */
    public function getByUserId($user_id, $onlyPublished = false) {
        $sql = "SELECT * FROM pages WHERE user_id = :user_id";
        if ($onlyPublished) {
            $sql .= " AND is_published = true";
        }
        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    /**
     * Vérifie si l'utilisateur est propriétaire de la page
     */
    public function isOwner($page_id, $user_id) {
        $stmt = $this->pdo->prepare("SELECT id FROM pages WHERE id = :id AND user_id = :user_id LIMIT 1");
        $stmt->execute([':id' => $page_id, ':user_id' => $user_id]);
        return $stmt->fetch() ? true : false;
    }
}