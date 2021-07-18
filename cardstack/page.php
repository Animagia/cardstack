	
	<?php get_header(); ?>
	
	<article class="page<?php if(get_theme_mod('page_breadcrumbs')) { print(' has-breadcrumbs'); } ?>">
	
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
		
	</article></main>
	
	<?php get_footer(); ?>
	
