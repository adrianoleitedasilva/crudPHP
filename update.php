<?php include('header.php') ?>
<?php include('dbcon.php') ?>

    <?php 
        // Recupera as informações do aluno
        if(isset($_GET['id'])){
            $id = $_GET['id'];
        
            $query = "select * from `alunos` where `id` = '$id'";
            $result = mysqli_query($connection, $query);

            if(!$result){
                die("Deu ruim".mysqli_error());
            } else {
                $row = mysqli_fetch_assoc($result);
                // print_r($row);
            }
        }
    ?>

    <?php
    // Atualiza as informações do aluno
    if(isset($_POST['atualizarAluno'])){
        if(isset($_GET['id_new'])){
            $idnew = mysqli_real_escape_string($connection, $_GET['id_new']);
        }
        // Sanitize and validate input data before using in the query
        $nome = mysqli_real_escape_string($connection, $_POST['nome']);
        $sobrenome = mysqli_real_escape_string($connection, $_POST['sobrenome']);
        $idade = mysqli_real_escape_string($connection, $_POST['idade']);
    
        // Use prepared statements to prevent SQL Injection
        $query = "UPDATE `alunos` SET `nome` = ?, `sobrenome` = ?, `idade` = ? WHERE `id` = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "sssi", $nome, $sobrenome, $idade, $idnew);
    
        if(!mysqli_stmt_execute($stmt)){
            die("Query failed: " . mysqli_error($connection));
        } else {
            header('Location: index.php?update_msg=Dados atualizados com sucesso!');
        }
    }
    ?>

    <br>
    <h2>Atualizando cadastro</h2>
    <form action="update.php?id_new=<?php echo $id ; ?>" method="post">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" name="nome" class="form-control" value="<?php echo $row['nome']; ?>"><br>
        </div>
                    
        <div class="form-group">
            <label for="sobrenome">Sobrenome</label>
            <input type="text" name="sobrenome" class="form-control" value="<?php echo $row['sobrenome']; ?>"><br>
        </div>
                    
        <div class="form-group">
            <label for="idade">Idade</label>
            <input type="number" name="idade" class="form-control" value="<?php echo $row['idade']; ?>"><br>
        </div>

        <a href="index.php" class="btn btn-outline-danger"><i class="fa-solid fa-door-open"></i> Cancelar</a>
        <input type="submit" class="btn btn-success" name="atualizarAluno" value="Salvar e Atualizar">
    </form>

<?php include('footer.php') ?>