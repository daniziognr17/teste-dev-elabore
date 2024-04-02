<?php 
require_once __DIR__.'/../Models/ProdutoModel.php';

class ProdutosController {
    public function list () {
        $produtoModel = new ProdutoModel();
        $produtoList = $produtoModel->fetch();

        if (!empty($produtoList)) {
            http_response_code(200);
            echo json_encode($produtoList);
        } else {
            http_response_code(404);
            echo json_encode([]);
        }
    }

    public function create () {
        $requestBody = file_get_contents('php://input');
        $requestData = json_decode($requestBody, true);

        if (!isset($requestData['NOME']) || !isset($requestData['VLRUNIT'])) {
            http_response_code(400);
            echo json_encode('Erro, informe os dados');
            return;
        }

        $nome = $requestData['NOME'];
        $val = $requestData['VLRUNIT'];

        $input = array();
        $input['produto_nome'] = $nome;
        $input['produto_valor_unid'] = $val;

        $produtoModel = new ProdutoModel();
        $id = $produtoModel->create($input);

        if (!empty($id)) {
            http_response_code(200);
            echo json_encode($id);
        } else {
            http_response_code(500);
            echo json_encode('Erro, tente novamente');
        }
    }

    public function show ($id) {
        $produtoModel = new ProdutoModel();
        $produtoModel = $produtoModel->fetchById($id);

        if (!empty($produtoModel)) {
            http_response_code(200);
            echo json_encode($produtoModel);
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

        if (!isset($requestData['NOME']) || !isset($requestData['VLRUNIT'])) {
            http_response_code(400);
            echo json_encode('Erro, informe os dados');
            return;
        }

        $produtoModel = new ProdutoModel();

        $produtoExists = $produtoModel->fetchById($id);

        if (!$produtoExists) {
            http_response_code(400);
            echo json_encode('Erro, produto nao encontrado');
        }

        $input = array();
        $input['produto_nome'] = $requestData['NOME'];
        $input['produto_valor_unid'] = $requestData['VLRUNIT'];

        
        if ($produtoModel->update($id, $input)) {
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

        $produtoModel = new ProdutoModel();

        $produtoExists = $produtoModel->fetchById($id);

        if (!$produtoExists) {
            http_response_code(400);
            echo json_encode('Erro, produto nao encontrado');
        }
        
        if ($produtoModel->delete($id)) {
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    }
}