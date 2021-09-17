<?php

/*-- Processa as informações recebidas --*/
if (isset($_GET['acao'])) {
    if ($_GET['acao'] == "salvar") {
        if ($_POST['enviar-professor']) {
            $professor = new Professor();

            $professor->setProfessor(
                $_POST['codigo_professor'],
                $_POST['nome-professor']
            );

            if ($professor->salvar()) {
                $msg['msg'] = "Registro Salvo com sucesso!";
                $msg['class'] = "success";
            } else {
                $msg['msg'] = "Falha ao salvar Registro!";
                $msg['class'] = "success";
            }
            $_SESSION['msgs'][] = $msg;
            unset($professor);
            header("location: ?pagina=$pagina");
        }
    } else if ($_GET['acao'] == "excluir") {
        if (isset($_GET['codigo'])) {
            if (Professor::excluir($_GET['codigo'])) {
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
            $professor = Professor::getProfessor($_GET['codigo']);
        }
    }
}

if (!isset($professor)) {
    $professor = new Professor();
    $professor->setProfessor(null, null);
}

/*-- Exibe as mensagens --*/
if (isset($_SESSION['msgs'])) {

    foreach ($_SESSION['msgs'] as $msg)
        echo "<div class=' all-msgs alert alert-{$msg['class']}'>{$msg['msg']}</div>";

    echo "<script defer> 
            setTimeout(
            function(){
                document.querySelector('.all-msgs').style='display:none; ';
            }
            ,
            3000
            );
            </script>";

    unset($_SESSION['msgs']);
}
?>

<!-- Exibe o formulário -->
<div class="container-fluid">
    <h2>Cadastrar Professores</<h2>
        <form name="form-professor" method="POST" action="?pagina=<?php print $pagina ?>&acao=salvar">
            <input type="hidden" name="codigo_professor" value="<?php echo $professor->getCodigo() ?>" />
            <div class="input-group mb-2">
                <span class="input-group-text">Nome do Professor:</span>
                <input type="text" class="form-control" id="nome-professor" name="nome-professor" value="<?php echo $professor->getNome() ?>">
            </div>
            <input type="submit" class="btn btn-primary" name="enviar-professor" value="Enviar" />
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
                            <th class='column2'>Nome do Professor</th>
                            <th class='column4'></th>
                            <th class='column5'></th>
                            <th class='column6'></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Chama a função listar da classe Professor.php -->
                        <?php
                        $professores = Professor::listar();
                        foreach ($professores as $professor) {
                            echo "
                    <tr>
                        <td class='column1'>{$professor->getCodigo()}</td>
                        <td class='column2'>{$professor->getNome()}</td>
                        <td class='column4'></td>
                        <td class='column5'>
                            <span class='badge rounded-pill bg-primary'>
                                <a href='?pagina={$pagina}&acao=editar&codigo={$professor->getCodigo()}' style='color:#fff'><i class='bi bi-pencil-square'></i></a>
                            </span>
                            <span class='badge rounded-pill bg-danger'>
                                <a href='?pagina={$pagina}&acao=excluir&codigo={$professor->getCodigo()}'style='color:#fff'><i class='bi bi-trash'></i></a>
                            </span>
                        </td>
                        <td class='column6'>
                    </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>