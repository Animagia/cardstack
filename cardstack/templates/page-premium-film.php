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
                ?>


                <?php

                if (CardStackAm::userCanStreamProduct(318)) {
                    CsAmVideo::printPremiumFilmPlayer();
                } else {
                    CsAmVideo::printFreeFilmPlayer();
                }


                ?>

                <?php
                echo "<p style=\"margin-top: 18px; text-align: center;\">";
                echo 'Jeśli chcesz obejrzeć Amagi Brilliant Park, przejdź <a href="' . get_home_url() . '/amagi-brilliant-park-odc-1">tutaj</a>.';
                echo "</p>";
                ?>


                <?php comments_template(); ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </article></main>

<?php get_footer(); ?>
