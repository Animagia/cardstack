<?php
/*
  Template Name: New film player
 */

require_once( __DIR__ . '/../includes/video-player.php' );
?>


<?php get_header(); ?>

<article class="page<?php
          if (get_theme_mod('page_breadcrumbs')) {
              print(' has-breadcrumbs');
          }
          ?>">

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>

                <?php
				if (is_front_page()) {
                    echo '<h2 class="demoted-title">';
                    the_title();
                    echo '</h2>';
                } else {
                    echo '<h1>';
                    the_title();
                    echo '</h1>';
                }
                ?>

                <?php

						$csam_film = json_decode(get_the_content());
						$csam_film_duration =
								intval(explode(" ", $csam_film->{"duration"})[0]) * 60000;
						$csam_vid_thumbnail = "https://static.animagia.pl/posters/" .
								$csam_film->{"sku"} . "_thumbnail.jpg";
						
						$csam_converted_timestamps = array();
						foreach(explode(";",$csam_film->{"timeStamps"}) as $csam_chapter_stamp) {
							$csam_stamp_parts = preg_split("/:|\./", $csam_chapter_stamp);
							$csam_chapter_millis = 60 * 60 * 1000 * intval($csam_stamp_parts[0]) +
									60 * 1000 * intval($csam_stamp_parts[1]) +
									1000 * intval($csam_stamp_parts[2]) +
									intval($csam_stamp_parts[3]);
							array_push($csam_converted_timestamps, $csam_chapter_millis);
						}
						
						$csam_translation_choice="csam_honorifics";
						
                        $csam_can_stream =
								CardStackAm::userCanStreamProduct($csam_film->{"idInWc"});
						
						if(is_front_page()) {
							echo("<p>Film kinowy pełen ciepła, rodzinnej miłości i zmagań życia
								codziennego w niewielkim, ale pięknym miasteczku Yunosagi.
								Wszstko to w kompletnej, starannie przygotowanej polskiej wersji
								językowej z dubbingiem grupy NanoKarrin, a także napisami do wyboru.</p>");
						} else {
							echo("<p>" . $csam_film->{"description"} . "</p>");
						}
						
						if(!$csam_can_stream) {
							echo("<p>Streaming bezpłatny z ograniczonym czasem oglądania, " .
								"całość dostępna w <a target=\"_blank\" href=\"https://animagia.pl/sklep/#" . 
								$csam_film->{"sku"} .
								"\">cyfrowej kopii</a> " .
								"oraz dla " .
								"<a target=\"_blank\" href=\"https://animagia.pl/sklep/#animagia-pl-premium\">" .
								"kont premium</a>.");
						}

                        if($csam_can_stream) {

							$csam_token_blueprint = $csam_film->{"sku"} . "_00_" . time() .
									"_" . $_SERVER['REMOTE_ADDR'];
							$csam_stream_token = CardStackAm::obfuscateString($csam_token_blueprint);

							$csam_base_vid_url = CardStackAmConstants::getVidUrl();
							$csam_filename = $csam_film->{"sku"} . ".webm";

							if ($_GET["altsub"] === "yes") {
								$csam_base_vid_url = CardStackAmConstants::getAlternateVidUrl();
								$csam_filename = $csam_film->{"sku"} . "_a.webm";
								$csam_translation_choice="csam_no_honorifics";
							} else if ($_GET["dub"] === "yes" && 1 === $csam_film->{"hasDub"} ) {
								$csam_filename = $csam_film->{"sku"} . "_d.webm";
								$csam_translation_choice="csam_dub";
							}							
							
							$csam_video_stream = $csam_base_vid_url .
									"stream/film_stream.php/" . $csam_filename . "?token=" .
									$csam_stream_token;

						} else {

							$csam_token_blueprint = $csam_film->{"sku"} . "_2_" . time() .
									"_" . $_SERVER['REMOTE_ADDR'];
							$csam_stream_token = CardStackAm::obfuscateString($csam_token_blueprint);

							$csam_base_vid_url = CardStackAmConstants::getVidUrl();
							$csam_filename = $csam_film->{"sku"} . "_p.webm";
									
							if("kon" === $csam_film->{"sku"}) {
								$csam_append_token_to_this = CardStackAmConstants::getAlternateVidUrl() .
										"stream/film_stream.php/" . $csam_film->{"sku"} .
										"_p" . ".webm?token=";
							}
							
							$csam_video_stream = $csam_base_vid_url .
									"stream/film_stream.php/" . $csam_filename . "?token=" .
									$csam_stream_token;

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
							   poster="<?php echo $csam_vid_thumbnail ?>" preload="metadata" >
							<source src="<?php echo $csam_video_stream ?>" type="video/webm" />
						</video>
						
						<svg id="csam-video-state-indicator"  viewBox="0 0 40 40" >
							<polygon points="10,8 10,32 33,20" />
							<path fill="white"
								d="m 30,20 c 0,5.52 -4.48,10 -10,10 -5.52,0 -10,-4.48 -10,-10 0,-1.19 0.22,-2.32 0.6,-3.38 l 1.88,0.68 C 12.17,18.14 12,19.05 12,20 c 0,4.41 3.59,8 8,8 4.41,0 8,-3.59 8,-8 0,-4.41 -3.59,-8 -8,-8 -0.95,0 -1.85,0.17 -2.69,0.48 L 16.63,10.59 C 17.69,10.22 18.82,10 20,10 c 5.52,0 10,4.48 10,10 z" />
						</svg>
						
						<ul>
							<li><button id="csam-playpause" type="button">
								<svg viewBox="0 0 40 40">
									<polygon points="7,7 7,33 33,20" />
								</svg>
								<svg viewBox="0 0 40 40" style="display:none;" >
									<rect x="10" y="10" width="6" height="20" />
									<rect x="24" y="10" width="6" height="20" />
								</svg>
							</button>
							</li>
							<li><button id="csam-prevchapter"
									type="button" onclick="csamPrevChapter()">
								<svg viewBox="0 0 40 40">
									<rect x="1" y="8" width="4" height="24" />
									<polygon points="5,20 18,7 18,33" />
									<polygon points="18,20 31,7 31,33" />
								</svg>
							</button></li>
							<li><button id="csam-nextchapter"
									type="button"  onclick="csamNextChapter()">
								<svg viewBox="0 0 40 40">
									<polygon points="1,7 1,33 14,20" />
									<polygon points="14,7 14,33 27,20" />
									<rect x="27" y="8" width="4" height="24" />
								</svg>
							</button></li>
							<li class="csam-seekbar">
                                <svg viewbox="0 0 1000 40" preserveAspectRatio="none">
                                    <defs>
                                        <pattern id="stripes" width="40" height="40" patternUnits="userSpaceOnUse" >
                                            <rect x="0" y="0" width="20" height="40" fill="yellow" />
                                        </pattern>
                                    </defs>

                                    <rect x="0" y="17" width="100%" height="6" />
                                    
                                    <?php if(!$csam_can_stream):
										for($csam_stripe = $csam_locked_start;
											$csam_stripe < 100; $csam_stripe+=6): ?>
										<rect
												x="<?php echo($csam_stripe); ?>%"
												y="17"
												width="3%"
												height="6"
												fill="rgb(76,47,62)" />
									<?php endfor; endif; ?>

                                    <?php foreach($csam_converted_timestamps as $csam_stamp_millis):
										$csam_chapter_position =
												100 * $csam_stamp_millis / $csam_film_duration; ?>
										<rect class="csam-chapter-marker"
												x="<?php echo($csam_chapter_position); ?>%"
												y="17"
												width="5"
												height="6" />
									<?php endforeach; ?>
                                </svg>
								<input 
									 id="csam-vid-progress" type="range" min="0" max="1000" step="1" value="0"
									 oninput="csamSeekVideo(this.value)" onchange="csamSeekVideo(this.value)"
									 disabled
									 />
								<time style="color: #aaa; display: none;" />
							</li>
							<li id="csam-video-timestamp"><span></span><span></span></li>
							<li style="position: relative;">
								<ul id="csam-translation-options"
										style="display: none;"
										class="<?php echo($csam_translation_choice); ?>">
									<li onclick="csamSwitchTranslation(this)">Polskie napisy + japońskie tyt. grzecznościowe</li>
									<li <?php if($csam_can_stream) {
											echo("onclick=\"csamSwitchTranslation(this)\"");
										} else {
											echo("class=\"csam-locked-translation\"");
										} ?>>Polskie napisy</li>
									<?php if(1 == intval($csam_film->{"hasDub"}) && $csam_can_stream) {
										echo("<li onclick=\"csamSwitchTranslation(this)\">Polski dubbing</li>");
									} else if(1 == intval($csam_film->{"hasDub"})) {
										echo("<li class=\"csam-locked-translation\">Polski dubbing</li>");
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
							<li class="csam-seekbar">
                                <svg viewbox="0 0 1000 40" preserveAspectRatio="none">
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
								<strong><?php echo(get_the_title()); ?></strong><br />
								do obejrzenia online i ściągnięcia.</p>
								<p><?php echo($csam_film->{"priceInWc"}); ?></p>
								<p><a target="_blank" href="https://animagia.pl/koszyk/?add-to-cart=<?php
										echo($csam_film->{"idInWc"}); ?>" >Do koszyka</a></p>
								<p><a target="_blank" href="https://animagia.pl/sklep/#<?php
										echo($csam_film->{"sku"}); ?>" >Szczegóły</a></p>
								
								<p>Lub</p>
								
								<p>załóż <strong>konto premium</strong>
								i oglądaj online wszystkie filmy w naszym katalogu.</p>
								<p>15,00 zł / miesiąc</p>
								<p><a target="_blank" href="https://animagia.pl/koszyk/?add-to-cart=78">Do koszyka</a></p>
								<p><a target="_blank" href="https://animagia.pl/sklep/#animagia-pl-premium">Szczegóły</a></p>
								
							</aside>
						
						<?php endif; ?>

					</figure>

<script>

const csamPageUrl = "<?php echo( $_SERVER['REQUEST_URI'] ) ?>";

const csamConvertedTimestamps = [
	0, <?php echo(implode(", ", $csam_converted_timestamps)); ?>
];

const csamCanStream = <?php echo($csam_can_stream ? "true" : "false"); ?>;

var csamVideo = document.getElementById('csam-vid');
var csamVidProgress = document.getElementById('csam-vid-progress');
var csamVolControl = document.getElementById('csam-vol-control');
var csamVidTimestamp = document.getElementById('csam-video-timestamp');
var csamVidWithControls = document.getElementById('csam-vid-container');
var csamTranslationPicker = document.getElementById('csam-translation-choice');
var csamTranslationOptions = document.getElementById('csam-translation-options');
const csamPreviewMillis = <?php echo($csam_film->{"previewMillis"}); ?>;
var csamSeekInProgress = false;

var csamSeekbar = document.querySelector('li.csam-seekbar');
var csamTargetTime = document.querySelector('li.csam-seekbar > time');

var csamCursorTimeoutId;
var csamTouchTimeoutId;

var csamTranslationChangeInProgress = false;
var csamWasPlayingBeforeTranslationChange = false;
var csamTimestampBeforeTranslationChange = 0;

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
csamVideo.addEventListener('loadeddata', csamOnVideoReadyToPlay);

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

	let displayedDuration = Math.floor(csamVideo.duration / 3600) + ":" +
		("0" + Math.floor((csamVideo.duration % 3600) / 60)).slice(-2) + 
		":" + ("0" + Math.floor(csamVideo.duration % 60)).slice(-2);

	csamVidTimestamp.children[0].textContent = "0:00:00 / " + displayedDuration;
	csamVidTimestamp.children[1].textContent = "-" + displayedDuration;
});


csamVideo.addEventListener('timeupdate', function() {
	if(!csamTranslationChangeInProgress) {
		csamVidWithControls.classList.remove("csam-buffering");
	}

	csamVidProgress.value = csamVideo.currentTime * 1000;

	csamVidTimestamp.children[0].textContent = 
		Math.floor(csamVideo.currentTime / 3600) + ":" +
		("0" + Math.floor((csamVideo.currentTime % 3600) / 60)).slice(-2) + 
		":" + ("0" + Math.floor(csamVideo.currentTime % 60)).slice(-2) +
		" / " +
		Math.floor(csamVideo.duration / 3600) + ":" +
		("0" + Math.floor((csamVideo.duration % 3600) / 60)).slice(-2) + 
		":" + ("0" + Math.floor(csamVideo.duration % 60)).slice(-2);
		
		
	let remainingTime = csamVideo.duration - csamVideo.currentTime;

	csamVidTimestamp.children[1].textContent = "-" +
		Math.floor(remainingTime / 3600) + ":" +
		("0" + Math.floor((remainingTime % 3600) / 60)).slice(-2) + 
		":" + ("0" + Math.floor(remainingTime % 60)).slice(-2);

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

csamVideo.addEventListener('waiting', function() {
	csamVidWithControls.classList.add("csam-buffering");
});

csamSeekbar.addEventListener('mousemove', function(e) {
	let rekt = e.target.getBoundingClientRect();
	let x = e.clientX - rekt.left;
	let y = e.clientY - rekt.top;

	let time = csamVideo.duration * x / rekt.width;

	csamTargetTime.textContent = Math.floor(time / 3600) + ":" +
		("0" + Math.floor((time % 3600) / 60)).slice(-2) + 
		":" + ("0" + Math.floor(time % 60)).slice(-2);

	let offset = csamTargetTime.getBoundingClientRect().width / 2;
	csamTargetTime.style.left = (x - offset) + "px";
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
	if(csamCanStream) {
		csamVideo.currentTime = val / 1000;
	} else {
		csamVideo.currentTime = Math.min(val / 1000, (csamPreviewMillis + 1) / 1000);
	}
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

function csamPrevChapter() {
	let stamps = Array.from(csamConvertedTimestamps);
	stamps.reverse();
	for(let stamp of stamps) {
		if(stamp + 1000 <= csamVideo.currentTime * 1000) {
			csamVideo.currentTime = stamp / 1000;
			break;
		}
	}
}

function csamNextChapter() {
	for(let stamp of csamConvertedTimestamps) {
		if(stamp > csamVideo.currentTime * 1000) {
			csamVideo.currentTime = csamCanStream ?
					stamp / 1000 :
					Math.min(stamp / 1000, (csamPreviewMillis + 1) / 1000);
			break;
		}
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
	csamTranslationChangeInProgress = true;
	csamWasPlayingBeforeTranslationChange = !csamVideo.paused && !csamVideo.ended;
	csamVideo.pause();
	csamVidWithControls.classList.add("csam-buffering");
	csamTimestampBeforeTranslationChange = csamVideo.currentTime;

	var vidReq = new XMLHttpRequest();
	vidReq.addEventListener("load", csamOnNewVidUrlFetched);
	vidReq.open("GET", csamPageUrl + queryString);
	vidReq.send();
}

function csamOnNewVidUrlFetched() {
	var start = this.responseText.indexOf("source src=\"") + 12;
	var end = this.responseText.indexOf("\"", start);
	var newUrl = this.responseText.substring(start, end);
  
  
	var source = document.createElement('source');
	source.setAttribute("src", newUrl);
	
	while (csamVideo.firstChild) {
		csamVideo.removeChild(csamVideo.firstChild);
	}
	
	csamVideo.appendChild(source);
	
	csamVideo.load();
}

function csamOnVideoReadyToPlay() {
	//if(!csamTranslationChangeInProgress) {
	csamVidWithControls.classList.remove("csam-buffering");
	
	if(csamTimestampBeforeTranslationChange > 0) {
		csamVideo.currentTime = csamTimestampBeforeTranslationChange;
	}
	if(csamWasPlayingBeforeTranslationChange) {
		csamVideo.play();
	}
	
	csamTimestampBeforeTranslationChange = 0;
	csamWasPlayingBeforeTranslationChange = false;
	csamTranslationChangeInProgress = false;
}

</script>

<?php endwhile; /*while post*/ endif; /*if have post*/ ?>

</article>

<?php if (is_front_page()) :?>

<article class="page">
            <h2 style="margin-top: 0; margin-bottom: 0.45rem; font-size: 1.7rem;"><a style="font: inherit; color: inherit" href="https://animagia.pl/danmachi-arrow-of-the-orion/">DanMachi: Arrow of the Orion</a></h2>
            <a class="banner-link" href="https://animagia.pl/danmachi-arrow-of-the-orion/"><figure class="series-banner">
                <img src="https://static.animagia.pl/DanMachi_poster.jpg" alt="Kadr z DanMachi">
                <figcaption class="image-text">
                    Boginie i bohaterowie na epickim queście. <span class="banner-cta">Zacznij&nbsp;oglądać&nbsp;»
                </figcaption>
            </figure></a>
</article>

<article class="page">
            <h2 style="margin-top: 0; margin-bottom: 0.45rem; font-size: 1.7rem;"><a style="font: inherit; color: inherit" href="https://animagia.pl/servamp-alice-in-the-garden/">Servamp: Alice in the Garden</a></h2>
            <a class="banner-link" href="https://animagia.pl/servamp-alice-in-the-garden/"><figure class="series-banner">
                <img src="https://static.animagia.pl/Servamp_poster.jpg" alt="Kadr z Servampa">
                <figcaption class="image-text">
                    Ludzie i wampiry mierzą się z nienaturalnym kryzysem. <span class="banner-cta">Zacznij&nbsp;oglądać&nbsp;»
                </figcaption>
            </figure></a>
</article>

<article class="page">
            <h2 style="margin-top: 0; margin-bottom: 0.45rem; font-size: 1.7rem;"><a style="font: inherit; color: inherit" href="https://animagia.pl/chuunibyou-demo-koi-ga-shitai-take-on-me/">Chuunibyou demo Koi ga Shitai! Take On Me</a></h2>
            <a class="banner-link" href="https://animagia.pl/chuunibyou-demo-koi-ga-shitai-take-on-me/"><figure class="series-banner">
                <img src="https://static.animagia.pl/posters/chuunibyou_thumbnail.jpg" alt="Kadr z Chuunibyou">
                <figcaption class="image-text">
                    Dorastanie jest zbyt mainstreamowe. <span class="banner-cta">Zacznij&nbsp;oglądać&nbsp;»
                </figcaption>
            </figure></a>
</article>

<article class="page">
            <h2 style="margin-top: 0; margin-bottom: 0.45rem; font-size: 1.7rem;"><a style="font: inherit; color: inherit" href="https://animagia.pl/tamako-love-story/">Tamako Love Story</a></h2>
            <a class="banner-link" href="https://animagia.pl/tamako-love-story/"><figure class="series-banner">
                <img src="https://static.animagia.pl/Tama_poster.jpg" alt="Kadr z Tamako Love Story">
                <figcaption class="image-text">
                    Czy to miłość? Tak. <span class="banner-cta">Zacznij&nbsp;oglądać&nbsp;»
                </figcaption>
            </figure></a>
</article>

<article class="page">
            <h2 style="margin-top: 0; margin-bottom: 0.45rem; font-size: 1.7rem;"><a style="font: inherit; color: inherit" href="https://animagia.pl/kyoukai-no-kanata-ill-be-here-przeszlosc/">Kyoukai no Kanata: I'll Be Here – przeszłość</a></h2>
            <a class="banner-link" href="https://animagia.pl/kyoukai-no-kanata-ill-be-here-przeszlosc/"><figure class="series-banner">
                <img src="https://static.animagia.pl/Past_poster.jpg" alt="Kadr z Kyoukai no Kanata – przeszłość">
                <figcaption class="image-text">
                    Początek historii Mirai i Akihito. <span class="banner-cta">Zacznij&nbsp;oglądać&nbsp;»
                </figcaption>
            </figure></a>
</article>

<article class="page">
            <h2 style="margin-top: 0; margin-bottom: 0.45rem; font-size: 1.7rem;"><a style="font: inherit; color: inherit" href="https://animagia.pl/kyoukai-no-kanata-ill-be-here-przeszlosc/">Kyoukai no Kanata: I'll Be Here – przeszłość</a></h2>
            <a class="banner-link" href="https://animagia.pl/kyoukai-no-kanata-ill-be-here-przeszlosc/"><figure class="series-banner">
                <img src="https://static.animagia.pl/Past_poster.jpg" alt="Kadr z Kyoukai no Kanata – przeszłość">
                <figcaption class="image-text">
                    Początek historii Mirai i Akihito. <span class="banner-cta">Zacznij&nbsp;oglądać&nbsp;»
                </figcaption>
            </figure></a>
</article>

<article class="page">
            <h2 style="margin-top: 0; margin-bottom: 0.45rem; font-size: 1.7rem;"><a style="font: inherit; color: inherit" href="https://animagia.pl/film-k-on/">Film K-On!</a></h2>
            <a class="banner-link" href="https://animagia.pl/film-k-on/"><figure class="series-banner">
                <img src="https://static.animagia.pl/posters/kon_thumbnail.jpg" alt="Kadr z filmu K-On!">
                <figcaption class="image-text">
                    Po szkole herbatka, muzyka i podróże. <span class="banner-cta">Zacznij&nbsp;oglądać&nbsp;»
                </figcaption>
            </figure></a>
</article>

<aside class="aside">
        <ul class="icon-container">
            <li class="mmenuitem">
                <img class="image" src="https://static.animagia.pl/blue_save.svg" alt="" style="width: 48px;"><br />
                <strong>Anime do kolekcji</strong><br />
                Ściągnij, zachowaj na zawsze,<br />oglądaj jak chcesz
            </li>
            <li class="mmenuitem">
                <img class="image" src="https://static.animagia.pl/blue_block.svg" alt="" style="width: 48px;"><br />
                <strong>Żadnych natrętnych reklam</strong> –<br />
                tylko autopromocja
            </li>
            <li class="mmenuitem">
                <img class="image" src="https://static.animagia.pl/blue_bubble.svg" alt="" style="width: 48px;"><br />
                <strong>Tłumaczenie, jakie chcesz</strong><br />
                Do wyboru napisy wierniejsze oryginałowi lub bardziej spolszczone, dla niektórych tytułów też polska ścieżka audio.
            </li>
       </ul>
</aside>


<?php endif; ?>

</main>

<?php get_footer(); ?>



