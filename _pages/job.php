
<body>
<div class="pag">
    <h2>Nos envie seu currículo:</h2><br>
    <div id="response"></div>
    <form id="uploadForm" enctype="multipart/form-data">
        <label for="file">Escolha um arquivo (PDF ou imagem):</label><br>
        <input type="file" name="file" id="file" accept=".pdf, image/*" required><br><br>

        <div class="g-recaptcha" data-sitekey=""></div><br>

        <input type="submit" value="Enviar" class="btn-visualizar" id="submitBtn">
    </form>
</div>

<script>
    $("#uploadForm").on("submit", function(e) {
        e.preventDefault();
        if (grecaptcha.getResponse() === '') {
            $("#response").html("<p style='color: red;'>Por favor, verifique o reCAPTCHA.</p>");
            return false;
        }
        
        var formData = new FormData(this);
        var $submitBtn = $("#submitBtn");
        $submitBtn.prop("disabled", true);

        siteLoading(true);

        $.ajax({
            url: siteApi + 'upJob',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response);
                var data;
                try {
                    data = JSON.parse(response);
                } catch (e) {
                    $("#response").html("p style='color: green;'>Seu currículo foi recebido!</p>");
                    $submitBtn.prop("disabled", false);
                    siteLoading(false);
                    return;
                }
            }
        }); location.reload();
    });
</script>

</body>
</html>
