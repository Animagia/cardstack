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
        $cardstack_am_video = CardStackAmConstants::getVidUrl() .
                "stream/film_stream.php/" . $csam_short_name . $cardstack_am_episode . ".webm?token=" .
                $cardstack_am_stream_token . "&t=" . time();
	$cardstack_am_video2 = CardStackAmConstants::getVidUrl() .
            "stream/film_stream.php/" . $csam_short_name . $cardstack_am_episode . "s" . ".webm?token=" .
     	$cardstack_am_stream_token . "&t=" . time() ;



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
        }

?>

        <p>Streaming bezpłatny z ograniczonym czasem oglądania, całość dostępna w
            <a href="<?php echo get_home_url() ?>/sklep/">cyfrowej kopii</a> i dla
            <a href="<?php echo get_home_url() ?>/sklep/">kont premium</a>.</p>

        <video onerror="onLoadError();" id='amagi' class="video-js vjs-16-9 vjs-big-play-centered" style="width: 100%;"
               controls="true" oncontextmenu="return false;"
               poster="<?php echo $csam_poster ?>" preload="metadata"
               data-setup='{}'>
            <source src="<?php echo $cardstack_am_video ?>" type="video/webm" />
        </video>

        <script src="https://static.animagia.pl/video.js"></script>
	<script src="https://dev.animagia.pl/static/videojs-resolution-switcher.js"></script>
	<link rel="stylesheet" type="text/css" href="https://dev.animagia.pl/static/videojs.suggestedVideoEndc.css">
	<script src="https://dev.animagia.pl/static/videojs.suggestedVideoEndcap1.js"></script>
<script>

//napisy
document.getElementById("amagi").innerHTML ='<track kind="captions" src="https://dotsub.com/media/5d5f008c-b5d5-466f-bb83-2b3cfa997992/c/chi_hans/vtt" srclang="zh" label="Chinese" default></track><track kind="captions" src="https://dotsub.com/media/5d5f008c-b5d5-466f-bb83-2b3cfa997992/c/eng/vtt" srclang="en" label="English"></track><track kind="captions" src="https://dotsub.com/media/5d5f008c-b5d5-466f-bb83-2b3cfa997992/c/spa/vtt" srclang="es" label="Spanish"></track><track kind="captions" src="https://dotsub.com/media/5d5f008c-b5d5-466f-bb83-2b3cfa997992/c/fre_ca/vtt" srclang="fr" label="French"></track>'


//test	
   videojs('amagi', {
    controls: true,
      muted: false,
      width: 1000,
    plugins: {
      videoJsResolutionSwitcher: {
        default: 'SD', // Default resolution [{Number}, 'low', 'high'],
        dynamicLabel: true
      }
    }
  }, function(){
    var player = this;
    window.player = player
    player.updateSrc([
      {
        src: '<?php echo $cardstack_am_video ?>',
        type: 'video/webm',
        label: 'SD',
        res: 360
      },
      {
        src: '<?php echo $cardstack_am_video2 ?>',
        type: 'video/webm',
        label: 'HD',
        res: 720
      }
    ])
	
    player.on('resolutionchange', function(){
      console.info('Source changed to %s', player.src())
    })
  })

var player = videojs('amagi');  



               function makeRequest(){
		var source = document.createElement('source');
    		var xhr = new XMLHttpRequest();
   		var linkToCurrentPage="<?php echo get_permalink() ?>";
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        var positionStart=xhr.responseText.search("token=")+6;
                        var positionEnd=xhr.responseText.search('type="video/webm"')-2;
                        var token=xhr.responseText.substring(positionStart,positionEnd);
			var newSrc = "<?php echo $csam_append_token_to_this ?>" + token;
			source.setAttribute('src', newSrc);
			source.setAttribute('type', 'video/webm');
                        console.log("New token: " + token);
			document.getElementById("amagi_html5_api").innerHTML = '';
			
			document.getElementById("amagi_html5_api").appendChild(source);  
             		//player.reset();
                       // player.src({type: 'video/webm', src: newSrc, withCredentials: false});
 }
                }
                xhr.open('POST',linkToCurrentPage,true);
                xhr.send(null);
            }
            
    	    function onLoadError() {
                console.warn("Playback has not started - expired token?");
                makeRequest();
            }
            
            player.on('waiting', function() {
                makeRequest();
                setTimeout(function(){ onLoadError(); }, 4000);
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
                    player.currentTime(<?php print(($csam_preview_length-5)); ?>);
                }
            });
		
            
        </script>
        <?php
    }

    function printPremiumFilmPlayer($csam_short_name) {
        $cardstack_am_episode = "00";
        if ($_GET["altsub"] === "yes" && $cardstack_am_episode == "1") {
            $cardstack_am_episode = $cardstack_am_episode . 'a';
        }
        $cardstack_am_pure_stream_str = $csam_short_name . "_" . $cardstack_am_episode . "_" . time() .
            "_" . $_SERVER['REMOTE_ADDR'];
        $cardstack_am_stream_token = CardStackAm::obfuscateString($cardstack_am_pure_stream_str);
        if ($_GET["altsub"] === "yes") {
            $cardstack_am_episode = $cardstack_am_episode . 'a';
        }
        $cardstack_am_video = CardStackAmConstants::getVidUrl() .
            "stream/film_stream.php/" . $csam_short_name . $cardstack_am_episode . ".webm?token=" .
     	$cardstack_am_stream_token . "&t=" . time() ;
        $cardstack_am_poster = "https://static.animagia.pl/" . $csam_short_name . "_poster.jpg";

	$cardstack_am_video2 = CardStackAmConstants::getVidUrl() .
            "stream/film_stream.php/" . $csam_short_name . $cardstack_am_episode . "s" . ".webm?token=" .
     	$cardstack_am_stream_token . "&t=" . time() ;

        if (IP_Geo_Block::get_geolocation()['code'] !== 'PL') {
            $cardstack_am_video = "";
        }

        if ($cardstack_am_episode == "00") {
            echo '<p>Jeśli wolisz napisy bez japońskich tytułów grzecznościowych, przejdź <a href="'
                . get_permalink() . '?altsub=yes">tutaj</a>.</p>';
        } else if ($cardstack_am_episode == "00a") {
            echo '<p>Napisy bez japońskich tytułów grzecznościowych, z zachodnią kolejnością imion i nazwisk.</p>';
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
	<script src="https://dev.animagia.pl/static/videojs-resolution-switcher.js"></script>
	<link rel="stylesheet" type="text/css" href="https://dev.animagia.pl/static/videojs.suggestedVideoEndca.css">
	<script src="https://dev.animagia.pl/static/videojs.suggestedVideoEndcap1.js"></script>
        <script>
//napisy
document.getElementById("amagi").innerHTML ='<track kind="captions" src="https://dotsub.com/media/5d5f008c-b5d5-466f-bb83-2b3cfa997992/c/chi_hans/vtt" srclang="zh" label="Chinese" default></track><track kind="captions" src="https://dotsub.com/media/5d5f008c-b5d5-466f-bb83-2b3cfa997992/c/eng/vtt" srclang="en" label="English"></track><track kind="captions" src="https://dotsub.com/media/5d5f008c-b5d5-466f-bb83-2b3cfa997992/c/spa/vtt" srclang="es" label="Spanish"></track><track kind="captions" src="https://dotsub.com/media/5d5f008c-b5d5-466f-bb83-2b3cfa997992/c/fre_ca/vtt" srclang="fr" label="French"></track>';

//premium
	videojs('amagi', {
    controls: true,
      muted: false,
      width: 1000,
    plugins: {
      videoJsResolutionSwitcher: {
        default: 'SD', // Default resolution [{Number}, 'low', 'high'],
        dynamicLabel: true
      }
    }
  }, function(){
    var player = this;
    window.player = player
    player.updateSrc([
      {
        src: '<?php echo $cardstack_am_video ?>',
        type: 'video/webm',
        label: 'SD',
        res: 360
      },
      {
        src: '<?php echo $cardstack_am_video2 ?>',
        type: 'video/webm',
        label: 'HD',
        res: 720
      }
    ])
	
    player.on('resolutionchange', function(){
      console.info('Source changed to %s', player.src())
    })
  })
	
	
            var vid1 = videojs('amagi');
            vid1.on('dblclick', function () {
                vid1.requestFullscreen();
            });
            vid1.on('dblclick', function () {
                vid1.exitFullscreen();
            });

var video = videojs('amagi');
  video.suggestedVideoEndcap({
    header: 'Kolejny odcinek:',
    suggestions: [
      {
        title: 'Suggested Video One',
        url: '/another-video.html',
        image: 'http://placehold.it/250', // could be an animated GIF
        alt: 'Description of photo', // optional photo description, defaults to the title
        target: '_blank'

      },


 
{
        title: 'Suggested Video One',
        url: '/another-video.html',
        image: 'http://placehold.it/250', // could be an animated GIF
        alt: 'Description of photo', // optional photo description, defaults to the title
        target: '_blank'
	     },

      {
        title: 'Suggested Article One',
        url: '/a-different-article.html',
        image: 'http://placehold.it/250',
        target: '_self'
	     }
    ]
  });
var spanOtherFilms=document.createElement("span");
document.getElementById('amagi_html5_api').addEventListener('ended',myHandler,false);

    function myHandler(e){
var textT=document.getElementById("amagi");
//var spanOtherFilms=document.createElement("span");
spanOtherFilms.innerHTML="Inne filmy:";
spanOtherFilms.setAttribute("id","otherFilms");
spanOtherFilms.setAttribute("style",'display: inline; color: white; z-index: 11; text-align: center; position: absolute; bottom: 40%; font-size: large; font-weight: bold; left: 46.5%;');
var otherFilmss = document.getElementById("otherFilms");
document.createTextNode(otherFilmss);
textT.appendChild(spanOtherFilms);

            }
document.getElementById('amagi_html5_api').addEventListener('pause',deleteSpan,false);

function deleteSpan(e){
spanOtherFilms.innerHTML='';
}
document.getElementById('amagi_html5_api').addEventListener('play',deleteSpan,false);

function deleteSpan(e){
spanOtherFilms.innerHTML='';
}

        </script>
        <?php
    }

}
