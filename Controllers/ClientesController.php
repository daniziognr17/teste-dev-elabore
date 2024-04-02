<?php 
header("Content-type:application/json");
require_once __DIR__.'/../Models/ClienteModel.php';

class ClientesController {
    public function list () {
        $clienteModel = new ClienteModel();
        $clienteList = $clienteModel->fetch();

        if (!empty($clienteList)) {
            http_response_code(200);
            echo json_encode($clienteList);
        } else {
            http_response_code(404);
            echo json_encode([]);
        }
    }

    public function create () {
        $requestBody = file_get_contents('php://input');
        $requestData = json_decode($requestBody, true);

        if (!isset($requestData['NOME']) || !isset($requestData['CIDADE_NOME'])) {
            http_response_code(400);
            echo json_encode('Erro, informe os dados');
            return;
        }

        $nome = $requestData['NOME'];
        $cidade = $requestData['CIDADE_NOME'];

        $input = array();
        $input['cliente_nome'] = $nome;
        $input['cliente_cidade'] = $cidade;

        $clienteModel = new ClienteModel();
        $id = $clienteModel->create($input);

        if (!empty($id)) {
            http_response_code(200);
            echo json_encode($id);
        } else {
            http_response_code(500);
            echo json_encode('Erro, tente novamente');
        }
    }

    public function show ($id) {
        $clienteModel = new ClienteModel();
        $clienteData = $clienteModel->fetchById($id);

        if (!empty($clienteData)) {
            http_response_code(200);
            echo json_encode($clienteData);
        } else {
            http_response_code(404);
            echo json_encode("Erro, nao encontrado");
        }
    }

    public function update ($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode('Erro, informe os dados');
            return;  
        }

        $requestBody = file_get_contents('php://input');
        $requestData = json_decode($requestBody, true);

        if (!isset($requestData['NOME']) || !isset($requestData['CIDADE_NOME'])) {
            http_response_code(400);
            echo json_encode('Erro, informe os dados');
            return;
        }

        $clienteModel = new ClienteModel();

        $clienteExists = $clienteModel->fetchById($id);

        if (!$clienteExists) {
            http_response_code(400);
            echo json_encode('Erro, cliente nao encontrado');
        }

        $input = array();
        $input['cliente_nome'] = $requestData['NOME'];
        $input['cliente_cidade'] = $requestData['CIDADE_NOME'];

        
        if ($clienteModel->update($id, $input)) {
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    }

    public function delete ($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode('Erro, informe os dados');
            return;  
        }

        $clienteModel = new ClienteModel();

        $clienteExists = $clienteModel->fetchById($id);

        if (!$clienteExists) {
            http_response_code(400);
            echo json_encode('Erro, cliente nao encontrado');
        }
        
        if ($clienteModel->delete($id)) {
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    }
}