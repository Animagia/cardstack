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
                ?>
                <h1><?php the_title(); ?></h1>


                <?php
                $cardstack_am_episode = explode(" ", get_the_title())[4];
                $cardstack_am_manifest = CardStackAmConstants::getVidUrl() . "dash/manifest" .
                        $cardstack_am_episode . ".mpd";
                $cardstack_am_poster = "https://static.animagia.pl/Amagi" . $cardstack_am_episode .
                        ".jpg";

                if ($cardstack_am_episode == "1"
                        || CardStackAm::userCanStreamProduct(118)) :
                    ?>

                    <script src="https://static.animagia.pl/dash.all.min.js"></script>

                    <!--<div class="dash-video-player">-->
                        <video id="am-dash-video" controls="true" style="width: 100%"
                               poster="<?php echo $cardstack_am_poster;?>"></video>
                    <!--</div>-->

                    <script>
                        function startVideo() {
                            var url = "<?php echo $cardstack_am_manifest; ?>",
                                    video = document.querySelector("#am-dash-video"),
                                    player;
                            player = dashjs.MediaPlayer({}).create();
                            player.initialize(video, url, false);
                        }

                        startVideo();
                    </script>
                    <?php
                else :
                    echo "<p>Ten odcinek nie jest jeszcze dostępny do bezpłatnego oglądania. " .
                    "Nie czekaj! Załóż konto premium lub kup cyfrową kopię.</p>";
                endif;
                ?> 

                <?php comments_template(); ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </article></main>

<?php get_footer(); ?>
	