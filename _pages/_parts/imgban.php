<div class="imgban" id="imgban">
    <?php
    $stmt = $conn->prepare("SELECT id, url, link FROM imgerban");
    $stmt->execute();
    foreach($stmt->fetchAll() as $plano){
    ?>
    <a href='<?= $plano["link"]; ?>' target='_blank'><img src='<?= $plano["url"]; ?>' alt='Imagem <?= $plano["id"]; ?>'></a>
    <?php } ?>
</div>

<script>
    var isDragging = false;
    var startPosition = 0;
    var startScrollLeft = 0;

    var imgban = document.getElementById('imgban');

    imgban.addEventListener('mousedown', function(event) {
        event.preventDefault();
        isDragging = true;
        imgban.style.cursor = 'grabbing';
        startPosition = event.clientX;
        startScrollLeft = imgban.scrollLeft;
    });

    imgban.addEventListener('mousemove', function(event) {
        if (isDragging) {
            var delta = startPosition - event.clientX;
            imgban.scrollLeft = startScrollLeft + delta;
        }
    });

    imgban.addEventListener('mouseup', function() {
        isDragging = false;
        imgban.style.cursor = 'grab';
    });

    imgban.addEventListener('mouseleave', function() {
        if (isDragging) {
            isDragging = false;
            imgban.style.cursor = 'grab';
        }
    });

    imgban.addEventListener('dblclick', function(event) {
        var target = event.target;
        if (target.tagName.toLowerCase() === 'img') {
            var link = target.parentElement.href;
            window.open(link, '_blank');
        }
    });
</script>