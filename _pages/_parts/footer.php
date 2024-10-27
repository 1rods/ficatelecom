<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FicaTelecom</title>
    <link rel="icon" type="image/x-icon" href="https://i.imgur.com/sJZyyT3.png">
    <link rel="stylesheet" href="<?=_CONFIG["URL"]; ?>/_assets/styles.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <a href="https://api.whatsapp.com/message/GFHPFVLLD7UXD1?autoload=1&app_absent=0" style="position:fixed;width:60px;height:60px;bottom:40px;right:40px;background-color:#25d366;color:#FFF;border-radius:50px;text-align:center;font-size:30px;box-shadow: 1px 1px 2px #888;z-index:1000;" target="_blank">
        <i style="margin-top:16px" class="fa fa-whatsapp"></i>
    </a>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col">
                    <h4>Contatos:</h4>
                    <ul>
                        <li><a href="tel:8540421848" id="phone-link">(85) 4042-1848</a></li>
                        <li><a href="https://www.instagram.com/fica_company/" target="_blank">Fica_company</a></li>
                        <li><a href="https://www.google.com/maps/place/FICA+telecom/@-3.8021961,-38.5481253,15z/data=!4m2!3m1!1s0x0:0x6ee47a0793cb94a9?sa=X&ved=1t:2428&ictx=111" target="_blank">Rua Centro e Quinze, 37<br>Parque Dosis Irmãos </a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Para Você:</h4>
                    <ul>
                        <li><a href="job">Trabalhe conosco</a></li>
                        <li><a id="linkHistoria">Fica Dicas</a></li>
                        <li><a href="ouvidoria">Ouvidoria</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Sobre Nós:</h4>
                    <ul>
                        <li><a href="historia">Nossa história</a></li>
                        <li><a href="social">Fica social</a></li>
                        <li><a href="mvv">Missão, visão e Valores</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Redes sociais</h4>
                    <div class="social-links">
                        <a href="https://www.google.com/maps/place/FICA+telecom/@-3.8021961,-38.5481253,15z/data=!4m2!3m1!1s0x0:0x6ee47a0793cb94a9?sa=X&ved=1t:2428&ictx=111" target="_blank"><i class="fa-solid fa-location-dot"></i></a>
                        <a href="tel:8540421848" id="phone-link"><i class="fa-solid fa-phone"></i></a>
                        <a href="https://www.instagram.com/fica_company/" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="mailto:comercial@ficatelecom.net.br" target="_blank"><i class="fa-regular fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img id="modalImage" src="" alt="Modal Image">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var phoneLink = document.getElementById('phone-link');
            
            phoneLink.addEventListener('click', function(event) {
                event.preventDefault();
                var phoneNumber = this.getAttribute('href').replace('tel:', '');
                var tempInput = document.createElement('input');
                tempInput.value = phoneNumber;
                document.body.appendChild(tempInput);
                tempInput.select();
                tempInput.setSelectionRange(0, 99999);
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                alert('Número de telefone copiado: ' + phoneNumber);
            });

            var modal = document.getElementById('modal');
            var modalImage = document.getElementById('modalImage');
            var closeBtn = document.getElementsByClassName('close')[0];
            var linkHistoria = document.getElementById('linkHistoria');
            var linkMissao = document.getElementById('linkMissao');
            linkHistoria.onclick = function() {
                modal.style.display = 'block';
                modalImage.src = 'https://i.imgur.com/x0j2iij.png';
            }

            closeBtn.onclick = function() {
                modal.style.display = 'none';
                modalImage.src = '';
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                    modalImage.src = '';
                }
            }
        });
    </script>
</body>
</html>
