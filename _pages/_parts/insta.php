<body>
    <div class="instagram-photos"></div>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: siteApi +'ig',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                console.log(data);
                if (data.data) {
                    data.data.forEach(function(photo) {
                        if (photo.media_type === 'IMAGE' || photo.media_type === 'CAROUSEL_ALBUM') {
                            $('.instagram-photos').append(
                                `<a href="${photo.permalink}" target="_blank">
                                    <img src="${photo.media_url}" class="instagram-photo">
                                </a>`
                            );
                        }
                    });
                } else {
                    $('.instagram-photos').append('<p>contactar a central do possivel erro.</p>');
                    }
                },
                error: function() {
                    $('.instagram-photos').append('<p>contactar a central do possivel erro.</p>');
                }
            });
        });
    </script>
</body>
</html>
