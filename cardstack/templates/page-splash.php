<?php
/**
 *Template Name: Splash
 */
?>

<!DOCTYPE html>

<html <?php language_attributes(); ?>>

    <head>
        <meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title><?php wp_title(); ?></title>
        <link href="<?php print(get_stylesheet_uri()); ?>" rel="stylesheet" type="text/css" />

        <?php wp_head(); ?>

        <style>
            h1, p {max-width: 500px; margin-left: auto; margin-right: auto; text-align: center;}
            h1 {margin-top: 15px; margin-bottom: 20px;}
            p {font-size: 13pt; margin-bottom: 20px; padding-left: 12px; padding-right: 12px;}
        </style>

    </head>

    <body <?php body_class(); ?>>
        <?php if (have_posts()): ?>
            <?php while (have_posts()): the_post(); ?>

                <?php the_content(); ?>

            <?php endwhile; ?>
        <?php endif; ?>
    </body>
</html>
