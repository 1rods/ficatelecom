<body>
<div class="pag">
<h2>Ouvidoria:</h2>
    <form id="contact-form" method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="texto">Mensagem:</label><br>
        <textarea id="texto" name="texto" rows="4" cols="50" required style="width: 319px; height: 67px;"></textarea><br><br>
        <div class="g-recaptcha" data-sitekey=""></div><br>
        <input type="submit" value="Enviar" class="btn-visualizar">
    </form>
</div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        $("#contact-form").on("submit", function(e) {
            e.preventDefault();
            
            var formData = $(this).serialize();
            var $submitBtn = $("input[type='submit']");
            $submitBtn.prop("disabled", true);

            $.ajax({
                url: siteApi + 'upOuv',
                type: 'POST',
                data: formData,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.success) {
                        alert("Sua mensagem foi enviada com sucesso!");
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        alert("Erro: " + data.message);
                        $submitBtn.prop("disabled", false);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Erro na requisição AJAX: " + textStatus);
                    $submitBtn.prop("disabled", false);
                }
            }); location.reload();
        });
    </script>
</body>
</html>
