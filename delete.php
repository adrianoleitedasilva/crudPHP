<?php
include('dbcon.php');

function deleteStudentById($connection, $id) {
    $query = "DELETE FROM alunos WHERE id = ?";
    $stmt = mysqli_prepare($connection, $query);
    
    if (!$stmt) {
        die("Falha na preparação: " . mysqli_error($connection));
    }
    
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    
    if (!$success) {
        die("Deu ruim: " . mysqli_error($connection));
    }
    
    mysqli_stmt_close($stmt);
    return $success;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if (filter_var($id, FILTER_VALIDATE_INT) === false) {
        die("ID inválido");
    }
    
    $wasDeleted = deleteStudentById($connection, $id);
    
    if ($wasDeleted) {
        header('location:index.php?delete_msg=Registro removido da base de dados!');
    } else {
        die("Erro ao deletar o registro");
    }
}
?>