<?php

class Database {
    // ─── Paramètres de connexion ──────────────────────────────────
    // En production, ces valeurs viendraient d'un fichier .env
    private string $host = "localhost";
    private string $dbName = "taskmaster_db";
    private string $username = "root";
    private string $password = ""; // Vide sur XAMPP par défaut

    // L'instance PDO — null avant la première connexion
    private ?PDO $conn = null;

    /**
     * Retourne la connexion PDO.
     * La crée si elle n'existe pas encore (pattern Lazy Loading).
     */
    public function getConnection(): PDO {
        if ($this->conn === null) {
            try {
                // Le DSN (Data Source Name) : chaîne qui décrit la connexion
                $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8";

                $this->conn = new PDO(
                    $dsn,
                    $this->username,
                    $this->password,
                    [
                        // En cas d'erreur SQL → exception PHP (try/catch)
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        // Les résultats sont des tableaux associatifs par défaut
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        // Désactive l'émulation des requêtes préparées (plus sûr)
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                // On ne renvoie JAMAIS le message d'erreur brut en production
                // car il peut contenir des infos sensibles (hôte, nom de BDD...)
                http_response_code(500);
                echo json_encode([
                    "success" => false,
                    "error" => "Erreur de connexion à la base de données."
                ]);
                exit(); // Stoppe tout — inutile de continuer sans BDD
            }
        }
        return $this->conn;
    }
}