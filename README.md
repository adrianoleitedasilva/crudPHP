# crudPHP


## Update

Explicando o código:

```php
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
```

- ***if(isset($_GET['id'])){**: Esta linha verifica se existe um parâmetro na URL chamado 'id'. Isso é feito usando a função isset() do PHP, que verifica se uma variável ou índice de matriz está definido.
- ***$id = $_GET['id'];***: Se o parâmetro 'id' existir na URL, esta linha atribui seu valor à variável $id.
- ***$query = "select * from alunoswhereid = '$id'";**: Esta linha cria uma consulta SQL para selecionar todas as colunas da tabela 'alunos' onde o campo 'id' é igual ao valor da variável $id. É importante notar que essa consulta é vulnerável a injeção de SQL. Seria mais seguro usar prepared statements para evitar esse tipo de vulnerabilidade.
- ***$result = mysqli_query($connection, $query);***: Esta linha executa a consulta SQL no banco de dados. mysqli_query() é uma função do PHP que executa consultas SQL em um banco de dados MySQL. O primeiro argumento é a conexão com o banco de dados, e o segundo é a consulta SQL.
- ***if(!$result){***: Esta linha verifica se houve algum erro ao executar a consulta SQL.
- ***die("Deu ruim".mysqli_error());***: Se houver um erro ao executar a consulta SQL, esta linha encerra o script PHP e exibe uma mensagem de erro que inclui a descrição do erro retornado pela função mysqli_error(). die() é uma função do PHP que termina a execução do script.
- ***} else {***: Se a consulta SQL for executada com sucesso, o código dentro deste bloco é executado.
- ***$row = mysqli_fetch_assoc($result);***: Esta linha recupera a próxima linha do resultado da consulta SQL como uma matriz associativa e a atribui à variável $row. Se não houver mais linhas para recuperar, mysqli_fetch_assoc() retornará NULL.
- ***// print_r($row);***: Esta linha é um comentário que, se descomentado, imprimirá o conteúdo da variável $row, que contém os dados do aluno recuperados do banco de dados. Este comando é útil para depurar e entender a estrutura dos dados recuperados.

Agora partindo para a próxima parte do código:

```php
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
```

- ***if(isset($_POST['atualizarAluno'])){***: Esta linha verifica se o formulário para atualizar informações de um aluno foi enviado. Isso é feito verificando se existe uma variável chamada 'atualizarAluno' no array $_POST. Se essa variável estiver presente, significa que o formulário foi submetido.
- ***if(isset($_GET['id_new'])){***: Esta linha verifica se existe um parâmetro na URL chamado 'id_new'. Se este parâmetro estiver presente na URL, ele é capturado e armazenado na variável $idnew após passar pela função mysqli_real_escape_string(). Isso ajuda a proteger contra injeção de SQL ao escapar caracteres especiais.
- ***Sanitize e validar dados de entrada antes de usar na consulta***:
  - **$nome, $sobrenome, $idade**: Estas linhas usam a função **mysqli_real_escape_string()** para escapar os valores dos campos de formulário nome, sobrenome e idade enviados via método POST. Isso é uma medida de segurança para evitar injeção de SQL.
- ***Use prepared statements para prevenir Injeção de SQL***:
    - ***$query = "UPDATE alunosSETnome= ?,sobrenome= ?,idade= ? WHEREid = ?";***: Esta linha define a consulta SQL para atualizar os dados do aluno na tabela 'alunos'. Ela usa placeholders (?) para os valores que serão inseridos na consulta. Isso ajuda a prevenir injeção de SQL.
- ***$stmt = mysqli_prepare($connection, $query);***: Esta linha prepara a consulta SQL para execução.
- ***mysqli_stmt_bind_param($stmt, "sssi", $nome, $sobrenome, $idade, $idnew);***: Esta linha associa os valores aos placeholders na consulta preparada. Os tipos de dados dos valores são especificados pelo segundo argumento ("sssi" neste caso), que corresponde aos tipos de dados dos valores (string, string, string, inteiro).
- ***if(!mysqli_stmt_execute($stmt)){***: Esta linha executa a consulta preparada e verifica se ocorreu algum erro durante a execução. Se houver um erro, o script morre e exibe uma mensagem de erro.
- ***Se a consulta for bem-sucedida, o usuário é redirecionado para a página inicial (index.php) com uma mensagem de sucesso***:
    - ***header('Location: index.php?update_msg=Dados atualizados com sucesso!');***: Esta linha redireciona o usuário para a página 'index.php' e adiciona um parâmetro na URL chamado 'update_msg' com a mensagem "Dados atualizados com sucesso!".
  - 

### SQL Injection

A injeção de SQL (SQL injection, em inglês) é uma técnica de ataque utilizada por hackers para explorar vulnerabilidades em sistemas que interagem com bancos de dados por meio de consultas SQL. A exploração ocorre quando entradas de usuários não são devidamente validadas ou escapadas, permitindo que um invasor insira código SQL malicioso em campos de entrada de um formulário, parâmetros de URL, ou qualquer outra forma de entrada de dados que seja utilizada para construir consultas SQL.

Essa técnica pode ser usada para realizar várias ações maliciosas, incluindo:
- **Extrair informações sensíveis**: Um invasor pode usar a injeção de SQL para recuperar dados sensíveis armazenados no banco de dados, como senhas, informações de cartão de crédito, detalhes de contas de usuário, entre outros.
- **Modificar dados no banco de dados**: Um atacante pode executar instruções SQL maliciosas para modificar, adicionar ou excluir registros no banco de dados. Isso pode resultar em perda de dados, corrupção ou manipulação de informações.
- **Executar comandos no sistema operacional**: Dependendo das permissões do usuário conectado ao banco de dados, um invasor pode conseguir executar comandos no sistema operacional hospedeiro. Isso pode levar a uma série de ações prejudiciais, como instalação de malware, modificação ou exclusão de arquivos, entre outros.

A injeção de SQL pode ser devastadora para a segurança de um sistema, pois permite que um invasor execute ações não autorizadas e potencialmente danosas no banco de dados e no sistema subjacente.

**Para prevenir a injeção de SQL, é fundamental seguir boas práticas de segurança, como**:
- **Usar consultas parametrizadas ou prepared statements**: Utilizar consultas parametrizadas ou prepared statements (dependendo da linguagem de programação utilizada) ajuda a separar os dados do código SQL, tornando mais difícil para um invasor injetar código malicioso.
- **Validar e escapar entradas de usuário**: Sempre validar e escapar entradas de usuário antes de incorporá-las em consultas SQL. Isso pode incluir sanitização de entrada, verificação de tipos de dados e utilização de funções específicas de escape oferecidas pela linguagem de programação ou pelo banco de dados.
- **Princípio do menor privilégio**: Limitar as permissões de acesso do usuário ao banco de dados. Garanta que os usuários tenham apenas as permissões necessárias para realizar suas tarefas, reduzindo assim o impacto de um possível ataque de injeção de SQL.

A prevenção eficaz da injeção de SQL é essencial para manter a segurança dos sistemas que interagem com bancos de dados e proteger as informações sensíveis dos usuários.