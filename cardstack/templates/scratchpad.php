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

                <?php
                if (!is_user_logged_in()) :

                    wp_login_form();

                else :
                    ?>

                    <p>Custom paragraph.</p>


                    <?php
                    $substatus = $cardstack_am->getSubStatus();

                    echo ("<p>Status: " . $substatus . "</p>");

                    $date = new DateTime("now", new DateTimeZone("Europe/Warsaw"));
                    $date->setTimestamp($cardstack_am->getExpirationDate());

                    if ($substatus == "expiring") {
                        echo ("<p>Valid until: " . $date->format("Y-m-d H:i:s") . "</p>");
                    }


                    //amagi id: 118

                    $current_user = wp_get_current_user();

                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 118)) {
                        echo ("This user bought Amagi!");
                    }
                    ?>

                    <?php the_content(); ?> 

                <?php endif; ?>

                <?php comments_template(); ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </article></main>

<?php get_footer(); ?>
	