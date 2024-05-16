<?php
include 'dbcon.php';

function isFormValid($data) {
    return isset($data['nome'], $data['sobrenome'], $data['idade']) &&
           !empty(trim($data['nome'])) &&
           !empty(trim($data['sobrenome'])) &&
           !empty(trim($data['idade']));
}

function insertAluno($connection, $nome, $sobrenome, $idade) {
    $query = "INSERT INTO `alunos` (`nome`, `sobrenome`, `idade`) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        die("Preparation failed: " . mysqli_error($connection));
    }
    mysqli_stmt_bind_param($stmt, "ssi", $nome, $sobrenome, $idade);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

if (isset($_POST['adicionarAluno'])) {
    if (!isFormValid($_POST)) {
        header('Location: index.php?message=Favor preencher todos os campos!');
        exit;
    }

    $nome = trim($_POST['nome']);
    $sobrenome = trim($_POST['sobrenome']);
    $idade = trim($_POST['idade']);

    if (insertAluno($connection, $nome, $sobrenome, $idade)) {
        header('Location: index.php?insert_message=Aluno registrado com sucesso');
    } else {
        die("Insertion failed: " . mysqli_error($connection));
    }
}

?>