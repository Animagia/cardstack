<?php
/*
  Template Name: Scratchpad
 */
?>


<?php get_header(); ?>

<?php

function cardstack_am_getStatus($sub) {
    return $sub->data["status"];
}

function cardstack_am_isActive($sub) {
    return (cardstack_am_getStatus($sub) == "active");
}

function cardstack_am_isExpiring($sub) {
    if (cardstack_am_getStatus($sub) != "pending-cancel") {
        return false;
    }

    $expiration = $sub->data["schedule_end"]->getTimestamp();

    if (time() < $expiration) {
        return true;
    }

    return false;
}

function cardstack_am_getSubStatus() {
    $expiring = false;

    $subscriptions = hforce_get_users_subscriptions();

    foreach ($subscriptions as $sub) {
        if (cardstack_am_isActive($sub)) {
            return "active";
        }
        if (cardstack_am_isExpiring($sub)) {
            $expiring = true;
        }
    }

    if ($expiring) {
        return "expiring";
    }

    return "invalid";
}
?>

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

                <p>Custom paragraph.</p>

                <?php
                echo ("<p>Stuff: " . $subscriptions[113]->data["status"] . " " . $subscriptions[113]->data["schedule_end"] . "</p>");

                echo ("<p>Status: " . cardstack_am_getSubStatus());
                ?>

                <pre><?php var_dump($subscriptions[113]->data); ?></pre>

                <pre><?php var_dump(hforce_get_users_subscriptions()); ?></pre>

                <?php the_content(); ?> 

                <?php comments_template(); ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </article></main>

<?php get_footer(); ?>
	