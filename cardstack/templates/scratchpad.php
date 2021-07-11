<?php
/*
  Template Name: Scratchpad
 */

require_once( __DIR__ . '/../includes/video-player.php' );
?>


<?php get_header(); ?>

<main<?php
if (is_active_sidebar(1)) {
    print(' class="with-sidebar"');
}
?>><article class="page<?php
          if (get_theme_mod('page_breadcrumbs')) {
              print(' has-breadcrumbs');
          }
          ?>">

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>

                <h1><?php the_title(); ?></h1>

                <?php
                if (!is_user_logged_in()) :

                    wp_login_form();

                else :
                    ?>

                    <p>Custom paragraph.</p>


                    <?php

						$csam_film = json_decode(get_the_content());
                        
						//echo("<pre>");
						//var_dump($csam_can_stream);
						//echo("</pre>");
						
                        $csam_can_stream = //false;
								CardStackAm::userCanStreamProduct($csam_film->{"idInWc"});

                        if($csam_can_stream) {

							$csam_translation_choice="csam_honorifics";

							$csam_short_name = CsAmVideo::getPremiumFilenameBySku($csam_film->{"sku"});
							
							$csam_video_poster = "https://static.animagia.pl/" . $csam_short_name .
									"_poster.jpg";
							
							$cardstack_am_episode = "00";
							$cardstack_am_pure_stream_str = $csam_short_name . "_" . $cardstack_am_episode . "_" . time() .
									"_" . $_SERVER['REMOTE_ADDR'];
							$csam_stream_token = CardStackAm::obfuscateString($cardstack_am_pure_stream_str);
							if ($_GET["altsub"] === "yes") {
								$cardstack_am_episode = $cardstack_am_episode . 'a';
								$csam_translation_choice="csam_no_honorifics";
							} else if ($_GET["dub"] === "yes" && $csam_short_name === "HanaIro" ) {
								$cardstack_am_episode = $cardstack_am_episode . 'd';
								$csam_translation_choice="csam_dub";
							}
							$csam_video_stream = CardStackAmConstants::getVidUrl() .
									"stream/film_stream.php/" . $csam_short_name .
									$cardstack_am_episode . ".webm?token=" .
									$csam_stream_token;

						} else {

							$csam_short_name = 
									CsAmVideo::getFreeFilenameBySku($csam_film->{"sku"});

							$csam_video_poster = "https://static.animagia.pl/" . $csam_short_name .
									"_poster.jpg";

							$cardstack_am_episode = "2";
							$cardstack_am_pure_stream_str = $csam_short_name . "_2_" . time() .
									"_" . $_SERVER['REMOTE_ADDR'];
							$csam_stream_token =
									CardStackAm::obfuscateString($cardstack_am_pure_stream_str);
							$csam_append_token_to_this = CardStackAmConstants::getVidUrl() .
									"stream/film_stream.php/" . $csam_short_name .
									$cardstack_am_episode . ".webm?token=";
							$csam_video_stream = $csam_append_token_to_this . $csam_stream_token;
							
							$csam_film_duration =
									intval(explode(" ", $csam_film->{"duration"})[0]) * 60000;

							$csam_locked_start = 100 *
									(intval($csam_film->{"previewMillis"}) / $csam_film_duration);
							$csam_locked_width = 100 - $csam_locked_start;

						}

						if (IP_Geo_Block::get_geolocation()['code'] !== 'PL') {
							$csam_video_stream = "";
						}

					?>
 
					<figure id="csam-vid-container" class="csam-vid-container csam-standby" >
	
						<video id="csam-vid"
							   oncontextmenu="return false;"
							   poster="<?php echo $csam_video_poster ?>" preload="metadata" >
							<source src="<?php echo $csam_video_stream ?>" type="video/webm" />
						</video>
						
						<svg id="csam-video-state-indicator"  viewBox="0 0 40 40" >
							<polygon points="10,8 10,32 33,20" />
						</svg>
						
						<ul>
							<li><button id="csam-playpause" type="button">
								<svg viewBox="0 0 40 40">
									<polygon points="6,6 6,34 34,20" />
								</svg>
								<svg viewBox="0 0 40 40" style="display:none;" >
									<rect x="10" y="10" width="5" height="20" />
									<rect x="25" y="10" width="5" height="20" />
								</svg>
							</button>
							</li>
							<li class="csam-range-wrapper">
                                <svg id="csam-seekbar-backdrop" viewbox="0 0 1000 40" preserveAspectRatio="none">
                                    <defs>
                                        <pattern id="stripes" width="40" height="40" patternUnits="userSpaceOnUse" >
                                            <rect x="0" y="0" width="20" height="40" fill="yellow" />
                                        </pattern>
                                    </defs>

                                    <rect x="0" y="17" width="100%" height="6" />
                                    <rect x="<?php echo $csam_locked_start ?>%"
                                            y="17"
                                            width="<?php echo $csam_locked_width ?>%"
                                            height="6"
                                            fill="url(#stripes)" />
                                </svg>
								<input 
									 id="csam-vid-progress" type="range" min="0" max="1000" step="1" value="0"
									 oninput="csamSeekVideo(this.value)" onchange="csamSeekVideo(this.value)"
									 disabled
									 />
							</li>
							<li id="csam-video-timestamp"></li>
							<li style="position: relative;">
								<ul id="csam-translation-options"
										style="display: none;"
										class="<?php echo($csam_translation_choice); ?>">
									<li onclick="csamSwitchTranslation(this)">Polskie napisy + japońskie tyt. grzecznościowe</li>
									<li onclick="csamSwitchTranslation(this)">Polskie napisy</li>
									<?php if($csam_short_name === "HanaIro") {
										echo("<li onclick=\"csamSwitchTranslation(this)\">Polski dubbing</li>");
									} ?>
								</ul>
								<button id="csam-translation-choice" type="button">
									<svg viewBox="0 0 24 24">
										  <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1
												   0 2-.9 2-2V6c0-1.1-.9-2-2-2zM4 12h4v2H4v-2zm10
												   6H4v-2h10v2zm6 0h-4v-2h4v2zm0-4H10v-2h10v2z" />	
									</svg>
								</button>
							</li>
							<li>
								<button id="csam-mute" type="button">
								<svg viewBox="0 0 40 40" style="display:none;">
									<path
										id="path4"
										d="m 27.5,20 c 0,-2.95 -1.7,-5.483333 -4.166667,-6.716667 v 3.683334 L
										   27.416667,21.05 C 27.466667,20.716667 27.5,20.366667 27.5,20 Z m 4.166667,0
										   c 0,1.566667 -0.333334,3.033333 -0.9,4.4 l 2.516666,2.516667 C 34.383333,24.85
										   35,22.5 35,20 35,12.866667 30.016667,6.9 23.333333,5.3833333 V 8.8166667
										   C 28.15,10.25 31.666667,14.716667 31.666667,20 Z M 7.1166667,5 5,7.1166667
										   12.883333,15 H 5 v 10 h 6.666667 L 20,33.333333 V 22.116667 L 27.083333,29.2
										   c -1.116666,0.866667 -2.366666,1.55 -3.75,1.966667 V 34.6 c 2.3,-0.516667
										   4.383334,-1.583333 6.15,-3.016667 L 32.883333,35 35,32.883333 l -15,-15 z
										   M 20,6.6666667 16.516667,10.15 20,13.633333 Z"
										/>
								</svg>
								<svg viewBox="0 0 40 40">
									<path
										  d="m 5,15 v 10 h 6.666667 L 20,33.333333 V 6.666667 L 11.666667,15 Z m 22.5,5 c 0,-2.95
											 -1.7,-5.483333 -4.166667,-6.716667 V 26.7 C 25.8,25.483333 27.5,22.95 27.5,20 Z
											 M 23.333333,5.383333 V 8.816667 C 28.15,10.25 31.666667,14.716667 31.666667,20
											 c 0,5.283333 -3.516667,9.75 -8.333334,11.183333 v 3.433334 C 30.016667,33.1 35,27.133333
											 35,20 35,12.866667 30.016667,6.9 23.333333,5.383333 Z"
										  />
								</svg>
							</button>
							</li>
							<li class="csam-range-wrapper">
                                <svg id="csam-seekbar-backdrop" viewbox="0 0 1000 40" preserveAspectRatio="none">
                                    <rect x="0" y="17" width="100%" height="6" fill="#555" />
                                </svg>
								<input 
									 id="csam-vol-control" type="range" min="0" max="100" step="1" 
									 oninput="csamOnVolumeChange(this.value)" onchange="csamOnVolumeChange(this.value)" />
							</li>
							<li><button id="csam-fs-toggle" type="button">
								<svg viewBox="4 4 32 32">
									<path
										  d="M 12.857143,22.857143 H 10 V 30 h 7.142857 V 27.142857
											 H 12.857143 Z M 10,17.142857 h 2.857143 v -4.285714
											 h 4.285714 V 10 H 10 Z m 17.142857,10 H 22.857143 V 30
											 H 30 V 22.857143 H 27.142857 Z M 22.857143,10 v 2.857143 h 4.285714 v 4.285714 H 30 V 10 Z"
										  />
								</svg>
							</button></li>
						</ul>
						
						<?php if(!$csam_can_stream) : ?>
						
							<aside id="csam-purchase-prompt">
								
								<img src="<?php echo($csam_film->{"posterAssetUri"}); ?>" />
								
							
								<p>Kup pełną wersję filmu:<br />
								<strong>Hanasaku Iroha: Home Sweet Home</strong><br />
								do obejrzenia online i ściągnięcia.</p>
								<p>24,90 zł</p>
								<p><a href="https://animagia.pl/koszyk/?add-to-cart=<?php
										echo($csam_film->{"shopId"}); ?>" >Do koszyka</a></p>
								<p><a href="https://animagia.pl/sklep/#<?php
										echo($csam_film->{"sku"}); ?>" >Szczegóły</a></p>
								
								<p>Lub</p>
								
								<p>załóż <strong>konto premium</strong>
								i oglądaj online wszystkie filmy w naszym katalogu.</p>
								<p>15,00 zł / miesiąc</p>
								<p><a href="https://animagia.pl/koszyk/?add-to-cart=78">Do koszyka</a></p>
								<p><a href="https://animagia.pl/sklep/#animagia-pl-premium">Szczegóły</a></p>
								
							</aside>
						
						<?php endif; ?>

					</figure>

<script>
const csamCanStream = <?php echo($csam_can_stream ? "true" : "false"); ?>;

var csamVideo = document.getElementById('csam-vid');
var csamVidProgress = document.getElementById('csam-vid-progress');
var csamVolControl = document.getElementById('csam-vol-control');
var csamVidTimestamp = document.getElementById('csam-video-timestamp');
var csamVidWithControls = document.getElementById('csam-vid-container');
var csamTranslationPicker = document.getElementById('csam-translation-choice');
var csamTranslationOptions = document.getElementById('csam-translation-options');
var csamPreviewMillis = <?php echo($csam_film->{"previewMillis"}); ?>;
var csamSeekInProgress = false;

var csamSeekbarBackground = document.querySelector('figure.csam-vid-container > ul > li:nth-child(2) > svg');

var csamCursorTimeoutId;
var csamTouchTimeoutId;

var csamVolumeBeforeMute = (null === localStorage.getItem("csamPlayerVolume") ? 
		100 : localStorage.getItem("csamPlayerVolume"));
csamVolControl.value = csamVolumeBeforeMute;
csamVideo.volume = csamVolumeBeforeMute / 100;

document.getElementById('csam-playpause').addEventListener('click', csamTogglePlayback);
csamVideo.addEventListener('touchend', function(ev) {
	ev.preventDefault();
	if(ev.target.parentNode.classList.contains("csam-standby")) {
		csamTogglePlayback();
	}
	csamToggleTouchControls();
});
csamVidWithControls.addEventListener('touchend', csamScheduleHideTouchControls);
csamVideo.addEventListener('click', csamTogglePlayback);
document.getElementById('csam-fs-toggle').addEventListener('click', csamToggleFs);
csamVideo.addEventListener('dblclick', csamToggleFs);

csamTranslationPicker.addEventListener('click', function(e) {
	if("block" === csamTranslationOptions.style.display) {
		csamTranslationOptions.style.display = "none";
	} else {
		csamTranslationOptions.style.display = "block";
	}
});

document.getElementById('csam-mute').addEventListener('click', function(e) {
	if(csamVideo.muted) {
		csamVolControl.value = csamVolumeBeforeMute;
		csamVideo.volume = csamVolumeBeforeMute / 100;
		document.querySelector("#csam-mute>svg:first-child").style.display="none";
		document.querySelector("#csam-mute>svg:last-child").style.display="inline";
		csamVideo.muted = false;
	} else {
		csamVolumeBeforeMute = csamVolControl.value;
		csamVolControl.value = 0;
		csamVideo.volume = 0;
		document.querySelector("#csam-mute>svg:first-child").style.display="inline";
		document.querySelector("#csam-mute>svg:last-child").style.display="none";
		csamVideo.muted = true;
	}
});

csamVideo.addEventListener('loadedmetadata', function() {
   csamVidProgress.setAttribute('max', csamVideo.duration * 1000);
   csamVidProgress.disabled = false;
	
	csamVidTimestamp.textContent = "0:00:00 / " +
		Math.floor(csamVideo.duration / 3600) + ":" +
		("0" + Math.floor((csamVideo.duration % 3600) / 60)).slice(-2) + 
		":" + ("0" + Math.floor(csamVideo.duration % 60)).slice(-2);
		
	
});


csamVideo.addEventListener('timeupdate', function() {

	csamVidProgress.value = csamVideo.currentTime * 1000;

	csamVidTimestamp.textContent = 
		Math.floor(csamVideo.currentTime / 3600) + ":" +
		("0" + Math.floor((csamVideo.currentTime % 3600) / 60)).slice(-2) + 
		":" + ("0" + Math.floor(csamVideo.currentTime % 60)).slice(-2) +
		" / " +
		Math.floor(csamVideo.duration / 3600) + ":" +
		("0" + Math.floor((csamVideo.duration % 3600) / 60)).slice(-2) + 
		":" + ("0" + Math.floor(csamVideo.duration % 60)).slice(-2);

	if(!csamCanStream) {
		if(csamVideo.currentTime * 1000 > csamPreviewMillis) {
			csamVideo.currentTime = (csamPreviewMillis / 1000) - 1;
			csamVideo.pause();
			csamVidWithControls.classList.add("csam-whiny");
		} else if (csamVideo.currentTime < (csamPreviewMillis / 1000) - 1) {
			csamVidWithControls.classList.remove("csam-whiny");
		}
	}

});




csamVideo.addEventListener('pause', function() {
	if(!csamSeekInProgress) {
		document.querySelector("#csam-playpause>svg:first-child").style.display="inline";
		document.querySelector("#csam-playpause>svg:last-child").style.display="none";
	}
});
csamVideo.addEventListener('play', function() {
	if(!csamSeekInProgress) {
		csamVidWithControls.classList.remove("csam-standby");
		document.querySelector("#csam-playpause>svg:first-child").style.display="none";
		document.querySelector("#csam-playpause>svg:last-child").style.display="inline";
	}
});
	
function csamTogglePlayback() {
	if (csamVideo.paused || csamVideo.ended) {
		csamVideo.play();
	} else {
		csamVideo.pause();
	}
}

function csamHandleFsMouseMove(e) {
	csamShowControls();
	csamScheduleHideControls();
}

function csamToggleTouchControls() {
	csamVidWithControls.classList.toggle("csam-recently-touched");
}

function csamScheduleHideTouchControls() {
	window.clearTimeout(csamTouchTimeoutId);
	csamTouchTimeoutId = window.setTimeout(function() {
		csamVidWithControls.classList.remove("csam-recently-touched");
	}, 2000);
}

function csamScheduleHideControls() {
	window.clearTimeout(csamCursorTimeoutId);
	csamCursorTimeoutId = window.setTimeout(csamHideControls, 2000);
}
	
function csamHideControls() {
	if(null === document.querySelector("figure.csam-vid-container>ul:hover"))
	{
		csamVidWithControls.classList.add("csam-idle-pointer");
	}
}
	
function csamShowControls() {
	window.clearTimeout(csamCursorTimeoutId);
	csamVidWithControls.classList.remove("csam-idle-pointer");
}
	
document.getElementById("csam-fs-toggle").addEventListener('click', csamToggleFs);
	
function csamToggleFs() {
   if(document.fullscreen) {
	    document.exitFullscreen();
   } else {
		csamVidWithControls.requestFullscreen();
   }
}
	
csamVidWithControls.addEventListener('fullscreenchange', (event) =>
{
	if (document.fullscreenElement) {
		csamScheduleHideControls();
		csamVidWithControls.addEventListener('mousemove', csamHandleFsMouseMove );
	} else {
        csamShowControls();
        csamVidWithControls.removeEventListener('mousemove', csamHandleFsMouseMove );
	}
});

var csamWasPlayingBeforeSeek = false;
	
csamVidProgress.addEventListener('mousedown', function(e) {
   csamSuspendPlayback();
});

csamVidProgress.addEventListener('mouseup', function(e) {
   csamUnsuspendPlayback();
});

function csamOnVolumeChange(val)
{
	csamVideo.volume = val / 100;
	localStorage.setItem("csamPlayerVolume", val);
}

function csamSeekVideo(val)
{
   csamVideo.currentTime = val / 1000;
}
	
function csamSuspendPlayback() {
	csamSeekInProgress = true;
	csamWasPlayingBeforeSeek = !csamVideo.paused;
	csamVideo.pause();
}
	
function csamUnsuspendPlayback() {
	csamSeekInProgress = false;
	if(csamWasPlayingBeforeSeek) {
		csamVideo.play();
	}
}

function csamSwitchTranslation(element) {
	csamTranslationOptions.style.display = "none";
	if(document.querySelector("#csam-translation-options>:nth-child(1)") === element) {
		csamTranslationOptions.classList.remove("csam_no_honorifics", "csam_dub");
		csamTranslationOptions.classList.add("csam_honorifics");
		csamGetNewVidUrl("?altsub=no");
	} else if(document.querySelector("#csam-translation-options>:nth-child(2)") === element) {
		csamTranslationOptions.classList.remove("csam_no_honorifics", "csam_dub");
		csamTranslationOptions.classList.add("csam_no_honorifics");
		csamGetNewVidUrl("?altsub=yes");
	} else if(document.querySelector("#csam-translation-options>:nth-child(3)") === element) {
		csamTranslationOptions.classList.remove("csam_honorifics", "csam_no_honorifics");
		csamTranslationOptions.classList.add("csam_dub");
		csamGetNewVidUrl("?dub=yes");
	}
}

function csamGetNewVidUrl(queryString) {
	var vidReq = new XMLHttpRequest();
	vidReq.addEventListener("load", csamOnNewVidUrlFetched);
	vidReq.open("GET", "https://animagia.pl/scratchpad" + queryString);
	vidReq.send();
}

function csamOnNewVidUrlFetched() {
	//console.log(this.responseText);
	var start = this.responseText.indexOf("source src=\"") + 12;
	var end = this.responseText.indexOf("\"", start);
	//console.log("Start/end: " + start + " " + end);
	var newUrl = this.responseText.substring(start, end);
  
  
	var source = document.createElement('source');
	source.setAttribute("src", newUrl);
	
	//console.log("Trying to replace " + csamVideo.firstChild + " with " + source);
	
	while (csamVideo.firstChild) {
		csamVideo.removeChild(csamVideo.firstChild);
	}
	
	csamVideo.appendChild(source);
	
	csamVideo.load();
}

</script>

                <?php endif; ?>

                <?php comments_template(); ?>

            <?php endwhile; ?>
<?php endif; ?>

    </article></main>

<?php get_footer(); ?>



