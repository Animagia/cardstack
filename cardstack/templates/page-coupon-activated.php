<?php
/*
  Template Name: Coupon activated
 */
?>


<?php get_header(); ?>

<article class="page<?php
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
						
						if($cardstack_am_wc_c->get_product_ids()[0] === 39) { 

							$cardstack_am->printAmagiLinks();

							echo '<p>Kupon został aktywowany: ' . $cardstack_am_wc_c->get_description() .
							', i przez 15 dni od tamtej chwili możesz go użyć do generowania linków. ' .
							'Wygenerowane linki są krótkotrwałe – w razie potrzeby ' .
							'wróć na <a href="https://animagia.pl/kupon">poprzednią stronę</a> ' .
							' i wpisz kod z kuponu ponownie, by wygenerować nowe.</p>' .
							'<p>Raz ściągnięte pliki możesz zachować na zawsze.</p>';
							
						} else if($cardstack_am_wc_c->get_product_ids()[0] === 318) { 


							$cardstack_am->printChuuniLink();

							echo '<p>Kupon został aktywowany: ' . $cardstack_am_wc_c->get_description() .
							', i przez 15 dni od tamtej chwili możesz go użyć do generowania linków. ' .
							'Wygenerowany link jest krótkotrwały – w razie potrzeby ' .
							'wróć na <a href="https://animagia.pl/kupon">poprzednią stronę</a> ' .
							' i wpisz kod z kuponu ponownie, by wygenerować nowy.</p>' .
							'<p>Raz ściągnięty plik możesz zachować na zawsze.</p>';

						} else if($cardstack_am_wc_c->get_product_ids()[0] === 508) { 

							$cardstack_am->printHanaIroLink();

							echo '<p>Kupon został aktywowany: ' . $cardstack_am_wc_c->get_description() .
							', i przez 15 dni od tamtej chwili możesz go użyć do generowania linków. ' .
							'Wygenerowane linki są krótkotrwałe – w razie potrzeby ' .
							'wróć na <a href="https://animagia.pl/kupon">poprzednią stronę</a> ' .
							' i wpisz kod z kuponu ponownie, by wygenerować nowe.</p>' .
							'<p>Raz ściągnięte pliki możesz zachować na zawsze.</p>';
							
						} else if($cardstack_am_wc_c->get_product_ids()[0] === 646) { 

							$cardstack_am->printTamakoLink();

							echo '<p>Kupon został aktywowany: ' . $cardstack_am_wc_c->get_description() .
							', i przez 15 dni od tamtej chwili możesz go użyć do generowania linków. ' .
							'Wygenerowane linki są krótkotrwałe – w razie potrzeby ' .
							'wróć na <a href="https://animagia.pl/kupon">poprzednią stronę</a> ' .
							' i wpisz kod z kuponu ponownie, by wygenerować nowe.</p>' .
							'<p>Raz ściągnięte pliki możesz zachować na zawsze.</p>';
							
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
	
