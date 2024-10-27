<!DOCTYPE html>
<html>
<head>
    <title>Exibição de Imagens</title>
    <link rel="stylesheet" href="./app/styles.css">
</head>
<body>

<div class="stream" id="stream">
    <?php
        $stmt = $conn->prepare("SELECT id, url, link FROM stream");
        $stmt->execute();

        $stream = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($stream) > 0) {
            foreach ($stream as $row) {
                echo "<img src='" . $row["url"] . "' alt='Imagem " . $row["id"] . "' data-link='" . $row["link"] . "'>";
            }
        } else {    
            echo "0 resultados";
        }
    ?>
</div>

<script>
    var isDragging = false;
    var startPosition = 0;
    var startScrollLeft = 0;

    var plano = document.getElementById('plano');

    plano.addEventListener('mousedown', function(event) {
        event.preventDefault();
        isDragging = true;
        plano.style.cursor = 'grabbing';
        startPosition = event.clientX;
        startScrollLeft = plano.scrollLeft;
    });

    plano.addEventListener('mousemove', function(event) {
        if (isDragging) {
            var delta = startPosition - event.clientX;
            plano.scrollLeft = startScrollLeft + delta;
        }
    });

    plano.addEventListener('mouseup', function() {
        isDragging = false;
        plano.style.cursor = 'grab';
    });

    plano.addEventListener('mouseleave', function() {
        if (isDragging) {
            isDragging = false;
            plano.style.cursor = 'grab';
        }
    });

    plano.addEventListener('dblclick', function(event) {
        var target = event.target;
        if (target.tagName.toLowerCase() === 'img') {
            var link = target.getAttribute('data-link');
            window.open(link, '_blank');
        }
    });
</script>

</body>
</html>
