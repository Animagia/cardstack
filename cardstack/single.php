	
	<?php get_header(); ?>
	
	<?php if(is_active_sidebar(1)) { print(' class="with-sidebar"'); } ?>><article <?php if(get_theme_mod('post_breadcrumbs')) { print('class="has-breadcrumbs"'); } ?>>
	
	<?php if(have_posts()) : ?>
	<?php while(have_posts()) : the_post(); ?>

		<?php
			/* breadcrumbs */
			if(get_theme_mod('post_breadcrumbs')) {
				print('<span class="breadcrumbs post-breadcrumbs"><a href="' . esc_url(home_url()) . '">');
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
				
			print( '<h1 ');
			post_class('post-title');
			print('>');
			the_title();
			print('</h1>');
				
			print('<span class="date">');
			if(!get_theme_mod('hide_author')) {
				the_author();
				print(', ');
			}
			print('<time datetime="' . get_the_date('Y-m-d') . '">');
			the_date();
			print('</time>');
			if(has_category() && !get_theme_mod('hide_categories')) {
				print(' &middot; '.__( 'Categories' ).': ');
				the_category(", ");
			}
			if(has_tag() && !get_theme_mod('hide_tags')) {
				print(' &middot; '.__( 'Tags' ).': ');
				the_tags("", ", ");
			}
			print('</span>');
			
			the_content();
			
			wp_link_pages(
				array(
					'before' => '<nav class="page-switcher"><p>' . __( 'Pages:' ),
					'after' => '</p></nav>'
				)
			);
		?>
		
		<?php comments_template(); ?>
	
	<?php endwhile; ?>
	<?php endif; ?>

	</article></main>
	
	<?php get_footer(); ?>
	
