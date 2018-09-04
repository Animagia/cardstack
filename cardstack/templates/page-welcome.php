<?php
/*
  Template Name: Welcome
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

                <?php comments_template(); ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </article>

    <article class="page<?php
    if (get_theme_mod('page_breadcrumbs')) {
        print(' has-breadcrumbs');
    }
    ?>">
        <figure class="container">
            <img src="https://static.animagia.pl/Amagi1.jpg" alt="Amagi"/>
            <div class="image-text">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris semper dapibus eros.
                    Quisque et rutrum purus, semper rhoncus purus. Donec libero odio, eleifend a purus
                    lobortis, accumsan cursus urna. Pellentesque et pharetra metus. Mauris lobortis
                    metus eu tortor blandit, in viverra est ultricies.
                </p>
            </div>
        </figure>
    </article>
    <aside class="aside">
        <ul class="icon-container">
            <li class="mmenuitem">
                <i class="material-icons">
                    movie_creation
                </i>
                <p>Lorem ipsum dolor sit amet</p>
            </li>
            <li class="mmenuitem">
                <i class="material-icons">
                    lock
                </i>
                <p>Lorem ipsum dolor sit amet</p>
            </li>
            <li class="mmenuitem">
                <i class="material-icons">
                    ondemand_video
                </i>
                <p>Lorem ipsum dolor sit amet</p>
            </li>
        </ul>
    </aside>
</main>

<?php get_footer(); ?>
