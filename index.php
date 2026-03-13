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
$controller = new TaskController($taskModel);

// 4. Analyse de l'URL et de la méthjode HTTP 

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// 1. On récupère le chemin de base (ex: /Build-Your-API)
$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);

// 2. On nettoie l'URI de manière insensible à la casse
// On utilise str_ireplace (le 'i' signifie case-insensitive)
$uri = str_ireplace($basePath, '', $_SERVER['REQUEST_URI']);

// 3. On enlève les éventuels paramètres de requête (?id=1...)
$uri = parse_url($uri, PHP_URL_PATH);

// 4. Nettoyage final des slashes
$uri = trim($uri, '/');

// 5. Découpage
$segments = explode('/', $uri);

if (($segments[0] ?? '') !== 'api') {
    http_response_code(404);
    echo json_encode(["success" => false, "error" => "Route introuvable."]);
    exit();
}

$ressource = $segments[1] ?? '';

$id = isset($segments[2]) ? (int)$segments[2] : null;

// 5. Routage 

switch ($ressource) {
    case 'tasks':
        switch($method) {
            case 'GET':
                if ($id !== null) {
                    $controller->getById($id);
                } else {
                    $controller->getAll();
                }
                break;
            
            case 'POST':
                $controller->create();
                break;
            
            case 'PUT':
            case 'PATCH':
                if ($id !== null) {
                    $controller->update($id);
                } else {
                    http_response_code(400);
                    echo json_encode(["success" => false, "error" => "ID requis pour la mise à jour."]);
                }
                break;
            
            case 'DELETE':
                if ($id !== null) {
                    $controller->delete($id);
                } else {
                    http_response_code(400);
                    echo json_encode(["success" => false, "error" => "ID requis pour la suppression."]);
                }
                break;
            
            default:
                http_response_code(405);
                echo json_encode(["success" => false, "error" => "Méthode HTTP non autorisée."]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["success" => false, "error" => "Ressource '{$ressource}' introuvable."]);
        break;
            
}

?>