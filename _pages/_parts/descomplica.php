<?php
function validarCPF($cpf)
{
    $cpf = preg_replace('/\D/', '', $cpf);
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

function validarCNPJ($cnpj)
{
    $cnpj = preg_replace('/\D/', '', $cnpj);
    if (preg_match('/(\d)\1{13}/', $cnpj)) {
        return false;
    }

    $tamanho = strlen($cnpj) - 2;
    $numeros = substr($cnpj, 0, $tamanho);
    $digitos = substr($cnpj, $tamanho);

    $soma = 0;
    $pos = $tamanho - 7;
    for ($i = $tamanho; $i >= 1; $i--) {
        $soma += $numeros[$tamanho - $i] * $pos--;
        if ($pos < 2) {
            $pos = 9;
        }
    }
    $resultado = $soma % 11 < 2 ? 0 : 11 - $soma % 11;
    if ($resultado != $digitos[0]) {
        return false;
    }

    $tamanho = $tamanho + 1;
    $numeros = substr($cnpj, 0, $tamanho);
    $soma = 0;
    $pos = $tamanho - 7;
    for ($i = $tamanho; $i >= 1; $i--) {
        $soma += $numeros[$tamanho - $i] * $pos--;
        if ($pos < 2) {
            $pos = 9;
        }
    }
    $resultado = $soma % 11 < 2 ? 0 : 11 - $soma % 11;
    if ($resultado != $digitos[1]) {
        return false;
    }
    return true;
}

function formatarDocumento($documento)
{
    $documento = preg_replace('/\D/', '', $documento);

    if (strlen($documento) == 11 && validarCPF($documento)) {
        $documento = substr($documento, 0, 3) . '.' . substr($documento, 3, 3) . '.' . substr($documento, 6, 3) . '-' . substr($documento, 9, 2);
    } elseif (strlen($documento) == 14 && validarCNPJ($documento)) {
        $documento = substr($documento, 0, 2) . '.' . substr($documento, 2, 3) . '.' . substr($documento, 5, 3) . '/' . substr($documento, 8, 4) . '-' . substr($documento, 12, 2);
    } else {
        return false;
    }

    return $documento;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Cliente</title>
    <link rel="stylesheet" href="../../styles.css">
</head>

<body>
    <div class="container">
        <div class="container-imputs">
        <form method="post" action="">
            <input type="text" name="cpf" placeholder="Digite o CPF ou CNPJ" value="<?= isset($_POST['cpf']) ? $_POST['cpf'] : null ?>">
            <input type="submit" value="Buscar" class="btn-visualizar">
        </form>
    
        <?php
        //colocar aqui a func/metodo de pesquisa da api do sistema usado
        ?>
        </div>
    </div>
    <?php
    if (isset($pdf_content)) { ?>
        <iframe src='data:application/pdf;base64,<?= $pdf_content; ?>' width='100%' height='500px'></iframe>
    <?php } ?>
</body>

</html>