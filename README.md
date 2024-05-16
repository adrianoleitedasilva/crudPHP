# crudPHP

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white) ![MySQL](https://img.shields.io/badge/mysql-4479A1.svg?style=for-the-badge&logo=mysql&logoColor=white) ![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?style=for-the-badge&logo=html5&logoColor=white) ![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?style=for-the-badge&logo=css3&logoColor=white) ![Bootstrap](https://img.shields.io/badge/bootstrap-%238511FA.svg?style=for-the-badge&logo=bootstrap&logoColor=white)


## Cadastrar

```php
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
```

- **Função isFormValid**: Esta função verifica se os dados do formulário são válidos.
- **Verifica se os campos 'nome', 'sobrenome' e 'idade'** estão definidos no array $data.
- **Verifica se nenhum desses campos está vazio após serem removidos os espaços em branco**.
- **Função insertAluno**: Esta função insere um novo aluno no banco de dados.
- **Prepara uma declaração SQL** para inserir os dados do aluno na tabela 'alunos'.
- **Liga as variáveis** aos marcadores de posição na declaração SQL.
  - Executa a declaração preparada.
  - Retorna true se a inserção for bem-sucedida e false caso contrário.
- **Verificação do envio do formulário**: Verifica se o formulário foi enviado (provavelmente através do método POST) com o botão 'adicionarAluno'.
- **Se o formulário não foi preenchido corretamente** (de acordo com a função isFormValid), redireciona de volta para a página inicial com uma mensagem de erro.
  - Se os dados do formulário forem válidos, extrai os valores dos campos 'nome', 'sobrenome' e 'idade'.
  - Chama a função insertAluno para inserir os dados do aluno no banco de dados.
  - Se a inserção for bem-sucedida, redireciona de volta para a página inicial com uma mensagem de confirmação.
  - Se a inserção falhar, exibe uma mensagem de erro.

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

# Deletando dados

```php
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
```

- **Função deleteStudentById**: Esta função é definida para excluir um aluno do banco de dados com base no ID fornecido.
- **mysqli_prepare**: Prepara uma declaração SQL para execução.
- **mysqli_stmt_bind_param**: Liga variáveis a um statement SQL para execução.
- **mysqli_stmt_execute**: Executa uma declaração preparada.
- **mysqli_stmt_close**: Fecha uma declaração preparada.
- **A função retorna true se a exclusão for bem-sucedida** e false caso contrário.
- **Verificação do parâmetro GET 'id'**: Verifica se foi passado um parâmetro 'id' via GET na URL.
- **Se não houver um ID fornecido**, o script não faz nada.
- **Validação do ID**: Verifica se o ID fornecido é um número inteiro válido.
- **Se o ID não for um número inteiro válido**, o script termina a execução e exibe "ID inválido".
- **Exclusão do aluno**: Chama a função deleteStudentById com o ID fornecido.
  - Se a exclusão for bem-sucedida, redireciona para 'index.php' com uma mensagem de confirmação.
  - Se a exclusão falhar, exibe "Erro ao excluir registro".
