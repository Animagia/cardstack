<?php
/*
  Template Name: Premium film
 */
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

                <?php
                /* breadcrumbs */
                if (get_theme_mod('page_breadcrumbs')) {
                    print('<span class="breadcrumbs"><a href="' . esc_url(home_url()) . '">');

                    $cardstack_customHomeLinkName = get_theme_mod('custom_home_link_title');
                    if (!empty($cardstack_customHomeLinkName)) {
                        print($cardstack_customHomeLinkName);
                    } else {
                        print(__('Home'));
                    }
                    print('</a> &raquo;');

                    $ancestors = array_reverse(get_post_ancestors($id));
                    foreach ($ancestors as $ancestor) {
                        print(' <a href="' . get_permalink($ancestor) . '">'
                                . get_the_title($ancestor) . '</a> &raquo;');
                    }

                    print('</span>');
                }
                if(is_front_page()) {
                    echo '<p class="demoted-title">';
                    the_title();
                    echo '</p>';
                } else {
                    echo '<h1>';
                    the_title();
                    echo '</h1>';
                }
                ?>


                <?php
                $cardstack_am_episode = "00";
				if($_GET["altsub"] === "yes" && $cardstack_am_episode == "1") {
                    $cardstack_am_episode = $cardstack_am_episode . 'a';
                }
                $cardstack_am_pure_stream_str = "Chuuni_" . $cardstack_am_episode . "_" . time() .
                        "_" . $_SERVER['REMOTE_ADDR'];
                $cardstack_am_stream_token = CardStackAm::obfuscateString($cardstack_am_pure_stream_str);
                if($_GET["altsub"] === "yes") {
                    $cardstack_am_episode = $cardstack_am_episode . 'a';
                }
                $cardstack_am_video = CardStackAmConstants::getVidUrl() .
                        "stream/film_stream.php/Chuuni" . $cardstack_am_episode . ".webm?token=" .
                        $cardstack_am_stream_token;
                $cardstack_am_poster = "https://static.animagia.pl/film_poster.jpg";

                if (IP_Geo_Block::get_geolocation()['code'] !== 'PL') {
                    $cardstack_am_video = "";
                }

                if ( CardStackAm::userCanStreamProduct(318) ) :

					if ($cardstack_am_episode == "00") {
						echo '<p>Jeśli wolisz napisy bez japońskich tytułów grzecznościowych, przejdź <a href="'
						. get_home_url() . '/?altsub=yes">tutaj</a>.</p>';
					} else if($cardstack_am_episode == "00a") {
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

                else :

                $cardstack_am_episode = "2";
                $cardstack_am_pure_stream_str = "Chuu_2_" . time() .
                        "_" . $_SERVER['REMOTE_ADDR'];
                $cardstack_am_stream_token = CardStackAm::obfuscateString($cardstack_am_pure_stream_str);
                $cardstack_am_video = CardStackAmConstants::getVidUrl() .
                        "stream/film_stream.php/Chuu" . $cardstack_am_episode . ".webm?token=" .
                        $cardstack_am_stream_token;



                if (IP_Geo_Block::get_geolocation()['code'] !== 'PL') {
                    $cardstack_am_video = "";
                } ?>
	
                       <p>Bezpłatny stream ma ograniczony czas oglądania. Żeby obejrzeć całość,
						   załóż <a href="<?php echo get_home_url() ?>/sklep/">konto premium</a>
                        lub kup <a href="<?php echo get_home_url() ?>/sklep/">cyfrową kopię</a>.</p>

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
					
					vid1.on('timeupdate', function() {
						var vid1time = vid1.currentTime();
						//console.log('seeked from', vid1time);
						
						if(vid1time > 443) {
							vid1.reset();
						}
						
					});
				</script>
	
	
                <?php endif; ?>

					<?php
                    echo "<p style=\"margin-top: 18px; text-align: center;\">";
                        echo 'Jeśli chcesz obejrzeć Amagi Brilliant Park, przejdź <a href="' . get_home_url() . '/amagi-brilliant-park-odc-1">tutaj</a>.';
                    echo "</p>";  ?>
	
	
                <?php comments_template(); ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </article></main>

<?php get_footer(); ?>
