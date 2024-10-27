<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FicaTelecom</title>
    <link rel="icon" type="image/x-icon" href="https://i.imgur.com/sJZyyT3.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= _CONFIG["URL"] . '/_assets/css/styles.css'; ?>">
</head>
<body>
    <div class="banner">
    <? includePart("banner") ?>
        </div>

    <div class="pag">
        <h2>Facilite seu dia a dia com nosso autoatendimento</h2><br>
            <button class="button" onclick="window.location.href = 'ficadesc';"><i class="fa-solid fa-hand-holding-dollar"></i> Pagamento</button>
            <button class="button" onclick="window.location.href = 'assine';"><i class="fa-solid fa-cart-shopping"></i> Adesão</button>
            <button class="button" onclick="window.location.href = 'atend';"><i class="fa-solid fa-headset"></i> Suporte</button>
    </div>

    <h2>Planos:</h2><br>
        <div class="plano">
            <? includePart ("planos") ?>
    </div>
    
    <h2>Conheça nosso instagram:</h2><br>
        <div class="Insta">
            <? includePart("insta") ?>
        </div>
</body>
</html>