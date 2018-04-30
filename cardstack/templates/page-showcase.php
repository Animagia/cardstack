<?php
/*
Template Name: Showcase
*/
?>
	
	<?php get_header(); ?>
	
	<main<?php if(is_active_sidebar(1)) { print(' class="with-sidebar"'); } ?>><article class="page<?php if(get_theme_mod('page_breadcrumbs')) { print(' has-breadcrumbs'); } ?>">
	
		<?php if(have_posts()) : ?>
		<?php while(have_posts()) : the_post(); ?>
	
			<?php
				/* breadcrumbs */
				if(get_theme_mod('page_breadcrumbs')) {
					print('<span class="breadcrumbs"><a href="' . esc_url(home_url()) . '">');
					
					$cardstack_customHomeLinkName = get_theme_mod('custom_home_link_title');
					if(!empty($cardstack_customHomeLinkName)) {
						print($cardstack_customHomeLinkName);
					} else  {
						print(__('Home'));
					}
					print('</a> &raquo;');
					
					$ancestors = array_reverse(get_post_ancestors($id));
					foreach($ancestors as $ancestor) {
						print(' <a href="' . get_permalink($ancestor) . '">' . get_the_title($ancestor) . '</a> &raquo;');
					}
					
					print('</span>');
				}
			?>
			<h1><?php the_title(); ?></h1>
	
			<?php the_content(); ?> 
			
			<?php comments_template(); ?>
		
		<?php endwhile; ?>
		<?php endif; ?>
		
		</article>
	
	<?php $id_of_this = get_the_ID();
	
	
	// Set up the objects needed
	$my_wp_query = new WP_Query();
	$all_wp_pages = $my_wp_query->query(array('post_type' => 'page', 'posts_per_page' => '-1'));

	// Filter through all pages and find Portfolio's children
	$portfolio_children = get_page_children( $id_of_this, $all_wp_pages );

	// echo what we get back from WP to the browser
	echo '<pre>' . print_r( $portfolio_children, true ) . '</pre>';
	
	?>
	
	</main>
	
	<?php get_footer(); ?>
	