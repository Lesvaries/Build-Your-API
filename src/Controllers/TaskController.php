<?php

class TaskController {
    private Task $taskModel;

    public function __construct(Task $model) {
        $this->taskModel = $model;
    }

    public function getAll(): void {
        $stmt = $this->taskModel->getAll();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "count" => count($tasks),
            "data" => $tasks
        ], JSON_UNESCAPED_UNICODE);
    }

    public function getById(int $id): void {
        $this->taskModel->id = $id;    
        
        $stmt = $this->taskModel->getById($id);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "Tâche {$id} non trouvée."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "data" => $task
        ], JSON_UNESCAPED_UNICODE);
    }

    public function create(): void {
        $body = json_decode(file_get_contents("php://input"), true);

        if (empty($body['title'])) {
            http_response_code(422);
            echo json_encode([
                "success" => false,
                "message" => "Le champs 'titre' est requis."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $statusValides = ['todo', 'in_progress', 'done'];
        if (isset($body['status']) && !in_array($body['status'], $statusValides)) {
            http_response_code(422);
            echo json_encode([
                "success" => false,
                "message" => "Le champ 'status' doit être l'une des valeurs suivantes : " . implode(", ", $statusValides) . "."
            ], JSON_UNESCAPED_UNICODE);
            return;
        }

        $this->taskModel->title         = $body['title'];
        $this->taskModel->description   = $body['description'] ?? null;
        $this->taskModel->status        = $body['status'] ?? 'todo';
        $this->taskModel->priority      = $body['priority'] ?? 1;

        if ($this->taskModel->create()) {
            http_response_code(201);
            echo json_encode([
                "success" => true,
                "message" => "Tâche créée avec succès."
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Une erreur est survenue lors de la création de la tâche."
            ]);
        }
    } // Fermeture correcte de la méthode create

    public function update(int $id): void {
        $this->taskModel->id = $id;
        $stmt = $this->taskModel->getById($id);
        
        // Correction : ajout du $ manquant devant stmt
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "Tâche {$id} non trouvée."
            ]);
            return;
        }

        $body = json_decode(file_get_contents("php://input"), true);

        if (empty($body['title'])) {
            http_response_code(422);
            echo json_encode([
                "success" => false,
                "message" => "Le champs 'titre' est requis."
            ]);
            return;
        }

        $this->taskModel->id            = $id;
        $this->taskModel->title         = $body['title'];
        $this->taskModel->description   = $body['description'] ?? null;
        $this->taskModel->status        = $body['status'] ?? 'todo';
        $this->taskModel->priority      = $body['priority'] ?? 1;

        if ($this->taskModel->update()) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Tâche {$id} mise à jour avec succès."
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Une erreur est survenue lors de la mise à jour de la tâche."
            ]);
        }
    } // Fermeture correcte de la méthode update

    public function delete(int $id): void {
        $this->taskModel->id = $id;
        $stmt = $this->taskModel->getById($id);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "Tâche {$id} non trouvée."
            ]);
            return;
        }

        if ($this->taskModel->delete()) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Tâche {$id} supprimée avec succès."
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Une erreur est survenue lors de la suppression de la tâche."
            ]);
        }
    }
}