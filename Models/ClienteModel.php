<?php 
require_once __DIR__.'/Database.php';

class ClienteModel extends Database {
    public function fetch () {
        $stm = $this->getConnection()->query('SELECT * FROM clientes');
        if ($stm->rowCount() > 0) {
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

    public function create ($input) {
        $conn = $this->getConnection();
        $stmt = $conn->prepare('INSERT INTO clientes (cliente_nome, cliente_cidade) VALUES (:nome, :cidade)');
        $stmt->bindParam(':nome', $input['cliente_nome'], PDO::PARAM_STR);
        $stmt->bindParam(':cidade', $input['cliente_cidade'], PDO::PARAM_STR);

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
        $stmt = $conn->prepare('SELECT * FROM clientes WHERE cliente_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update ($id, $input) {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("UPDATE clientes SET cliente_nome = ?, cliente_cidade = ? WHERE cliente_id = ?");

        try {
            $stmt->execute([$input['cliente_nome'], $input['cliente_cidade'], $id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete ($id) {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("DELETE FROM clientes WHERE cliente_id = ?");
        
        try {
            $stmt->execute([$id]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}