<?php

$cepError = '';
$telefoneError = '';
$formStage = isset($_POST['formStage']) ? $_POST['formStage'] : 1;

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function is_valid_cep($cep) {
    return preg_match("/^[0-9]{5}-?[0-9]{3}$/", $cep);
}

function is_valid_name($name) {
    return preg_match("/^[a-zA-Z\s]+$/", $name);
}

function is_valid_cpf($cpf) {
    return preg_match("/^[0-9]{11}$/", $cpf);
}

function is_valid_rg($rg) {
    return preg_match("/^[0-9]{7,14}$/", $rg);
}

function is_valid_date($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function is_valid_phone($phone) {
    return preg_match("/^[0-9]{10,11}$/", $phone);
}

function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $cep = sanitize_input($_POST['cep'] ?? '');
    $nome = sanitize_input($_POST['nome'] ?? '');
    $cpf = sanitize_input($_POST['cpf'] ?? '');
    $rg = sanitize_input($_POST['rg'] ?? '');
    $data_nasc = sanitize_input($_POST['data_nasc'] ?? '');
    $casa_alugada = sanitize_input($_POST['casa_alugada'] ?? '');
    $contato_dono = sanitize_input($_POST['contato_dono'] ?? '');
    $nome_dono = sanitize_input($_POST['nome_dono'] ?? '');
    $bairro = sanitize_input($_POST['bairro'] ?? '');
    $rua = sanitize_input($_POST['rua'] ?? '');
    $numero = sanitize_input($_POST['numero'] ?? '');
    $ponto_referencia = sanitize_input($_POST['ponto_referencia'] ?? '');
    $telefone_contato = sanitize_input($_POST['telefone_contato'] ?? '');
    $telefone_whatsapp = sanitize_input($_POST['telefone_whatsapp'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $plano = sanitize_input($_POST['plano'] ?? '');
    $plano_nome = '';

    if ($formStage == 1 && isset($_POST['cep'])) {
        $cep = preg_replace('/\D/', '', $cep);
        if (is_valid_cep($cep)) {
            $stmt = $conn->prepare("SELECT * FROM cep WHERE codcep = :codcep");
            $stmt->execute(['codcep' => $cep]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $cep = $result['codcep'];
                $formStage = 2;
            } else {
                $cepError = "CEP não encontrado. Por favor, tente novamente.";
            }
        } else {
            $cepError = "CEP inválido. Por favor, insira um CEP válido.";
        }
    } elseif ($formStage == 2 && isset($_POST['nome'], $_POST['cpf'], $_POST['rg'], $_POST['data_nasc'])) {
        $nome = sanitize_input($_POST['nome']);
        $cpf = sanitize_input($_POST['cpf']);
        $rg = sanitize_input($_POST['rg']);
        $data_nasc = sanitize_input($_POST['data_nasc']);
    
        $cpf = preg_replace('/[\.\-]/', '', $cpf);
        $rg = preg_replace('/[\.\-]/', '', $rg);
        $nome = ucfirst($nome);
    
        if (is_valid_name($nome) && is_valid_cpf($cpf) && is_valid_rg($rg) && is_valid_date($data_nasc)) {
            $formStage = 3;
        } else {
            $cepError = "Dados inválidos. Por favor, verifique os campos e tente novamente.";
        }
    
    
    } elseif ($formStage == 3 && isset($_POST['casa_alugada'])) {
        if ($_POST['casa_alugada'] == 'sim') {
            $formStage = 4;
        } elseif ($_POST['casa_alugada'] == 'nao') {
            $formStage = 5;
        }
    } elseif ($formStage == 4 && isset($_POST['contato_dono'], $_POST['nome_dono'])) {
        if (is_valid_name($nome_dono) && is_valid_phone($contato_dono)) {
            $formStage = 5;
        } else {
            $cepError = "Dados do dono inválidos. Por favor, verifique os campos e tente novamente.";
        }
    } elseif ($formStage == 5 && isset($_POST['bairro'], $_POST['rua'], $_POST['numero'], $_POST['ponto_referencia'])) {
        if (!empty($bairro) && !empty($rua) && !empty($numero) && !empty($ponto_referencia)) {
            $formStage = 6;
        } else {
            $cepError = "Endereço inválido. Por favor, verifique os campos e tente novamente.";
        }
    } elseif ($formStage == 6 && isset($_POST['telefone_contato'], $_POST['telefone_whatsapp'], $_POST['email'])) {
        if (is_valid_phone($telefone_contato) && is_valid_phone($telefone_whatsapp) && is_valid_email($email)) {
            if ($telefone_contato == $telefone_whatsapp) {
                $telefoneError = "Os números de telefone não podem ser iguais. Por favor, insira números diferentes.";
            } else {
                $formStage = 7;
            }
        } else {
            $cepError = "Dados de contato inválidos. Por favor, verifique os campos e tente novamente.";
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $formStage == 7) {
        $selected_plano_id = sanitize_input($_POST['plano']);

        $recaptchaSecret = '';

        $recaptchaResponse = $_POST['g-recaptcha-response'];
    
        $recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
        $recaptcha = json_decode($recaptcha);
    
        if (!$recaptcha->success) {
            jsonError("Validação do reCAPTCHA falhou. Tente novamente.");
        }
        
        if (empty($selected_plano_id)) {
            die("Erro: Nenhum plano foi selecionado.");
        }
        
        try {
            $stmt = $conn->prepare("SELECT nome FROM planos WHERE id = :id");
            $stmt->execute(['id' => $selected_plano_id]);
            $plano_result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($plano_result) {
                $plano_nome = $plano_result['nome'];
            } else {
                die('Plano não encontrado.');
            }
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
        
        $sms = "cep:$cep, nome completo:$nome, cpf:$cpf, rg:$rg, data_nasc:$data_nasc, casa_alugada:$casa_alugada";
        if ($casa_alugada == 'sim') {
            $sms .= ", contato_dono:$contato_dono, nome_dono:$nome_dono";
        }
        $sms .= ", bairro:$bairro, rua:$rua, numero:$numero, ponto_referencia:$ponto_referencia, telefone_contato:$telefone_contato, telefone_whatsapp:$telefone_whatsapp, email:$email, plano:$plano_nome";
        
        try {
            $stmt = $conn->prepare("INSERT INTO mensagens (sms) VALUES (:sms)");
            $stmt->execute(['sms' => $sms]);
            echo "Formulário completo. Obrigado por fornecer suas informações. Um de nossos atendentes entrará em contato!";
            echo '<script>
                setTimeout(function() {
                    window.location.href = "home";
                }, 2000);
            </script>';
            
            exit;
        } catch(PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<body>
    <?php if ($formStage == 1): ?>
        <form method="POST" action="">
            <input type="hidden" name="formStage" value="1">
            <label for="cep">Informe seu CEP:</label>
            <input type="text" id="cep" name="cep" value="<?= (isset($cep)) ? $cep : null ?>" required>
            <input type="submit" value="Verificar Cep" class="btn-visualizar">
            <?php if (!empty($cepError)): ?>
                <span style="color: red;">Infelizmente não temos viabilidade para esse endereço</span>
            <?php endif; ?>
        </form>
    <?php elseif ($formStage == 2): ?>
        <form method="POST" action="">
            <input type="hidden" name="formStage" value="2">
            <input type="hidden" name="cep" value="<?php echo htmlspecialchars($cep); ?>">
            <label for="nome">Nome completo:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required><br>
            <?php if (!empty($nomeError)): ?>
                <span style="color: red;">Por favor nome Completo</span><br>
            <?php endif; ?>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>" required><br>
            <?php if (!empty($cpfError)): ?>
                <span style="color: red;">CPF inválido</span><br>
            <?php endif; ?>

            <label for="rg">RG:</label>
            <input type="text" id="rg" name="rg" value="<?php echo htmlspecialchars($rg); ?>" required><br>
            <?php if (!empty($rgError)): ?>
                <span style="color: red;">RG inválido</span><br>
            <?php endif; ?>

            <label for="data_nasc">Data de Nascimento:</label>
            <input type="date" id="data_nasc" name="data_nasc" value="<?php echo htmlspecialchars($data_nasc); ?>" required><br>
            <?php if (!empty($dataNascError)): ?>
                <span style="color: red;">Data de nascimento inválido</span><br>
            <?php endif; ?>
            <input type="submit" value="Próximo" class="btn-visualizar">
        </form>
    <?php elseif ($formStage == 3): ?>
        <form method="POST" action="">
            <input type="hidden" name="formStage" value="3">
            <input type="hidden" name="cep" value="<?php echo htmlspecialchars($cep); ?>">
            <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nome); ?>">
            <input type="hidden" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>">
            <input type="hidden" name="rg" value="<?php echo htmlspecialchars($rg); ?>">
            <input type="hidden" name="data_nasc" value="<?php echo htmlspecialchars($data_nasc); ?>">
            <label>Sua casa é alugada?</label><br>
            <input type="radio" id="sim" name="casa_alugada" value="sim" required>
            <label for="sim">Sim</label><br>
            <input type="radio" id="nao" name="casa_alugada" value="nao" required>
            <label for="nao">Não</label><br>
            <?php if (!empty($casaAlugadaError)): ?>
                <span style="color: red;">É necessario marcar uma das opções</span><br>
            <?php endif; ?>
            <input type="submit" value="Próximo" class="btn-visualizar">
        </form>
    <?php elseif ($formStage == 4): ?>
        <form method="POST" action="">
            <input type="hidden" name="formStage" value="4">
            <input type="hidden" name="cep" value="<?php echo htmlspecialchars($cep); ?>">
            <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nome); ?>">
            <input type="hidden" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>">
            <input type="hidden" name="rg" value="<?php echo htmlspecialchars($rg); ?>">
            <input type="hidden" name="data_nasc" value="<?php echo htmlspecialchars($data_nasc); ?>">
            <input type="hidden" name="casa_alugada" value="<?php echo htmlspecialchars($casa_alugada); ?>">
            <label for="contato_dono">Contato do Locador:</label>
            <input type="text" id="contato_dono" name="contato_dono" value="<?php echo htmlspecialchars($contato_dono); ?>" required><br>
            <?php if (!empty($contatoDonoError)): ?>
                <span style="color: red;">Forneça um contato do dono correto</span><br>
            <?php endif; ?>

            <label for="nome_dono">Nome do Dono:</label>
            <input type="text" id="nome_dono" name="nome_dono" value="<?php echo htmlspecialchars($nome_dono); ?>" required><br>
            <?php if (!empty($nomeDonoError)): ?>
                <span style="color: red;">error</span><br>
            <?php endif; ?>

            <input type="submit" value="Próximo" class="btn-visualizar">
        </form>
    <?php elseif ($formStage == 5): ?>
        <form method="POST" action="">
            <input type="hidden" name="formStage" value="5">
            <input type="hidden" name="cep" value="<?php echo htmlspecialchars($cep); ?>">
            <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nome); ?>">
            <input type="hidden" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>">
            <input type="hidden" name="rg" value="<?php echo htmlspecialchars($rg); ?>">
            <input type="hidden" name="data_nasc" value="<?php echo htmlspecialchars($data_nasc); ?>">
            <input type="hidden" name="casa_alugada" value="<?php echo htmlspecialchars($casa_alugada); ?>">
            <input type="hidden" name="contato_dono" value="<?php echo htmlspecialchars($contato_dono); ?>">
            <input type="hidden" name="nome_dono" value="<?php echo htmlspecialchars($nome_dono); ?>">
            <label for="bairro">Bairro:</label>
            <input type="text" id="bairro" name="bairro" value="<?php echo htmlspecialchars($bairro); ?>" required><br>
            <?php if (!empty($bairroError)): ?>
                <span style="color: red;">Ops: Bairro inválido</span><br>
            <?php endif; ?>

            <label for="rua">Rua:</label>
            <input type="text" id="rua" name="rua" value="<?php echo htmlspecialchars($rua); ?>" required><br>
            <?php if (!empty($ruaError)): ?>
                <span style="color: red;">Ops: Rua inválido</span><br>
            <?php endif; ?>

            <label for="numero">Número:</label>
            <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($numero); ?>" required><br>
            <?php if (!empty($numeroError)): ?>
                <span style="color: red;">Ops: Numeração da casa inválida</span><br>
            <?php endif; ?>

            <label for="ponto_referencia">Ponto de referência:</label>
            <input type="text" id="ponto_referencia" name="ponto_referencia" value="<?php echo htmlspecialchars($ponto_referencia); ?>" required><br>
            <?php if (!empty($pontoReferenciaError)): ?>
                <span style="color: red;">Ops: Ponto de referencia inválida</span><br>
            <?php endif; ?>

            <input type="submit" value="Próximo" class="btn-visualizar">
        </form>
    <?php elseif ($formStage == 6): ?>
        <form method="POST" action="">
            <input type="hidden" name="formStage" value="6">
            <input type="hidden" name="cep" value="<?php echo htmlspecialchars($cep); ?>">
            <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nome); ?>">
            <input type="hidden" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>">
            <input type="hidden" name="rg" value="<?php echo htmlspecialchars($rg); ?>">
            <input type="hidden" name="data_nasc" value="<?php echo htmlspecialchars($data_nasc); ?>">
            <input type="hidden" name="casa_alugada" value="<?php echo htmlspecialchars($casa_alugada); ?>">
            <input type="hidden" name="contato_dono" value="<?php echo htmlspecialchars($contato_dono); ?>">
            <input type="hidden" name="nome_dono" value="<?php echo htmlspecialchars($nome_dono); ?>">
            <input type="hidden" name="bairro" value="<?php echo htmlspecialchars($bairro); ?>">
            <input type="hidden" name="rua" value="<?php echo htmlspecialchars($rua); ?>">
            <input type="hidden" name="numero" value="<?php echo htmlspecialchars($numero); ?>">
            <input type="hidden" name="ponto_referencia" value="<?php echo htmlspecialchars($ponto_referencia); ?>">
            <label for="telefone_contato">Telefone de contato:</label>
            <input type="text" id="telefone_contato" name="telefone_contato" value="<?php echo htmlspecialchars($telefone_contato); ?>" required><br>
            <?php if (!empty($telefoneContatoError)): ?>
                <span style="color: red;">Forneça um telefone de contato correto</span><br>
            <?php endif; ?>

            <label for="telefone_whatsapp">Telefone WhatsApp:</label>
            <input type="text" id="telefone_whatsapp" name="telefone_whatsapp" value="<?php echo htmlspecialchars($telefone_whatsapp); ?>" required><br>
            <?php if (!empty($telefoneWhatsappError)): ?>
                <span style="color: red;">Forneça um telefone WhatsApp correto</span><br>
            <?php endif; ?>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>
            <?php if (!empty($emailError)): ?>
                <span style="color: red;">Forneça um email correto</span><br>
            <?php endif; ?>

            <input type="submit" value="Próximo" class="btn-visualizar">
        </form>
    <?php elseif ($formStage == 7): ?>
    <form method="POST" action="" id="form-stage-7">
        <input type="hidden" name="formStage" value="7">
        <input type="hidden" name="cep" value="<?php echo htmlspecialchars($cep); ?>">
        <input type="hidden" name="nome" value="<?php echo htmlspecialchars($nome); ?>">
        <input type="hidden" name="cpf" value="<?php echo htmlspecialchars($cpf); ?>">
        <input type="hidden" name="rg" value="<?php echo htmlspecialchars($rg); ?>">
        <input type="hidden" name="data_nasc" value="<?php echo htmlspecialchars($data_nasc); ?>">
        <input type="hidden" name="casa_alugada" value="<?php echo htmlspecialchars($casa_alugada); ?>">
        <input type="hidden" name="contato_dono" value="<?php echo htmlspecialchars($contato_dono); ?>">
        <input type="hidden" name="nome_dono" value="<?php echo htmlspecialchars($nome_dono); ?>">
        <input type="hidden" name="bairro" value="<?php echo htmlspecialchars($bairro); ?>">
        <input type="hidden" name="rua" value="<?php echo htmlspecialchars($rua); ?>">
        <input type="hidden" name="numero" value="<?php echo htmlspecialchars($numero); ?>">
        <input type="hidden" name="ponto_referencia" value="<?php echo htmlspecialchars($ponto_referencia); ?>">
        <input type="hidden" name="telefone_contato" value="<?php echo htmlspecialchars($telefone_contato); ?>">
        <input type="hidden" name="telefone_whatsapp" value="<?php echo htmlspecialchars($telefone_whatsapp); ?>">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

        <label for="plano">Escolha o seu plano:</label><br>
        <?php
        try {
            $stmt = $conn->prepare("SELECT id, nome, url, link, preco FROM planos");
            $stmt->execute();
            $planos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($planos) > 0) {
                foreach ($planos as $plano) {
                    echo "<div>
                            <input type='radio' id='plano{$plano['id']}' name='plano' value='{$plano['id']}' required>
                            <label for='plano{$plano['id']}'><img src='{$plano['url']}' alt='Plano {$plano['nome']}'><br>{$plano['nome']} - Preço: R$ {$plano['preco']}</label>
                          </div>";
                }
            } else {
                echo "Nenhum plano disponível.";
            }
        } catch(PDOException $e) {
            die('ERROR: ' . $e->getMessage());
        }
        ?>
        <div class="g-recaptcha" data-sitekey=""></div>
        <input type="submit" value="Enviar" class="btn-visualizar">
    </form>

    <script>
        document.getElementById('form-stage-7').addEventListener('submit', function(event) {
            var response = grecaptcha.getResponse();
            if (response.length === 0) {
                alert('Por favor, complete o reCAPTCHA.');
                event.preventDefault();
            }
        });
    </script>
<?php endif; ?>
</body>

</html>
