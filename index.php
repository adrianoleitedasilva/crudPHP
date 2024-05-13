<?php include('header.php') ?>
<?php include('dbcon.php') ?>

    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
        <h1 class="display-5 fw-bold">Conheça o Sistema</h1>
        <p class="col-md-12 fs-8">O Sistema de Cadastro de Alunos é uma plataforma intuitiva e eficiente projetada para gerenciar o registro de alunos em uma instituição educacional. Com uma interface amigável e recursos robustos, este sistema simplifica o processo de cadastro, edição e exclusão de informações dos alunos, oferecendo uma experiência de usuário otimizada.</p>
        <button class="btn btn-success"><i class="fa-solid fa-plus"></i> Adicionar aluno</button>
    </div>    

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Sobrenome</th>
                    <th>Idade</th>
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
                    </tr>
                    <?php 
                }
            }

            ?>

    
                
            </tbody>
        </table>
    </div>
    
    
    <?php include('footer.php') ?>