<?php
/*
  Template Name: Premium video
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
                $cardstack_am_pure_stream_str = "Amagi_" . $cardstack_am_link_iter . "_" . time() .
                        "_" . $_SERVER['REMOTE_ADDR'];
                $cardstack_am_stream_token = CardStackAm::obfuscateString($cardstack_am_pure_stream_str);
                $cardstack_am_episode = explode(" ", get_the_title())[4];
                $cardstack_am_video = CardStackAmConstants::getVidUrl() .
                        "stream/serve_stream.php/Amagi" . $cardstack_am_episode . ".webm?token=" .
                        $cardstack_am_stream_token;
                $cardstack_am_poster = "https://static.animagia.pl/Amagi" . $cardstack_am_episode .
                        ".jpg";

                if ($cardstack_am_episode == "1") {
                    echo "<p>Promocja premierowa! Pierwszy odcinek w full HD do bezpłatnego oglądania, bez reklam.</p>";
                }



                if (IP_Geo_Block::get_geolocation()['code'] !== 'PL') {
                    $cardstack_am_video = "";
                    /*?>
                    <p>Wideo działa tylko w Polsce. Skontaktuj się z nami, jeśli kraj rozpoznano
                        niepoprawnie, lub jeśli tymczasowo przebywasz w innym kraju UE.</p>
                    <?php*/
                }

                if ($cardstack_am_episode == "1" ||
                        CardStackAm::userCanStreamProduct(CardStackAmConstants::getAmagiId())) :
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
                    echo "<p style=\"margin-top: 18px; text-align: center;\">";


                    if ($cardstack_am_episode > 1) :
                        echo '<a href="' . get_home_url() . '/amagi-brilliant-park-odc-' .
                        strval(intval($cardstack_am_episode) + -1) . '/">« poprzedni odcinek</a>';
                    endif;

                    if ($cardstack_am_episode > 1 && $cardstack_am_episode < 12) {
                        echo ' | ';
                    }

                    if ($cardstack_am_episode < 12) :
                        echo '<a href="' . get_home_url() . '/amagi-brilliant-park-odc-' .
                        strval(intval($cardstack_am_episode) + 1) . '/">następny odcinek »</a>';
                    endif;
                    echo "</p>";

                else :
                    ?>
                    <p>Ten odcinek nie jest jeszcze dostępny do bezpłatnego oglądania.
                        Nie czekaj! Załóż <a href="<?php echo get_home_url() ?>/sklep/">konto premium</a>
                        lub kup <a href="<?php echo get_home_url() ?>/sklep/">cyfrową kopię</a>.</p>
                <?php endif; ?> 

                <?php comments_template(); ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </article></main>

<?php get_footer(); ?>
	