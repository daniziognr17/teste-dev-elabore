<?php 
require_once __DIR__.'/Database.php';

class ProdutoModel extends Database {
    public function fetch () {
        $stm = $this->getConnection()->query('SELECT * FROM produtos');
        if ($stm->rowCount() > 0) {
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function create ($input) {
        $conn = $this->getConnection();
        $stmt = $conn->prepare('INSERT INTO produtos (produto_nome, produto_valor_unid) VALUES (:nome, :val)');
        $stmt->bindParam(':nome', $input['produto_nome'], PDO::PARAM_STR);
        $stmt->bindParam(':val', $input['produto_valor_unid'], PDO::PARAM_STR);

        try {
            $stmt->execute();
            $id = $conn->lastInsertId();
            return $id;
        } catch (PDOException $e) {
            throw new Exception ($e);
        }

        return '';
    }

    public function fetchById ($id) {
        $conn = $this->getConnection();
        $stmt = $conn->prepare('SELECT * FROM produtos WHERE produto_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update ($id, $input) {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("UPDATE produtos SET produto_nome = ?, produto_valor_unid = ? WHERE produto_id = ?");

        try {
            $stmt->execute([$input['produto_nome'], $input['produto_valor_unid'], $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete ($id) {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("DELETE FROM produtos WHERE produto_id = ?");
        
        try {
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}