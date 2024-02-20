<?php
// Headers requis pour une API REST
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclure les fichiers de configuration de la base de données et du modèle Projet
include_once '../config/Database.php';
include_once '../models/projet.php';

// Instanciation de la base de données et de l'objet projet
$database = new Database();
$db = $database->getConnection();

$projet = new Projet($db);

// Obtenir la méthode de la requête HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Lire les projets
        $stmt = $projet->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $projets_arr = array();
            $projets_arr["data"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($projets_arr["data"], $row);
            }

            http_response_code(200);
            echo json_encode($projets_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Aucun projet trouvé."));
        }
        break;

    case 'POST':
        // Créer un nouveau projet
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->NOM) && isset($data->BUDGET) && isset($data->NE)) {
            $projet->NOM = $data->NOM;
            $projet->BUDGET = $data->BUDGET;
            $projet->NE = $data->NE;

            if ($projet->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Projet créé avec succès."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de créer le projet."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Impossible de créer le projet. Les données sont incomplètes."));
        }
        break;

    case 'PUT':
        // Mettre à jour un projet existant
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->NP)) {
            $projet->NP = $data->NP;
            $projet->NOM = $data->NOM;
            $projet->BUDGET = $data->BUDGET;
            $projet->NE = $data->NE;

            if ($projet->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Projet mis à jour."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de mettre à jour le projet."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Impossible de mettre à jour le projet. L'identifiant du projet (NP) est manquant."));
        }
        break;

    case 'DELETE':
        // Supprimer un projet
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->NP)) {
            $projet->NP = $data->NP;

            if ($projet->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Projet supprimé."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de supprimer le projet."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Impossible de supprimer le projet. L'identifiant du projet (NP) est manquant."));
        }
        break;

    default:
        // Gérer les méthodes non prises en charge
        http_response_code(405);
        echo json_encode(array("message" => "Méthode non autorisée."));
        break;
}

