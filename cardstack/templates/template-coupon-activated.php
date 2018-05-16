<?php
/*
  Template Name: Coupon activated
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

                <?php if (empty($_POST["code"])) : ?>

                    <p>Coś poszło nie tak. <a href="https://animagia.pl/kupon">Powrót</a>

                    <?php else : ?>

                    <p><?php echo $_POST["code"]; ?></p>
                    
                    <p><?php echo ((new DateTime("now", new DateTimeZone('Europe/Warsaw')))->format("Y-m-d H:i:s")); ?>
                    
                    <pre><?php var_dump(new WC_Coupon($_POST["code"])); ?></pre>

                <?php endif; ?>


            <?php endwhile; ?>
        <?php endif; ?>

    </article></main>

<?php get_footer(); ?>
	