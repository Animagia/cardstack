<?php
/**
 * Template Name: Scratchpad
 */
?>


<?php get_header(); ?>

<main <?php if (is_active_sidebar(1)): print('class="with-sidebar"'); endif ?> >

    <article class="page<?php if (get_theme_mod('page_breadcrumbs')): print(' has-breadcrumbs'); endif ?>" >

        <?php if (have_posts()): ?>
            <?php while (have_posts()): the_post(); ?>

                <h1><?php the_title(); ?></h1>

                <?php
                if (!is_user_logged_in()):

                    wp_login_form();

                else :
                    ?>

                    <p>Custom paragraph.</p>


                    <?php
                        echo("<h2>" . "Pliki do pobrania" . "</h2><p>");

                        echo("<a href=\"" . CardStackAmConstants::getVidUrl() . "ddl/serve_file.php?token=" . $obfuscated . "\">");
                        echo("[Animagia.pl] Amagi Brilliant Park 01 1080p.mkv");
                        echo("</a>");

                        echo("</p>");
 
                    ?>

                    <?php the_content(); ?> 

                <?php endif; ?>

                <?php comments_template(); ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </article>
</main>

<?php get_footer(); ?>
	