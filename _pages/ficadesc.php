<div class="Boleto">
    <div class="Form">
            <h2>Solicite seu Boleto</h2>
            <form id="getBoletos">
                <label for="cep">Digite o CPF ou CNPJ:</label>
                <input type="text" name="cpf" value="">
                <div class="g-recaptcha" data-sitekey="6Ldx2SMqAAAAAEOEQUE43xUryllFQpGqQiIy7qYG"></div>
                <input type="submit" value="Buscar" class="btn-visualizar">
            </form>
            <form id="formBoletos" style="display:none;">
                <label for="cep">Escolha seu boleto:</label>
                <select name="id"></select>
                <input type="submit" value="Visualizar Boleto" class="btn-visualizar">
            </form>
    </div>
</div>

<div class="Img">
    <? includePart("img") ?>
</div>

<div class="ImgBan">
    <h2>Facilidade em sua Mão</h2>
    <? includePart("imgban") ?>
</div>

<script>
    $("#formBoletos").on("submit", function(e) {
        siteLoading(true);
        e.preventDefault();
        $.ajax({
            url: siteApi + "getBoleto",
            data: $(this).serialize()
        }).done(function(data) {
            console.log(data)

            if (data.error)
                alert(data.message);
            else
                $(".Boleto iframe").remove(),
                $(".Boleto").append(`<iframe src='data:application/pdf;base64,${data.data}' width='100%' height='500px'></iframe>`);

            siteLoading(false);

        }).fail(function(jqXHR, textStatus, msg) {
            console.log(msg)
        });
    });

    $("#getBoletos").on("submit", function(e) {
    e.preventDefault();
    if (grecaptcha.getResponse() === '') {
        alert('Por favor, verifique o reCAPTCHA.');
        return false;
    }
    siteLoading(true);
    $.ajax({
        url: siteApi + "getBoletos",
        data: $(this).serialize()
    }).done(function(data) {
        console.log(data)

        if (data.error)
            alert(data.message);
        else {
            $("#formBoletos").show();
            $("#formBoletos select").empty();
            $.each(data.boletos, function(index, value) {
                $("#formBoletos select").append($('<option>', {
                    value: value.id,
                    text: `Id Boleto: ${value.id} - Data de Vencimento: ${value.vencimento}`
                }));
            });
            
            $("#getBoletos input[type='submit']").hide();
            if ($("#newSearchButton").length === 0) {
                $("#getBoletos").append('<button id="newSearchButton" class="btn-visualizar">Nova busca</button>');
            }

            $("#newSearchButton").on("click", function() {
                location.reload();
            });
        }

        siteLoading(false);

    }).fail(function(jqXHR, textStatus, msg) {
        console.log(msg)
    });
});


</script>