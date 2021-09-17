<?php

class Professor {
    private $codigo;
    private $nome;
        
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
    }
    
    /**
     * setProfessor
     *
     * @param  mixed $codigo
     * @param  mixed $nome
     * @return void
     */
    public function setProfessor($codigo, $nome) {
        $this->codigo = $codigo;
        $this->nome = $nome;
    }
    
    /**
     * getCodigo
     *
     * @return void
     */
    public function getCodigo() {
        return $this->codigo;
    }
    
    /**
     * getNome
     *
     * @return void
     */
    public function getNome() {
        return $this->nome;
    }
    
    /**
     * salvar
     *
     * @return void
     */
    public function salvar() {
        try {
            $db = Database::conexao();
            if (empty($this->codigo)) {
                $stm = $db->prepare("INSERT INTO professor (nome) VALUES (:nome)");
                $stm->execute(array(":nome" => $this->getNome()));
            } else {
                $stm = $db->prepare("UPDATE professor SET nome=:nome WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":codigo" => $this->codigo));
            }
            #ppegar o id do registro no banco de dados
            #setar o id do objeto
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage() . "<br>";
            return false;
        }
        return true;
    }
    
    /**
     * listar
     *
     * @return void
     */
    public static function listar() {
        $db = Database::conexao();
        $professores = null;
        $retorno = $db->query("SELECT * FROM professor");
        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $professor = new Professor();
            $professor->setProfessor($item['codigo'], $item['nome']);

            $professores[] = $professor;
        }

        return $professores;
    }

    
    /**
     * getProfessor
     *
     * @param  mixed $codigo
     * @return void
     */
    public static function getProfessor($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM professor WHERE codigo= $codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $professor = new Professor();
            $professor->setProfessor($item['codigo'], $item['nome']);
            return $professor;
        }
        return false;
    }
    
    /**
     * excluir
     *
     * @param  mixed $codigo
     * @return void
     */
    public static function excluir($codigo) {
        $db = Database::conexao();
        $professor = null;
        if ($db->query("DELETE FROM professor WHERE codigo=$codigo")) {
            return true;
        }
        return false;
    }
}
