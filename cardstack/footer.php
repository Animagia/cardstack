	
	<footer>
		<?php
			$cardstack_customFooterText = get_theme_mod('custom_footer_text');
			
			$cardstack_jsonObject = json_decode($cardstack_customFooterText);
			if($cardstack_jsonObject != null) {
				$cardstack_jsonLanguage = substr(get_bloginfo('language'), 0, 2);
				if(isset($cardstack_jsonObject[$cardstack_jsonLanguage])) {
					$cardstack_customFooterText = $cardstack_jsonObject[$cardstack_jsonLanguage];
				} else if(isset($cardstack_jsonObject['default'])) {
					$cardstack_customFooterText = $cardstack_jsonObject['default'];
				} else {
					$cardstack_customFooterText = '';
				}
			}
			
			if(!empty($cardstack_customFooterText)) {
				print($cardstack_customFooterText);
			} else {
				print('WordPress ' . $wp_version . ' with Card Stack theme');
			}
		?>
	</footer>

<?php wp_footer(); ?>

</body>

</html>
