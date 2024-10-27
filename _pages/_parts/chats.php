<!DOCTYPE html>
<html>
<head>
    <title>Chats</title>
    <link rel="stylesheet" href="./app/styles.css">
</head>
<body>

<div class="plano" id="plano">
    <?php
        $stmt = $conn->prepare("SELECT id, url, link FROM chats");
        $stmt->execute();

        $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($chats) > 0) {
            foreach ($chats as $chat) {
                echo "<a href='" . $chat["link"] . "' target='_blank'><img src='" . $chat["url"] . "' alt='Imagem " . $chat["id"] . "'></a>";
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
