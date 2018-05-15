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
                $cardstack_am_video = CardStackAmConstants::getVidUrl() .
                        "stream/serve_stream.php/Amagi" . $cardstack_am_episode . ".webm";
                $cardstack_am_poster = "https://static.animagia.pl/Amagi" . $cardstack_am_episode .
                        ".jpg";

                if ($cardstack_am_episode == "1"
                        || CardStackAm::userCanStreamProduct(39)) :
                    ?>

                    <video class="video-js vjs-16-9 vjs-big-play-centered" style="width: 100%;"
                           controls="true" oncontextmenu="return false;"
                           poster="<?php echo $cardstack_am_poster ?>" preload="metadata"
                           data-setup='{"playbackRates": [1, 1.1, 1.2, 2] }'>
                        <source src="<?php echo $cardstack_am_video ?>" type="video/webm" />
                    </video>

                    <script src="https://static.animagia.pl/video.js"></script>

                    <?php
                    if ($cardstack_am_episode < 12) :
                        echo "następny odcinek";
                    endif;

                    if ($cardstack_am_episode > 1) :
                        echo "poprzedni odcinek";
                    endif;

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
	