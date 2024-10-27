<link rel="stylesheet" href="./app/styles.css">

<?php
$isMobile = false;
if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $isMobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent);
}

$stmt = $conn->prepare("SELECT " . ($isMobile ? "dataMobi" : "dataPC") . ", timeban FROM indez");
$stmt->execute();

$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($images) > 0) {
    echo '<div class="carousel">';
    echo '<div class="carousel-inner">';

    foreach ($images as $index => $image) {
        $imageData = base64_encode($image[($isMobile ? 'dataMobi' : 'dataPC')]);
        $mimeType = 'image/jpeg';
        $timeban = $image['timeban'];

        echo '<div class="carousel-item" data-timeban="' . $timeban . '">';
        echo '<img src="data:' . $mimeType . ';base64,' . $imageData . '" alt="...">';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
} else {
    echo "Nenhuma imagem encontrada.";
}
?>

<script>
    let slideIndex = 0;
    let slides = document.querySelectorAll('.carousel-item');
    let timeban = slides[0].getAttribute('data-timeban');
    showSlides();

    function showSlides() {
        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1;
        }
        document.querySelector('.carousel-inner').style.transform = `translateX(-${(slideIndex - 1) * 100}%)`;

        timeban = slides[slideIndex - 1].getAttribute('data-timeban');
        setTimeout(showSlides, timeban * 1000);
    }
</script>