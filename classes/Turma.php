<?php

/**
 * Turma
 */
class Turma {
    private $codigo;
    private $curso;
    private $nome;
    private $professor;
    
    /**
     * setTurma
     *
     * @param  mixed $codigo
     * @param  mixed $curso
     * @param  mixed $nome
     * @param  mixed $professor
     * @return void
     */
    public function setTurma($codigo, $curso, $nome, $professor) {
        $this->codigo = $codigo;
        $this->curso = $curso;
        $this->nome = $nome;
        $this->professor = $professor;
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
     * getCurso
     *
     * @return void
     */
    public function getCurso() {
        return $this->curso;
    }
    public function getProfessor() {
        return $this->professor;
    }
    
    /**
     * listar
     *
     * @return void
     */
    public static function listar() {
        $db = Database::conexao();
        $turmas = null;
        $retorno = $db->query("SELECT * FROM turma");

        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $professor = Professor::getProfessor($item['professor_codigo']);
            $turma = new Turma();
            $turma->setTurma($item['codigo'], $item['curso'], $item['nome'], $professor);

            $turmas[] = $turma;
        }

        return $turmas;
    }
    
    /**
     * excluir
     *
     * @param  mixed $codigo
     * @return void
     */
    public static function excluir($codigo) {
        $db = Database::conexao();
        $turmas = null;
        if ($db->query("DELETE FROM turma WHERE codigo=$codigo")) {
            return true;
        }
        return false;
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
                $stm = $db->prepare("INSERT INTO turma (nome, curso, professor_codigo) VALUES (:nome,:curso,:professor)");
                $stm->execute(array(":nome" => $this->getNome(), ":curso" => $this->getCurso(), ":professor" => $this->getProfessor()->getCodigo()));
            } else {
                $stm = $db->prepare("UPDATE turma SET nome=:nome,curso=:curso,professor_codigo=:professor_codigo WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":curso" => $this->curso, ":professor_codigo" => $this->professor->getCodigo(), ":codigo" => $this->codigo));
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
     * getTurma
     *
     * @param  mixed $codigo
     * @return void
     */
    public static function getTurma($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM turma WHERE codigo=$codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $professor = Professor::getProfessor($item['professor_codigo']);
            $turma = new Turma();
            $turma->setTurma($item['codigo'], $item['curso'], $item['nome'], $professor);
            return $turma;
        }
        return false;
    }
}
