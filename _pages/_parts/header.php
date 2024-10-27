<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fica Company</title>
    <link rel="icon" type="image/x-icon" href="https://i.imgur.com/sJZyyT3.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= _CONFIG["URL"] ?>/_assets/css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </style>
    <script>
        const siteApi = "<?= _CONFIG["URL"] ?>/api/";

        function siteLoading(status) {
            if (status)
                $("#loading").css("display", "flex");
            else
                $("#loading").css("display", "none");
        }
    </script>
</head>

<body>
    <div id="loading" style="
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 99999;
    background-color: rgb(0 0 0 / 80%);
    display: none;
    align-items: center;
    justify-content: center;
">
        <img src="https://portal.ufvjm.edu.br/a-universidade/cursos/grade_curricular_ckan/loading.gif/loading.gif" style="
    width: 75px;
">

    </div>
    <div class="top-bar">
        <div class="logo">
            <a href="home">
                <img src="">
            </a>
        </div>
        <div class="dropdown">
            <div class="search-bar">
                <button type="submit" class="search-button">Pesquisar...
                    <i class="fas fa-search search-icon"></i></button>
            </div>
            <div class="dropdown-content">
                <a href="ficadesc">Boletos</a>
                <a href="assine">Assine-já</a>
                <a href="servicos">Serviços</a>
                <a href="atend">Atendimento</a>
                <a href="job">Trabalhe conosco</a>
                <a href="ouvidoria">Ouvidoria</a>
                <a href="historia">Nossa Historia</a>
                <a href="social">Fica Social</a>
                <a href="mvv">Missão Visão e valores</a>
            </div>
        </div>
    </div>
    <div class="bottom-bar">
        <button id="toggleOptions" class="toggle-button">≡</button>
        <ul id="optionsList" class="hidden">
            <li><a href="ficadesc" class="link">FICA descomplica</a></li>
            <li><a href="assine" class="link">Assine já</a></li>
            <li><a href="servicos" class="link">Serviços</a></li>
            <li><a href="atend" class="link">Atendimento</a></li>
        </ul>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var toggleButton = document.getElementById("toggleOptions");
            var optionsList = document.getElementById("optionsList");

            toggleButton.addEventListener("click", function() {
                optionsList.classList.toggle("show");
            });
        });
    </script>
</body>

</html>