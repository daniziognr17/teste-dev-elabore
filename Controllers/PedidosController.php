<?php 
require_once __DIR__.'/../Models/PedidoModel.php'; 

class PedidosController {
    public function list () {
        $pedidoModel = new PedidoModel();
        $pedidoList = $pedidoModel->fetch();

        if (!empty($pedidoList)) {
            http_response_code(200);
            echo json_encode($pedidoList);
        } else {
            http_response_code(404);
            echo json_encode([]);
        }
    }

    public function create () {
        $requestBody = file_get_contents('php://input');
        $requestData = json_decode($requestBody, true);

        if (!isset($requestData['CLIENTE_ID']) || !isset($requestData['ITENS'])) {
            http_response_code(400);
            echo json_encode('Erro, informe os dados');
            return;
        }

        $cliente_id = $requestData['CLIENTE_ID'];
        $itens = $requestData['ITENS'];

        $input = array();
        $input['cliente_id'] = $cliente_id;

        $itensAux = array();
        $count = 0;
        foreach ($itens as $item) {
            $itensAux[$count]['produto_id'] = $item['PRODUTO_ID'];
            $itensAux[$count]['produto_qtd'] = $item['QUANTIDADE'];
            $count++;
        }

        $input['itens'] = $itensAux;

        $pedidoModel = new PedidoModel();
        $id = $pedidoModel->create($input);

        if (!empty($id)) {
            http_response_code(200);
            echo json_encode($id);
        } else {
            http_response_code(500);
            echo json_encode('Erro, tente novamente');
        }
    }

    public function show ($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode('Erro, informe os dados');
        }

        $pedidoModel = new PedidoModel();
        $pedidoList = $pedidoModel->fetchById($id);

        if (!empty($pedidoList)) {
            http_response_code(200);
            echo json_encode($pedidoList);
        } else {
            http_response_code(404);
            echo json_encode("Erro, nao encontrado");
        }
    }

    public function update ($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode('Erro, informe os dados');
        }

        $requestBody = file_get_contents('php://input');
        $requestData = json_decode($requestBody, true);

        if (!isset($requestData['CLIENTE_ID']) || !isset($requestData['ITENS'])) {
            http_response_code(400);
            echo json_encode('Erro, informe os dados');
            return;
        }

        $cliente_id = $requestData['CLIENTE_ID'];
        $itens = $requestData['ITENS'];

        $input['cliente_id'] = $cliente_id;
        $input['itens'] = $itens;

        $pedidosModel = new PedidoModel();
        
        if ($pedidosModel->update($id, $input)) {
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    }

    public function delete ($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode('Erro, informe os dados');
        }

        $pedidoModel = new PedidoModel();
        
        if ($pedidoModel->delete($id)) {
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    }
}