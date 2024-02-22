<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../models/chercheur.php';

$database = new Database();
$db = $database->getConnection();

$chercheur = new Chercheur($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        if ($action === 'withTeam') {
            $stmt = $chercheur->readWithTeam();
        } else {
            $stmt = $chercheur->read();
        }

        $num = $stmt->rowCount();

        if ($num > 0) {
            $chercheurs_arr = array();
            $chercheurs_arr["data"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($action === 'withTeam') {
                    $chercheur_item = array(
                        "NC" => $row['NC'],
                        "NOM" => $row['NOM'],
                        "PRENOM" => $row['PRENOM'],
                        "NOM_EQUIPE" => $row['NOM_EQUIPE']
                    );
                } else {
                    $chercheur_item = array(
                        "NC" => $row['NC'],
                        "NOM" => $row['NOM'],
                        "PRENOM" => $row['PRENOM'],
                        "NE" => $row['NE']
                    );
                }

                array_push($chercheurs_arr["data"], $chercheur_item);
            }

            http_response_code(200);
            echo json_encode($chercheurs_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Aucun chercheur trouvé."));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->NOM) && !empty($data->PRENOM) && isset($data->NE) && isset($data->NC)) {
            $chercheur->NC = $data->NC;
            $chercheur->NOM = $data->NOM;
            $chercheur->PRENOM = $data->PRENOM;
            $chercheur->NE = $data->NE;

            if ($chercheur->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Chercheur créé avec succès."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de créer le chercheur."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Les données sont incomplètes."));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->NC)) {
            $chercheur->NC = $data->NC;
            $chercheur->NOM = $data->NOM;
            $chercheur->PRENOM = $data->PRENOM;
            $chercheur->NE = $data->NE;

            if ($chercheur->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Chercheur mis à jour."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de mettre à jour le chercheur."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Identifiant du chercheur manquant ou données incomplètes."));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->NC)) {
            $chercheur->NC = $data->NC;

            if ($chercheur->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Chercheur supprimé."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de supprimer le chercheur."));
            }
        } else {
            http_response_code(400);
            echo json_encode            (array("message" => "Impossible de supprimer le chercheur. Identifiant manquant."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Méthode non autorisée."));
        break;
}



