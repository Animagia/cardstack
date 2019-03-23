<?php
/*
  Template Name: Account management
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
                if (!is_user_logged_in()) :

                    wp_login_form();

                    echo('<p><a href="https://animagia.pl/wp-login.php?action=lostpassword">Przypomnij hasło</a></p>');
                    echo '<p>Konto możesz założyć przy pierwszym zakupie w naszym sklepie. '
                    . 'By oglądać odcinki dostępne bezpłatnie, nie musisz zakładać konta.</p>';

                else :
                    ?>

                    <p>Zalogowano jako:
                        <?php echo $current_user->user_email; ?>.

                        <a href="<?php echo wp_logout_url(get_permalink()); ?>">Wyloguj</a> |
                        <a href="https://animagia.pl/chpasswd">Zmień hasło</a>
                    </p>

                    <h2>Konto premium</h2>


                    <?php
                    $substatus = $cardstack_am->getSubStatus();

                    if ($substatus === "expiring") { //expiring
                        $date = new DateTime("now", new DateTimeZone("Europe/Warsaw"));
                        $date->setTimestamp($cardstack_am->getExpirationDate());
                        echo ("<p><strong>Wygasające</strong>, ważne do: " . $date->format("Y-m-d H:i:s") .
                        ". <a href=\"https://animagia.pl/\">Zacznij oglądać anime!</a></p>");
                    } else if ($substatus === "active") { //active
                        echo ("<p><strong>Aktywne.</strong> " .
                        "<a href=\"https://animagia.pl/amagi-brilliant-park-odc-1/\">Zacznij oglądać anime!</a></p>" .
                        "<p><small>Jeśli chcesz zrezygnować, <a href=\"https://www.paypal.com/myaccount/autopay\">" .
                        "anuluj cykliczną płatność</a> w PayPal.</small></p>");
                    } else {
                        echo ("<p>Nieaktywne.</p>");
                    }

                    $current_user = wp_get_current_user();

                    echo ("<h2>Zakupione anime</h2>");

					$cardstack_am_can_watch_anything = false;
	
                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID,
                                    CardStackAmConstants::getAmagiId())) {
                        echo ("<p>Amagi Brilliant Park – <a href=\"https://animagia.pl/\">zacznij oglądać</a></p>");
						$cardstack_am_can_watch_anything = true;
                    }
	                if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 318)) {
                        echo ("<p>Chuunibyou demo Koi ga Shitai! Take On Me
						– <a href=\"https://animagia.pl/chuunibyou-demo-koi-ga-shitai-take-on-me/\">zacznij oglądać</a></p>");
						$cardstack_am_can_watch_anything = true;
                    }
	                if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 508)) {
                        echo ("<p>Hanasaku Iroha: Home Sweet Home
						– <a href=\"https://animagia.pl/\">zacznij oglądać</a></p>");
						$cardstack_am_can_watch_anything = true;
                    }
	                if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 572)) {
                        echo ("<p>Kyoukai no Kanata: I’ll Be Here – przeszłość
						– <a href=\"https://animagia.pl/kyoukai-no-kanata-ill-be-here-przeszlosc/\">zacznij oglądać</a></p>");
						$cardstack_am_can_watch_anything = true;
                    }
	                if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 615)) {
                        echo ("<p>Kyoukai no Kanata: I’ll Be Here – przyszłość
						– <a href=\"https://animagia.pl/kyoukai-no-kanata-ill-be-here-przyszlosc/\">zacznij oglądać</a></p>");
						$cardstack_am_can_watch_anything = true;
                    }
					if(!$cardstack_am_can_watch_anything) {
                        echo ("<p>Brak zakupionych anime. " .
                        "<a href=\"https://animagia.pl/sklep\">Przejdź do sklepu</a></p>");
                    }

                    echo ("<h2>Pliki do pobrania</h2>");
					
                    $cs_am_can_download_anything = false;

                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID,
                                    CardStackAmConstants::getAmagiId())) {
                        echo ("<h3>Amagi Brilliant Park</h3>");
                        $cardstack_am->printAmagiLinks();
			$cs_am_can_download_anything = true;
                    }
                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 318)) {
                        echo ("<h3>Chuunibyou demo Koi ga Shitai! Take On Me</h3>");
                        $cardstack_am->printChuuniLink();
						$cs_am_can_download_anything = true;
                    }
                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 508)) {
                        echo ("<h3>Hanasaku Iroha: Home Sweet Home</h3>");
                        $cardstack_am->printHanaIroLink();
						$cs_am_can_download_anything = true;
                    }
                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 572)) {
                        echo ("<h3>Kyoukai no Kanata – przeszłość</h3>");
                        $cardstack_am->printPastLink();
						$cs_am_can_download_anything = true;
                    }
                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 615)) {
                        echo ("<h3>Kyoukai no Kanata – przyszłość</h3>");
                        $cardstack_am->printFutureLink();
						$cs_am_can_download_anything = true;
                    }
					
                    if($substatus === "expiring" || $substatus === "active") {
                        echo ("<h3>Aruku to Iu Koto</h3>");
                        $cardstack_am->printArukuLink();
                    }
                    
                    if($substatus === "expiring" || $substatus === "active") {
                        echo ("<h3>Łososik (Shake-chan)</h3>");
                        $cardstack_am->printShakeLink();
                    }

                    if ($substatus === "invalid" && $cs_am_can_download_anything === false) {
                        echo ("<p>Brak plików. " .
                        "<a href=\"https://animagia.pl/sklep\">Przejdź do sklepu</a></p>");
                    }


                    // the_content();
                    ?>

                <?php endif; ?>

                <?php comments_template(); ?>

    <?php endwhile; ?>
<?php endif; ?>

    </article></main>

<?php get_footer(); ?>
	