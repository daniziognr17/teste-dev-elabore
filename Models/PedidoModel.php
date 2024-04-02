<?php 
require_once __DIR__.'/Database.php';

class PedidoModel extends Database {
    public function fetch () {
        $stm = $this->getConnection()->query('SELECT * FROM pedidos p 
        LEFT JOIN pedidos_produtos pp ON (p.pedido_id = pp.pedido_id)
        LEFT JOIN produtos pro ON (pro.produto_id = pp.produto_id)
        ');
        if ($stm->rowCount() > 0) {
            $produtos = $stm->fetchAll(PDO::FETCH_ASSOC);
            $pedidosAgrupados = array();

            foreach ($produtos as $produto) {
                $pedidoId = $produto['pedido_id'];
        
                // Se este pedido ainda não estiver no array de pedidos agrupados, adicioná-lo
                if (!isset($pedidosAgrupados[$pedidoId])) {
                    $pedidosAgrupados[$pedidoId] = array(
                        'PEDIDO_ID' => $pedidoId,
                        'CLIENTE_ID' => $produto['cliente_id'],
                        'ITENS' => array()
                    );
                }
        
                // Adicionar o item ao array de itens do pedido
                $pedidosAgrupados[$pedidoId]['ITENS'][] = array(
                    'PRODUTO_ID' => $produto['produto_id'],
                    'VLRUNIT' => $produto['produto_valor_unid'],
                    'QUANTIDADE' => $produto['produto_qtd']
                );
            }
        
            // Converter o array de pedidos agrupados em JSON e retornar
            return $pedidosAgrupados;
        } else {
            return array();
        }
    }

    public function create ($input) {
        $conn = $this->getConnection();
        $stmt = $conn->prepare('INSERT INTO pedidos VALUES (NULL, :cliente)');
        $stmt->bindParam(':cliente', $input['cliente_id'], PDO::PARAM_STR);

        try {
            $stmt->execute();
            $id = $conn->lastInsertId();
            foreach ($input['itens'] as $itens) {
                $stmt = $conn->prepare('INSERT INTO pedidos_produtos VALUES (:pedido_id, :produto_id, :produto_qtd)');
                $stmt->bindParam(':pedido_id', $id, PDO::PARAM_STR);
                $stmt->bindParam(':produto_id', $itens['produto_id'], PDO::PARAM_STR);
                $stmt->bindParam(':produto_qtd', $itens['produto_qtd'], PDO::PARAM_STR);
                $stmt->execute();
            }

            return $id;
        } catch (PDOException $e) {
            throw new Exception ($e);
        }

        return '';
    }

    public function fetchById ($id) {
        $stm = $this->getConnection()->prepare('SELECT * FROM pedidos p 
        LEFT JOIN pedidos_produtos pp ON (p.pedido_id = pp.pedido_id)
        LEFT JOIN produtos pro ON (pro.produto_id = pp.produto_id)
        WHERE p.pedido_id = :id
        ');
        $stm->bindParam(':id', $id, PDO::PARAM_STR);
        $stm->execute();
        if ($stm->rowCount() > 0) {
            $produtos = $stm->fetchAll(PDO::FETCH_ASSOC);
            $pedidosAgrupados = array();

            foreach ($produtos as $produto) {
                $pedidoId = $produto['pedido_id'];
        
                // Se este pedido ainda não estiver no array de pedidos agrupados, adicioná-lo
                if (!isset($pedidosAgrupados[$pedidoId])) {
                    $pedidosAgrupados[$pedidoId] = array(
                        'PEDIDO_ID' => $pedidoId,
                        'CLIENTE_ID' => $produto['cliente_id'],
                        'ITENS' => array()
                    );
                }
        
                // Adicionar o item ao array de itens do pedido
                $pedidosAgrupados[$pedidoId]['ITENS'][] = array(
                    'PRODUTO_ID' => $produto['produto_id'],
                    'VLRUNIT' => $produto['produto_valor_unid'],
                    'QUANTIDADE' => $produto['produto_qtd']
                );
            }
        
            // Converter o array de pedidos agrupados em JSON e retornar
            return $pedidosAgrupados;
        } else {
            return array();
        }
    }

    public function update ($id, $input) {
        $conn = $this->getConnection();
        
        try {
            $stmt = $conn->prepare('UPDATE pedidos SET cliente_id = :cliente_id WHERE pedido_id = :id');
            $stmt->bindParam(':cliente_id', $input['cliente_id'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
    
            $stmt = $conn->prepare('DELETE FROM pedidos_produtos WHERE pedido_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
    
            foreach ($input['itens'] as $item) {
                $stmt = $conn->prepare("INSERT INTO pedidos_produtos VALUES (:pedido_id, :produto_id, :produto_qtd)");
                $stmt->bindParam(':pedido_id', $id, PDO::PARAM_STR);
                $stmt->bindParam(':produto_id', $item['PRODUTO_ID']);
                $stmt->bindParam(':produto_qtd', $item['QUANTIDADE']);
                $stmt->execute();
            }
    
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete ($id) {
        $conn = $this->getConnection();

        try {
            $stmt = $conn->prepare('DELETE FROM pedidos WHERE pedido_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
    
            $stmt = $conn->prepare('DELETE FROM pedidos_produtos WHERE pedido_id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}