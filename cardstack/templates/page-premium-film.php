<?php
/*
  Template Name: Premium film
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
                if (is_front_page()) {
                    echo '<p class="demoted-title">';
                    the_title();
                    echo '</p>';
                } else {
                    echo '<h1>';
                    the_title();
                    echo '</h1>';
                }
                
                if (IP_Geo_Block::get_geolocation()['code'] !== 'PL') {
                    echo '<p>Video playback works in Poland only. Please contact us '
                    . 'if your location was detected incorrectly or if you qualify for "roaming" '
                    . 'under EU law.</p>';
                }
				
		
                if(strpos(get_the_content(), 'future') !== false ) {
                    echo '<p>Satysfakcjonujące zakończenie historii Mirai, Akihito i rodziny Nase.</p>';
                    if (CardStackAm::userCanStreamProduct(615)) {
                        CsAmVideo::printPremiumFilmPlayer("KnKFuture");
                    } else {
                        CsAmVideo::printFreeFilmPlayer("Future");
                    }
                } else if(strpos(get_the_content(), 'knk') !== false ) {
                    echo '<p>Początek historii Mirai i Akihito.</p>';
                    if (CardStackAm::userCanStreamProduct(572)) {
                        CsAmVideo::printPremiumFilmPlayer("KnKPast");
                    } else {
                        CsAmVideo::printFreeFilmPlayer("Past");
                    }
                } else if(strpos(get_the_content(), 'tamako') !== false ) {
                    echo '<p>Czy to miłość? Tak.</p>';
                    if (CardStackAm::userCanStreamProduct(646)) {
                        CsAmVideo::printPremiumFilmPlayer("Tamako");
                    } else {
                        CsAmVideo::printFreeFilmPlayer("Tama");
                    }
                } else {
                    echo '<p>Najnowszy film o tych, dla których dorastanie jest zbyt mainstreamowe.</p>';
                    if (CardStackAm::userCanStreamProduct(318)) {
                        CsAmVideo::printPremiumFilmPlayer("Chuuni");
                    } else {
                        CsAmVideo::printFreeFilmPlayer("Chuu");
                    }
                }


                ?>


                <?php comments_template(); ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </article></main>

<?php get_footer(); ?>
