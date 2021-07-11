<?php

class CsAmVideo {
		
    function PrintFreeFilmPlayer($csam_short_name) {

        $csam_poster = "https://static.animagia.pl/" . $csam_short_name . "_poster.jpg";
        $cardstack_am_episode = "2";
        $cardstack_am_pure_stream_str = $csam_short_name . "_2_" . time() .
                "_" . $_SERVER['REMOTE_ADDR'];
        $cardstack_am_stream_token = CardStackAm::obfuscateString($cardstack_am_pure_stream_str);
        $csam_append_token_to_this = CardStackAmConstants::getVidUrl() .
                "stream/film_stream.php/" . $csam_short_name . $cardstack_am_episode . ".webm?token=";
        $cardstack_am_video = $csam_append_token_to_this . $cardstack_am_stream_token;



        if (IP_Geo_Block::get_geolocation()['code'] !== 'PL') {
            $cardstack_am_video = "";
        }

        $csam_preview_length = 430;
        if ("Hana" == $csam_short_name) {
            $csam_preview_length = 913;
        } else if ("Past" == $csam_short_name) {
            $csam_preview_length = 772;
        } else if ("Future" == $csam_short_name) {
            $csam_preview_length = 768;
        } else if ("Tama" == $csam_short_name) {
            $csam_preview_length = 645;
        } else if ("DanMachi" == $csam_short_name) {
            $csam_preview_length = 455;
        } else if ("Servamp" == $csam_short_name) {
            $csam_preview_length = 824;
        }
		
        if ("Hana" == $csam_short_name) :
        ?>

        <p>Całość dostępna w
            <a href="<?php echo get_home_url() ?>/sklep/">cyfrowej kopii do ściągnięcia</a> i w streamingu
            <a href="<?php echo get_home_url() ?>/sklep/">premium</a>. Poniżej bezpłatny podgląd z&nbsp;napisami,
		z ograniczonym czasem oglądania:</p>

		<?php else: ?>

        <p>Streaming bezpłatny z ograniczonym czasem oglądania, całość dostępna w
            <a href="<?php echo get_home_url() ?>/sklep/">cyfrowej kopii</a> i dla
            <a href="<?php echo get_home_url() ?>/sklep/">kont premium</a>.</p>
		
		<?php endif; ?>


        <video onerror="onLoadError();" id='amagi' class="video-js vjs-16-9 vjs-big-play-centered"
               style="width: 100%;"
               controls="true" oncontextmenu="return false;"
               poster="<?php echo $csam_poster ?>" preload="metadata"
               data-setup='{}'>
            <source src="<?php echo $cardstack_am_video ?>" type="video/webm" />
        </video>

        <script src="https://static.animagia.pl/video.js"></script>

        <script>
            var player = videojs('amagi');

            function makeRequest() {
                var source = document.createElement('source');
                var xhr = new XMLHttpRequest();
                var linkToCurrentPage = "<?php echo get_permalink() ?>";
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        var positionStart = xhr.responseText.search("token=") + 6;
                        var positionEnd = xhr.responseText.search('type="video/webm"') - 2;
                        var token = xhr.responseText.substring(positionStart, positionEnd);
                        var newSrc = "<?php echo $csam_append_token_to_this ?>" + token;
                        source.setAttribute('src', newSrc);
                        source.setAttribute('type', 'video/webm');
                        console.log("New token: " + token);
                        document.getElementById("amagi_html5_api").innerHTML = '';
                        document.getElementById("amagi_html5_api").appendChild(source);
                        player.reset();
                        player.src({type: 'video/webm', src: newSrc, withCredentials: false});
                    }
                }
                xhr.open('POST', linkToCurrentPage, true);
                xhr.send(null);
            }

            function onLoadError() {
                console.warn("Playback has not started - expired token?");
                makeRequest();
            }

            player.on('waiting', function () {
                makeRequest();
                setTimeout(function () {
                    onLoadError();
                }, 4000);
            });

            player.on('dblclick', function () {
                player.requestFullscreen();
            });

            player.on('dblclick', function () {
                player.exitFullscreen();
            });

            player.on('timeupdate', function () {
                var vid1time = player.currentTime();

                if (vid1time > <?php print($csam_preview_length); ?>) {
                    player.pause();
                    player.currentTime(<?php print(($csam_preview_length - 5)); ?>);
                }
            });


        </script>
        <?php
    }

    function printPremiumFilmPlayer($csam_short_name) {
        $cardstack_am_episode = "00";
        $cardstack_am_pure_stream_str = $csam_short_name . "_" . $cardstack_am_episode . "_" . time() .
                "_" . $_SERVER['REMOTE_ADDR'];
        $cardstack_am_stream_token = CardStackAm::obfuscateString($cardstack_am_pure_stream_str);
        if ($_GET["altsub"] === "yes") {
            $cardstack_am_episode = $cardstack_am_episode . 'a';
        } else if ($_GET["dub"] === "yes" && $csam_short_name === "HanaIro" ) {
            $cardstack_am_episode = $cardstack_am_episode . 'd';
        }
        $cardstack_am_video = CardStackAmConstants::getVidUrl() .
                "stream/film_stream.php/" . $csam_short_name . $cardstack_am_episode . ".webm?token=" .
                $cardstack_am_stream_token;
        $cardstack_am_poster = "https://static.animagia.pl/" . $csam_short_name . "_poster.jpg";

        if (IP_Geo_Block::get_geolocation()['code'] !== 'PL') {
            $cardstack_am_video = "";
        }

		if($csam_short_name === "HanaIro" && $cardstack_am_episode == "00") {
			echo '<p>Poniżej wersja z napisami. Przełącz na: ' .
				'<a href="'. get_permalink() . '?dub=yes">dubbing NanoKarrin</a> · ' .
				'<a href="'. get_permalink() . '?altsub=yes">bardziej spolsczone napisy.</a></p>';
		} else if ($cardstack_am_episode == "00") {
            echo '<p>Jeśli wolisz napisy bez japońskich tytułów grzecznościowych, przejdź <a href="'
            . get_permalink() . '?altsub=yes">tutaj</a>.</p>';
        } else if ($cardstack_am_episode == "00a") {
            echo '<p>Napisy bez japońskich tytułów grzecznościowych, z zachodnią kolejnością imion i nazwisk.</p>';
        } else if ($cardstack_am_episode == "00d") {
            echo '<p>Wersja z pełnym polskim dubbingiem.</p>';
        }
        ?>

        <!--data-setup='{"playbackRates": [1, 1.1, 1.2, 2] }'-->
        <video id='amagi' class="video-js vjs-16-9 vjs-big-play-centered" style="width: 100%;"
               controls="true" oncontextmenu="return false;"
               poster="<?php echo $cardstack_am_poster ?>" preload="metadata"
               data-setup='{}'>
            <source src="<?php echo $cardstack_am_video ?>" type="video/webm" />
        </video>

        <script src="https://static.animagia.pl/video.js"></script>

        <script>
            var vid1 = videojs('amagi');
            vid1.on('dblclick', function () {
                vid1.requestFullscreen();
            });
            vid1.on('dblclick', function () {
                vid1.exitFullscreen();
            });
        </script>
        <?php
    }


	function getFreeFilenameBySku($csam_sku) {
		$csam_short_name = "";
		
		if("danmachi" == $csam_sku) {
			$csam_short_name = "DanMachi";
		} else if("servamp" == $csam_sku) {
			$csam_short_name = "Servamp";
		} else if("chuunibyou" == $csam_sku) {
			$csam_short_name = "Chuu";
		} else if("hanairo" == $csam_sku) {
			$csam_short_name = "Hana";
		} else if("knk_past" == $csam_sku) {
			$csam_short_name = "Past";
		} else if("knk_future" == $csam_sku) {
			$csam_short_name = "Future";
		} else if("tamako" == $csam_sku) {
			$csam_short_name = "Tama";
		}
        
        return $csam_short_name;
	}


	function getPremiumFilenameBySku($csam_sku) {
		$csam_short_name = "";
		
		if("danmachi" == $csam_sku) {
			$csam_short_name = "DanMachiOrion";
		} else if("servamp" == $csam_sku) {
			$csam_short_name = "ServampAlice";
		} else if("chuunibyou" == $csam_sku) {
			$csam_short_name = "Chuuni";
		} else if("hanairo" == $csam_sku) {
			$csam_short_name = "HanaIro";
		} else if("knk_past" == $csam_sku) {
			$csam_short_name = "KnKPast";
		} else if("knk_future" == $csam_sku) {
			$csam_short_name = "KnKFuture";
		} else if("tamako" == $csam_sku) {
			$csam_short_name = "Tamako";
		}
        
        return $csam_short_name;
	}

}
