<?php
// Headers requis
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Inclure les fichiers de base de données et du modèle
include_once '../config/Database.php';
include_once '../models/aff.php';

// Instanciation de la base de données et de l'objet Aff
$database = new Database();
$db = $database->getConnection();

$aff = new Aff($db);

// Obtenir la méthode de la requête HTTP
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Lire les affiliations
        $stmt = $aff->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $affs_arr = array();
            $affs_arr["data"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($affs_arr["data"], $row);
            }

            http_response_code(200);
            echo json_encode($affs_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Aucune affiliation trouvée."));
        }
        break;

    case 'POST':
        // Créer une nouvelle affiliation
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->NP) && !empty($data->NC) && !empty($data->ANNEE)) {
            $aff->NP = $data->NP;
            $aff->NC = $data->NC;
            $aff->ANNEE = $data->ANNEE;

            if ($aff->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Affiliation créée avec succès."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de créer l'affiliation."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Impossible de créer l'affiliation. Les données sont incomplètes."));
        }
        break;

    case 'PUT':
        // Mettre à jour une affiliation existante
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->NP) && !empty($data->NC)) {
            $aff->NP = $data->NP;
            $aff->NC = $data->NC;
            $aff->ANNEE = $data->ANNEE;

            if ($aff->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Affiliation mise à jour."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de mettre à jour l'affiliation."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Impossible de mettre à jour l'affiliation. Les données sont incomplètes."));
        }
        break;

    case 'DELETE':
        // Supprimer une affiliation
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->NP) && !empty($data->NC)) {
            $aff->NP = $data->NP;
            $aff->NC = $data->NC;

            if ($aff->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Affiliation supprimée."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de supprimer l'affiliation."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Impossible de supprimer l'affiliation. Identifiant manquant."));
        }
        break;

    default:
        // Gérer les méthodes non prises en charge
        http_response_code(405);
        echo json_encode(array("message" => "Méthode non autorisée."));
        break;
}

