<!DOCTYPE html>
<html>
<head>
    <title>Company</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>

<div class="plano" id="plano">
    <?php
        $stmt = $conn->prepare("SELECT id, url, link FROM multi");
        $stmt->execute();

        $multi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($multi) > 0) {
            foreach ($multi as $row) {
                echo "<a href='" . $row["link"] . "' target='_blank'><img src='" . $row["url"] . "' alt='Imagem " . $row["id"] . "'></a>";
            }
        } else {
            echo "0 resultados";
        }
    $conn = null;
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
            var link = target.parentElement.href;
            window.open(link, '_blank');
        }
    });
</script>

</body>
</html>
