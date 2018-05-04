<?php
/*
  Template Name: Scratchpad
 */
?>


<?php get_header(); ?>

<?php

function isActive($sub) {
    $subdata = $sub->data;
    
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

				<?php $subscriptions = hforce_get_users_subscriptions();
				
                                foreach($subscriptions as $subscription) {
                                    
                                }
                                
				$subdata = $subscriptions[113]->data;
				
				$substatus = $subdata->status;
				
				$scheduleEndString = $subdata->schedule_end;
				
				echo ("<p>Stuff: " . $subdata["status"] . " " . $subdata["schedule_end"] . "</p>");
				
				 ?>
				
                <pre><?php var_dump($subscriptions[113]->data); ?></pre>

                <pre><?php var_dump(hforce_get_users_subscriptions()); ?></pre>

                <?php the_content(); ?> 

                <?php comments_template(); ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </article></main>

<?php get_footer(); ?>
	