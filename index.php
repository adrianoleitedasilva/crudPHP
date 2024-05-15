<?php include('header.php') ?>
<?php include('dbcon.php') ?>

    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
        <h1 class="display-5 fw-bold">Conheça o Sistema</h1>
        <p class="col-md-12 fs-8">O Sistema de Cadastro de Alunos é uma plataforma intuitiva e eficiente projetada para gerenciar o registro de alunos em uma instituição educacional. Com uma interface amigável e recursos robustos, este sistema simplifica o processo de cadastro, edição e exclusão de informações dos alunos, oferecendo uma experiência de usuário otimizada.</p>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-plus"></i> Adicionar aluno</button>    
    </div>    

    <?php
        if(isset($_GET['message'])){
            echo '<div class="alert alert-danger" role="alert">' . $_GET['message']. '</div>';
        }
    ?>

    <?php
        if(isset($_GET['insert_message'])){
            echo '<div class="alert alert-success" role="alert">' . $_GET['insert_message']. '</div>';
        }
    ?>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Sobrenome</th>
                    <th>Idade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            
            <tbody>
            <?php 
            
            $query = "select * from `alunos`";

            $result = mysqli_query($connection, $query);

            if (!$result){
                die("Não foi possível concluir a consulta!".mysqli_error());
            } else {
                // print_r($result);
                while($row = mysqli_fetch_assoc($result)){
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo $row['sobrenome']; ?></td>
                        <td><?php echo $row['idade']; ?></td>
                        <td>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Deletar</a>
                            <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                        </td>
                    </tr>
                    <?php 
                }
            }

            ?>
                
            </tbody>
        </table>


 
    <form action="cadastrar_aluno.php" method="post">
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="fa-solid fa-person-circle-plus"></i> Cadastrar novo aluno</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Insira as informações do aluno:</p>

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" class="form-control" placeholder="Nome"><br>
                    </div>
                    
                    <div class="form-group">
                        <label for="sobrenome">Sobrenome</label>
                        <input type="text" name="sobrenome" class="form-control" placeholder="Sobrenome"><br>
                    </div>
                    
                    <div class="form-group">
                        <label for="idade">Idade</label>
                        <input type="number" name="idade" class="form-control" placeholder="Idade"><br>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"><i class="fa-solid fa-door-open"></i> Cancelar</button>
                <input type="submit" class="btn btn-success" name="adicionarAluno" value="Salvar e Criar">
            </div>
            </div>
        </div>
        </div>
    </form>

    <?php include('footer.php') ?>