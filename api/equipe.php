<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/Database.php';
include_once '../models/equipe.php';

$database = new Database();
$db = $database->getConnection();

$equipe = new Equipe($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        if ($action === 'teamProjectCount') {
            $stmt = $equipe->readTeamsAndProjectCount();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $equipes_projets_arr = array();
                $equipes_projets_arr["data"] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $equipe_item = array(
                        "NOM" => $row['NOM'],
                        "nombre_projets" => $row['nombre_projets']
                    );

                    array_push($equipes_projets_arr["data"], $equipe_item);
                }

                http_response_code(200);
                echo json_encode($equipes_projets_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Aucune équipe trouvée."));
            }
        } else {
            $stmt = $equipe->read();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $equipes_arr = array();
                $equipes_arr["data"] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($equipes_arr["data"], $row);
                }

                http_response_code(200);
                echo json_encode($equipes_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Aucune équipe trouvée."));
            }
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->NOM)) {
            $equipe->NOM = $data->NOM;

            if ($equipe->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Équipe créée avec succès."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de créer l'équipe."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Impossible de créer l'équipe. Les données sont incomplètes."));
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->NE) && !empty($data->NOM)) {
            $equipe->NE = $data->NE;
            $equipe->NOM = $data->NOM;

            if ($equipe->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Équipe mise à jour."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de mettre à jour l'équipe."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Impossible de mettre à jour l'équipe. Les données sont incomplètes."));
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->NE)) {
            $equipe->NE = $data->NE;

            if ($equipe->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Équipe supprimée."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Impossible de supprimer l'équipe."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Impossible de supprimer l'équipe. Identifiant manquant."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Méthode non autorisée."));
        break;
}

