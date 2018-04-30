<?php
/*
  Template Name: Scratchpad
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

                <h1><?php the_title(); ?></h1>

                <p>Custom stuff goes in this paragraph. Did it sync? RSS_1337</p>

                <?php echo('userid' . get_current_user_id()); ?>

                <p><?php var_dump(hforce_get_users_subscriptions()); ?></p>

                <p><?php var_dump(hforce_get_users_subscriptions(get_current_user_id())); ?></p>

                <p><?php echo(hforce_dummy_nonexistent_functions()); ?></p>

                <?php the_content(); ?> 

                <?php comments_template(); ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </article></main>

<?php get_footer(); ?>
	