<?php

class CsAmVideo {

    function PrintFreeFilmPlayer() {

        $csam_poster = "https://static.animagia.pl/film_poster.jpg";
        $cardstack_am_episode = "2";
        $cardstack_am_pure_stream_str = "Chuu_2_" . time() .
                "_" . $_SERVER['REMOTE_ADDR'];
        $cardstack_am_stream_token = CardStackAm::obfuscateString($cardstack_am_pure_stream_str);
        $cardstack_am_video = CardStackAmConstants::getVidUrl() .
                "stream/film_stream.php/Chuu" . $cardstack_am_episode . ".webm?token=" .
                $cardstack_am_stream_token;



        if (IP_Geo_Block::get_geolocation()['code'] !== 'PL') {
            $cardstack_am_video = "";
        }
        ?>

        <p>Bezpłatny stream ma ograniczony czas oglądania. Żeby obejrzeć całość,
            załóż <a href="<?php echo get_home_url() ?>/sklep/">konto premium</a>
            lub kup <a href="<?php echo get_home_url() ?>/sklep/">cyfrową kopię</a>.</p>


        <video id='amagi' class="video-js vjs-16-9 vjs-big-play-centered" style="width: 100%;"
               controls="true" oncontextmenu="return false;"
               poster="<?php echo $csam_poster ?>" preload="metadata"
               data-setup='{}'>
            <source src="<?php echo $cardstack_am_video ?>" type="video/webm" />
        </video>

        <script src="https://static.animagia.pl/video.js"></script>

        <script>
            var player = videojs('amagi');
            player.on('dblclick', function () {
                player.requestFullscreen();
            });
            player.on('dblclick', function () {
                player.exitFullscreen();
            });
            
            var modal = null;

            player.on('timeupdate', function () {
                var vid1time = player.currentTime();
                //console.log('seeked from', vid1time);

                if (vid1time > 430) {
                    player.pause();
                    if(modal === null || modal.opened() === false) {
                        modal = player.createModal('This is a modal!');
                    }
                    modal.on('modalclose', function () {
                        player.currentTime(425);
                    });
                }

            });
            
        </script>
        <?php
    }

}
