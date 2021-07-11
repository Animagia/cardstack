<?php
/*
  Template Name: Catalog
 */
?>

<?php 

header("Content-Type: text/plain");

$csam_pages = get_pages(array(
    'meta_key' => '_wp_page_template',
    'meta_value' => 'templates/page-new-film.php'
));

echo("[");

$csam_count = 0;
foreach($csam_pages as $csam_page) {
	$csam_count++;
    echo get_post_field('post_content', $csam_page->ID);
	if($csam_count < count($csam_pages)) {
		echo ",\n";
	}
}

echo("]");

