<?php
/*
  Template Name: Coupon activated
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
                if (empty($_POST["code"])) {
					echo "<p>Coś poszło nie tak. Kupon pozostał niewykorzystany. <a href=\"https://animagia.pl/kupon\">Powrót</a></p>";
                } else if (IP_Geo_Block::get_geolocation()['code'] !== 'PL') {
                    ?>
                    <p>Aktywacja kuponów działa tylko w Polsce. Kupon pozostał niewykorzystany.
                        Skontaktuj się z nami, jeśli kraj rozpoznano niepoprawnie,
                        lub jeśli tymczasowo przebywasz w innym kraju UE.</p>
                    <?php
                } else {

                    $cardstack_am_c_flag = true;

                    $cardstack_am_wc_c = new WC_Coupon($_POST["code"]);
					
					/*echo("<pre>");
					var_dump($cardstack_am_wc_c->get_product_ids(  ));
					echo("</pre>");*/

                    $cardstack_am_show_links = false;

                    if ($cardstack_am_wc_c->id !== 0 && $cardstack_am_wc_c->usage_count == 0) {
                        $cardstack_am_wc_c->set_description((new DateTime("now",
                                new DateTimeZone("Europe/Warsaw")))->format("Y-m-d H:i:s"));
                        $cardstack_am_wc_c->set_usage_count(1);
                        $cardstack_am_wc_c->save();

                        $cardstack_am_show_links = true;
                    }

                    if ($cardstack_am_wc_c->id !== 0 && $cardstack_am_wc_c->usage_count !== 0) {
                        $cardstack_am_c_timestamp = new DateTime($cardstack_am_wc_c->get_description(),
                                new DateTimeZone("Europe/Warsaw"));

                        if (time() <= $cardstack_am_c_timestamp->getTimestamp() + 3600 * 24 * 15) {
                            $cardstack_am_show_links = true;
                        }
                    }

                    if ($cardstack_am_show_links) {
						
						if($cardstack_am_wc_c->get_product_ids()[0] === 39) { // XXX

							$cardstack_am->printAmagiLinks();

							echo '<p>Kupon został aktywowany: ' . $cardstack_am_wc_c->get_description() .
							', i przez 15 dni od tamtej chwili możesz go użyć do generowania linków. ' .
							'Wygenerowane linki są krótkotrwałe – w razie potrzeby ' .
							'wróć na <a href="https://animagia.pl/kupon">poprzednią stronę</a> ' .
							' i wpisz kod z kuponu ponownie, by wygenerować nowe.</p>' .
							'<p>Raz ściągnięte pliki możesz zachować na zawsze.</p>';
							
						} else if($cardstack_am_wc_c->get_product_ids()[0] === 318) { // XXX
						
                        	echo "<p><strong>Sukces!</strong></p>";
						
                        	?>
	
							<p><strong>Kupon został aktywowany.</strong> Żeby wrócić na tę stronę, wpisz kod z kuponu ponownie
								na animagia.pl/kupon.</p>
							<p>Do 3 sierpnia kod z kuponu pozwala Ci obejrzeć poniższy stream. 3 sierpnia stream zostanie zastąpiony
								linkiem do ściągnięcia filmu w pliku .mkv. Kod z kuponu przestanie działać 15 dni po pierwszym użyciu,
								jednak nie wcześniej niż 18 sierpnia.
								<strong>Koniecznie wróć na tę stronę między 3 a 18 sierpnia, żeby ściągnąć plik .mkv</strong>,
								który będziesz mógł (mogła) zachować na zawsze.</p>
	
							<?php
							
							$cardstack_am_episode = "00";
							$cardstack_am_pure_stream_str = "Chuuni_" . $cardstack_am_episode . "_" . time() .
									"_" . $_SERVER['REMOTE_ADDR'];
							$cardstack_am_stream_token = CardStackAm::obfuscateString($cardstack_am_pure_stream_str);
							$cardstack_am_video = CardStackAmConstants::getVidUrl() .
									"stream/film_stream.php/Chuuni" . $cardstack_am_episode . ".webm?token=" .
									$cardstack_am_stream_token;
							$cardstack_am_poster = "https://static.animagia.pl/film_poster.jpg";

							if (IP_Geo_Block::get_geolocation()['code'] !== 'PL') {
								$cardstack_am_video = "";
							} ?>

							<video id='amagi' class="video-js vjs-16-9 vjs-big-play-centered" style="width: 100%;"
								   controls="true" oncontextmenu="return false;"
								   poster="<?php echo $cardstack_am_poster ?>" preload="metadata"
								   data-setup='{}'>
								<source src="<?php echo $cardstack_am_video ?>" type="video/webm" />
							</video>

							<script src="https://static.animagia.pl/video.js"></script>

							<script>
								var vid1 = videojs('amagi');
								vid1.on('dblclick', function () {
									vid1.requestFullscreen();
								});
								vid1.on('dblclick', function () {
									vid1.exitFullscreen();
								});
							</script>

							<?php
						} else {
							echo "<p>Coś poszło nie tak. Kupon pozostał niewykorzystany. <a href=\"https://animagia.pl/kupon\">Powrót</a></p>";
						}
                    } else {
                        echo "<p>Kod kuponu jest niepoprawny lub już wygasł. " .
                        "<a href=\"https://animagia.pl/kupon\">Powrót</a></p>";
                    }

                    /* echo "<pre>";
                      var_dump($cardstack_am_wc_c);
                      echo "</pre>"; */
                }
                ?>


            <?php endwhile; ?>
        <?php endif; ?>

    </article></main>

<?php get_footer(); ?>
	