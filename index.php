<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Agenda de Contatos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('select[name="contato_id"]').change(function() {
                var contato_id = $(this).val();
                if (contato_id) {
                    $.ajax({
                        url: "get_address.php",
                        method: "GET",
                        data: { contato_id: contato_id },
                        dataType: "json",
                        success: function(data) {
                            if (data) {
                                $("#cep").val(data.cep);
                                $("#rua").val(data.rua);
                                $("#numero").val(data.numero);
                                $("#bairro").val(data.bairro);
                                $("#cidade").val(data.cidade);
                                $("#estado").val(data.estado);
                            } else {
                                
                                $("#cep").val(row.cep);
                                $("#rua").val(row.rua);
                                $("#numero").val(row.numero);
                                $("#bairro").val(row.bairro);
                                $("#cidade").val(row.cidade);
                                $("#estado").val(row.estado);
                            }
                        }
                    });
                } else {
                    $("#cep").val('');
                    $("#rua").val('');
                    $("#numero").val('');
                    $("#bairro").val('');
                    $("#cidade").val('');
                    $("#estado").val('');
                }
            });

            $('select[name="contato_id"]').change(function() {
                var contato_id = $(this).val();
                if (contato_id) {
                    $.ajax({
                        url: "get_phone.php",
                        method: "GET",
                        data: { contato_id: contato_id },
                        dataType: "json",
                        success: function(data) {
                            if (data) {
                                $("#telefone_comercial").val(data.telefone_comercial);
                                $("#telefone_residencial").val(data.telefone_residencial);
                                $("#telefone_celular").val(data.telefone_celular);
                            } else {
                                $("#telefone_comercial").val('');
                                $("#telefone_residencial").val('');
                                $("#telefone_celular").val('');
                            }
                        }
                    });
                } else {
                    $("#telefone_comercial").val('');
                    $("#telefone_residencial").val('');
                    $("#telefone_celular").val('');
                }
            });

            $('#cep').blur(function() {
                var cep = $(this).val();
                if (cep) {
                    $.ajax({
                        url: 'https://viacep.com.br/ws/' + cep + '/json/',
                        method: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if (!("erro" in data)) {
                                $("#rua").val(data.logradouro);
                                $("#bairro").val(data.bairro);
                                $("#cidade").val(data.localidade);
                                $("#estado").val(data.uf);
                            } else {
                                alert("CEP não encontrado.");
                            }
                        }
                    });
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Agenda de Contatos</h1>

        <h2>Contatos</h2>
        <a href="create.php" class="btn btn-primary">Adicionar Contato</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome Completo</th>
                    <th>CPF</th>
                    <th>Email</th>
                    <th>Data de Nascimento</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = listarContatos($conn);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['nome_completo']}</td>
                                <td>{$row['cpf']}</td>
                                <td>{$row['email']}</td>
                                <td>" . date("d/m/Y", strtotime($row['data_nascimento'])) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Nenhum contato encontrado</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Endereços</h2>
        <form action="address_store.php" method="POST">
            <div class="form-group">
                <label for="contato_id">Contato</label>
                <select name="contato_id" id="contato_id" class="form-control" required>
                    <option value="">Selecione um contato</option>
                    <?php
                    $result = listarContatosParaSelecao($conn);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['nome_completo']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="cep">CEP</label>
                <input type="text" name="cep" class="form-control" id="cep">
            </div>
            <div class="form-group">
                <label for="rua">Rua</label>
                <input type="text" name="rua" class="form-control" id="rua">
            </div>
            <div class="form-group">
                <label for="numero">Número</label>
                <input type="text" name="numero" class="form-control" id="numero">
            </div>
            <div class="form-group">
                <label for="bairro">Bairro</label>
                <input type="text" name="bairro" class="form-control" id="bairro">
            </div>
            <div class="form-group">
                <label for="cidade">Cidade</label>
                <input type="text" name="cidade" class="form-control" id="cidade">
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <input type="text" name="estado" class="form-control" id="estado">
            </div>
            <button type="submit" class="btn btn-success">Salvar Endereço</button>
        </form>

        <h2>Criar Contatos</h2>
        <div class="container">
            <h1>Criar Contato</h1>
            <form action="create.php" method="POST">
                <div class="form-group">
                    <label for="nome_completo">Nome Completo</label>
                    <input type="text" name="nome_completo" class="form-control" id="nome_completo" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF</label>
                    <input type="text" name="cpf" class="form-control" id="cpf" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" required>
                </div>
                <div class="form-group">
                    <label for="data_nascimento">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" class="form-control" id="data_nascimento" required>
                </div>
                <button type="submit" class="btn btn-success">Salvar Contato</button>
            </form>
        </div>

        <h2>Telefones</h2>
        <form action="phone_store.php" method="POST">
            <div class="form-group">
                <label for="contato_id">Contato</label>
                <select name="contato_id" id="contato_id" class="form-control" required>
                    <option value="">Selecione um contato</option>
                    <?php
                    $result = listarContatosParaSelecao($conn);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['nome_completo']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="telefone_comercial">Telefone Comercial</label>
                <input type="text" name="telefone_comercial" class="form-control" id="telefone_comercial">
            </div>
            <div class="form-group">
                <label for="telefone_residencial">Telefone Residencial</label>
                <input type="text" name="telefone_residencial" class="form-control" id="telefone_residencial">
            </div>
            <div class="form-group">
                <label for="telefone_celular">Telefone Celular</label>
                <input type="text" name="telefone_celular" class="form-control" id="telefone_celular" required>
            </div>
            <button type="submit" class="btn btn-success">Salvar Telefone</button>
        </form>
    </div>
</body>
</html>
