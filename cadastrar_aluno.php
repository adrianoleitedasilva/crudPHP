<?php

include 'dbcon.php';

if(isset($_POST['adicionarAluno'])) {
    // echo "pressionado";

    // Criando as variáveis que vão receber os dados do formulário e gravar no BD
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $idade = $_POST['idade'];

    if($nome == "" || empty($nome) || $sobrenome == "" || empty($sobrenome) || $idade == "" || empty($idade)){
        header('location:index.php?message=Favor preencher todos os campos!');
    } else {
        $query = "insert into `alunos` (`nome`, `sobrenome`, `idade`) values ('$nome', '$sobrenome', '$idade')";
        $result = mysqli_query($connection, $query);

        if(!$result){
            die("Deu ruim".mysqli_error());
        } else {
            header('location:index.php?insert_message=Aluno registrado com sucesso');
        }
    }

}