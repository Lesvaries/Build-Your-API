<?php
// 1. En-tête globaux 
header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
http_response_code(200);
exit();
}

// 2. Autoload 

require_once 'config/Database.php';
require_once 'src/Models/Task.php';
require_once 'src/Controllers/TaskController.php';

// 3. Connexion à la base 

$database = new Database();
$connexion = $database->getConnection();
$taskModel = new Task($connexion);
// $controller = new TaskController($taskModel);

// 4. Analyse de l'URL et de la méthjode HTTP 

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Nettoyer l'URI : supprimer les slashes en début et fin
// Ex: "/api/tasks/" → "api/tasks"
$uri = trim($uri, '/');

// Découper l'URI en segments
// Ex: "api/tasks/42" → ["api", "tasks", "42"]
$segments = explode('/', $uri);

if (($segments[0] ?? '') !== 'api') {
    http_response_code(404);
    echo json_encode(["success" => false, "error" => "Route introuvable."]);
    exit();
}

?>