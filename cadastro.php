<?php
$conn = new mysqli("localhost", "root", "", "agenda");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

function validaCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);
    if (strlen($cpf) != 11) {
        return false;
    }
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

function listarContatos($conn) {
    $sql = "SELECT * FROM contato";
    return $conn->query($sql);
}

function listarTelefones($conn) {
    $sql = "SELECT t.*, c.nome_completo FROM telefones t JOIN contatos c ON t.contato_id = c.id";
    return $conn->query($sql);
}

function listarContatosParaSelecao($conn) {
    $sql = "SELECT id, nome_completo FROM contatos";
    return $conn->query($sql);
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_completo = $_POST['nome_completo'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];

    if (!validaCPF($cpf)) {
        echo "CPF invalido.";
        exit;
    }

    $sql = "SELECT * FROM contatos WHERE cpf = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $cpf, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "CPF ou Email ja cadastrado";
    } else {
        $sql = "INSERT INTO contatos (nome_completo, cpf, email, data_nascimento) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nome_completo, $cpf, $email, $data_nascimento);
        if ($stmt->execute()) {
            header("Location: index.php");
        } else {
            echo "erro ao salvar contato";
        }
    }
}
