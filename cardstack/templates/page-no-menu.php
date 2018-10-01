<?php
/**
 * Template Name: No menu, no sidebar, no title
 */
?>

<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
	<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?php wp_title(); ?></title>
	<link href="<?php print(get_stylesheet_uri()); ?>" rel="stylesheet" type="text/css" />

	<?php
		wp_head();
	?>
	
</head>

<body <?php body_class(); ?>>

	<header id="header-main" 
		<?php
			$hashi_headerImageUrl = get_header_image();
			if (!empty($hashi_headerImageUrl)) {
				print('style="background-image: url('. $hashi_headerImageUrl .');"');
			}
		?>
		>
		<?php
			$hashi_headerTextColor = get_header_textcolor();
			if ($hashi_headerTextColor != 'blank'):
			if (is_singular()):
		?>
			<p id="blog-title" style="color: #<?php print($hashi_headerTextColor); ?>;"><?php bloginfo('name'); ?></p>
			<p id="blog-tagline" style="color: #<?php print($hashi_headerTextColor); ?>;"><?php bloginfo( 'description' ); ?></p>
		<?php else: ?>
			<h1 id="blog-title" style="color: #<?php print($hashi_headerTextColor); ?>;"><?php bloginfo('name'); ?></h1>
			<p id="blog-tagline" style="color: #<?php print($hashi_headerTextColor); ?>;"><?php bloginfo( 'description' ); ?></p>
		<?php endif; endif; ?>
		<a id="home-link" href="<?php print(esc_url(home_url())); ?>"></a>
	</header>
	
	<main class="only-column">
        <article>
            <?php if (have_posts()): ?>
            <?php while (have_posts()): the_post(); ?>

                <?php the_content(); ?>

                <?php comments_template(); ?>

            <?php endwhile; ?>
            <?php endif; ?>
	    </article>
    </main>

    <?php get_footer(); ?>
