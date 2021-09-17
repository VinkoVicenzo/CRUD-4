<?php

/*-- Processa as informações recebidas --*/
if (isset($_GET['acao'])) {
    if ($_GET['acao'] == "salvar") {
        if ($_POST['enviar-aluno']) {
            $turma = new Turma();
            $turma->setTurma($_POST['codigo-turma-aluno'], null, null, null);
            $aluno = new Aluno();

            $aluno->setAluno(
                $_POST['codigo_aluno'],
                $_POST['nome-aluno'],
                $_POST['matricula-aluno'],
                $turma
            );

            if ($aluno->salvar()) {
                $msg['msg'] = "Registro Salvo com sucesso!";
                $msg['class'] = "success";
            } else {
                $msg['msg'] = "Falha ao salvar Registro!";
                $msg['class'] = "success";
            }
            $_SESSION['msgs'][] = $msg;
            unset($aluno);
            header("location: ?pagina=$pagina");
        }
    } else if ($_GET['acao'] == "excluir") {
        if (isset($_GET['codigo'])) {
            if (Aluno::excluir($_GET['codigo'])) {
                $msg['msg'] = "Registro excluido com sucesso!";
                $msg['class'] = "success";
            } else {
                $msg['msg'] = "Falha ao excluir Registro!";
                $msg['class'] = "danger";
            }
            $_SESSION['msgs'][] = $msg;
        }
        header("location: ?pagina=$pagina");
    } else if ($_GET['acao'] == "editar") {
        if (isset($_GET['codigo'])) {
            $aluno = Aluno::getAluno($_GET['codigo']);
        }
    }
}

if (!isset($aluno)) {
    $aluno = new Aluno();
    $aluno->setAluno(null, null, null, new Turma());
}

/*-- Exibe as Mensagens --*/
if (isset($_SESSION['msgs'])) {

    foreach ($_SESSION['msgs'] as $msg)
        echo "<div class=' all-msgs alert alert-{$msg['class']}'>{$msg['msg']}</div>";

    echo
    "<script defer> 
            setTimeout(
            function() {
                document.querySelector('.all-msgs').style='display:none';
            }        
            ,
            3000);
            </script>";

    unset($_SESSION['msgs']);
}

?>
<!-- Exibe o formulário -->
<div class="container-fluid">
    <h2> Cadastro de Alunos</h2>
    <form name="form-aluno" method="POST" action="?pagina=<?php print $pagina ?>&acao=salvar">
        <input type="hidden" name="codigo_aluno" value="<?php echo $aluno->getCodigo() ?>" />
        <div class="input-group mb-2">
            <span class="input-group-text">Nome do Aluno:</span>
            <input type="text" class="form-control" id="nome-aluno" name="nome-aluno" value="<?php echo $aluno->getNome() ?>">
        </div>
        <div class="input-group mb-2">
            <span class="input-group-text">Matrícula:</span>
            <input type="number" class="form-control" id="matricula-aluno" name="matricula-aluno" value="<?php echo $aluno->getMatricula() ?>">
        </div>

        <div class="input-group mb-2 mb-2">
            <label class="input-group-text" for="inputGroupTurma">Turma</label>
            <select class="form-select" name="codigo-turma-aluno">
                <option value="<?php echo $aluno->getTurma()->getCodigo()  ?>"><?php echo $aluno->getTurma()->getNome() ?></option>
                <?php
                $turma = new Turma();
                $turmas = Turma::listar();

                if ($turmas) {
                    foreach ($turmas as $item) {
                        echo "<option value='{$item->getCodigo()}'>{$item->getNome()}</option>";
                    }
                }
                ?>
            </select>
        </div>
        <input type="submit" class="btn btn-primary" name="enviar-aluno" value="Enviar" />

    </form>
</div>
<!-- Parte superior da tabela -->
<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100">
            <div class="table100">
                <table>
                    <thead>
                        <tr class='table100-head'>
                            <th class='column1'>#</th>
                            <th class='column2'>Aluno</th>
                            <th class='column4'>Matricula</th>
                            <th class='column5'>Turma</th>
                            <th class='column6'>Professor</th>
                            <th class='column7'></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Chama a função listar da classe Professor.php -->
                        <?php

                        $alunos = Aluno::listar();
                        foreach ($alunos as $item) {
                            echo "
                <tr>
                    <td class='column1'>{$item->getCodigo()}</td>
                    <td class='column2'>{$item->getNome()}</td>
                    <td class='column4'>{$item->getMatricula()}</td>
                    <td class='column5'>{$item->getTurma()->getNome()}</td>
                    <td class='column6'>{$item->getTurma()->getProfessor()->getNome()}</td>
                    <td class='column7'>
                        <span class='badge rounded-pill bg-primary'>
                            <a href='?pagina={$pagina}&acao=editar&codigo={$item->getCodigo()}' style='color:#fff'><i class='bi bi-pencil-square'></i></a>
                        </span>
                        <span class='badge rounded-pill bg-danger'>
                            <a href='?pagina={$pagina}&acao=excluir&codigo={$item->getCodigo()}'style='color:#fff'><i class='bi bi-trash'></i></a>
                        </span>
                    </td>
                </tr>";
                        }
                        ?>