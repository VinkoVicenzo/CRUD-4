<?php

/**
 * Aluno
 */
class Aluno {    
    /**
     * codigo
     *
     * @var mixed
     */
    private $codigo;    
    /**
     * nome
     *
     * @var mixed
     */
    private $nome;    
    /**
     * matricula
     *
     * @var mixed
     */
    private $matricula;    
    /**
     * turma
     *
     * @var mixed
     */
    private $turma;
    
    /**
     * setAluno
     *
     * @param  mixed $codigo
     * @param  mixed $nome
     * @param  mixed $matricula
     * @param  mixed $turma
     * @return void
     */
    public function setAluno($codigo, $nome, $matricula, $turma) {
        $this->codigo = $codigo;
        $this->nome = $nome;
        $this->matricula = $matricula;
        $this->turma = $turma;
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
     * getMatricula
     *
     * @return void
     */
    public function getMatricula() {
        return $this->matricula;
    }

    public function getTurma() {
        return $this->turma;
    }
    
    /**
     * listar
     *
     * @return void
     */
    public static function listar() {
        $db = Database::conexao();
        $alunos = null;
        $retorno = $db->query("SELECT * FROM aluno");

        while ($item = $retorno->fetch(PDO::FETCH_ASSOC)) {
            $turma = Turma::getTurma($item['turma_codigo']);
            $aluno = new Aluno();
            $aluno->setAluno($item['codigo'], $item['nome'], $item['matricula'], $turma);

            $alunos[] = $aluno;
        }

        return $alunos;
    }
    
    /**
     * excluir
     *
     * @param  mixed $codigo
     * @return void
     */
    public static function excluir($codigo) {
        $db = Database::conexao();
        if ($db->query("DELETE FROM aluno WHERE codigo=$codigo")) {
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
                $stm = $db->prepare("INSERT INTO aluno (nome, matricula, turma_codigo) VALUES (:nome,:matricula,:turma)");
                $stm->execute(array(":nome" => $this->getNome(), ":matricula" => $this->getMatricula(), ":turma" => $this->getTurma()->getCodigo()));
            } else {
                $stm = $db->prepare("UPDATE aluno SET nome=:nome,matricula=:matricula,turma_codigo=:turma_codigo WHERE codigo=:codigo");
                $stm->execute(array(":nome" => $this->nome, ":matricula" => $this->matricula, ":turma_codigo" => $this->turma->getCodigo(), ":codigo" => $this->codigo));
            }
            #pegar o id do registro no banco de dados
            #setar o id do objeto
            return true;
        } catch (Exception $ex) {
            echo $ex->getMessage() . "<br>";
            return false;
        }
        return true;
    }
    
    /**
     * getAluno
     *
     * @param  mixed $codigo
     * @return void
     */
    public static function getAluno($codigo) {
        $db = Database::conexao();
        $retorno = $db->query("SELECT * FROM aluno WHERE codigo=$codigo");
        if ($retorno) {
            $item = $retorno->fetch(PDO::FETCH_ASSOC);
            $turma = Turma::getTurma($item['turma_codigo']);
            $aluno = new Aluno();
            $aluno->setAluno($item['codigo'], $item['nome'], $item['matricula'], $turma);
            return $aluno;
        }
        return false;
    }
}
