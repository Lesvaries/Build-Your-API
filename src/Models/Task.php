<?php
// On inclut la classe Database pour pouvoir s'en servir
require_once '../../config/Database.php';

class Task {
    // ─── Propriétés ─────────────────────────────────────────────
    private PDO $conn; // La connexion PDO
    private string $table = "tasks"; // Nom de la table

    // Propriétés qui correspondent aux colonnes de la table
    public ?int $id = null;
    public ?string $title = null;
    public ?string $description = null;
    public string $status = "todo";
    public int $priority = 1;

    /**
     * Le constructeur reçoit la connexion PDO depuis l'extérieur.
     * C'est de l'injection de dépendance.
     */
    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function getAll(): PDOStatement {
        $query = "SELECT id, title, description, status, priority, created_at
                  FROM {$this->table}
                  ORDER BY priority DESC, created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt; // Le contrôleur fera $stmt->fetchAll()
    }

    public function getById(): PDOStatement {
        // Correction de la requête (ajout de updated_at si nécessaire)
        $query = "SELECT id, title, description, status, priority, created_at
                  FROM {$this->table}
                  WHERE id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        
        // bindParam lie la valeur à l'exécution (par référence)
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    public function create(): bool {
        $query = "INSERT INTO {$this->table}
                  (title, description, status, priority)
                  VALUES
                  (:title, :description, :status, :priority)";

        $stmt = $this->conn->prepare($query);

        // Nettoyage des données
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description ?? ''));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindParam(':status', $this->status, PDO::PARAM_STR);
        $stmt->bindParam(':priority', $this->priority, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function update(): bool {
        $query = "UPDATE {$this->table}
                  SET title = :title,
                      description = :description,
                      status = :status,
                      priority = :priority
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Nettoyage
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description ?? ''));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
        $stmt->bindParam(':status', $this->status, PDO::PARAM_STR);
        $stmt->bindParam(':priority', $this->priority, PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}