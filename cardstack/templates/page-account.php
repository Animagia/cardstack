<?php
/*
  Template Name: Account management
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
                if (!is_user_logged_in()) :

                    wp_login_form();

                    echo('<p><a href="https://animagia.pl/wp-login.php?action=lostpassword">Przypomnij hasło</a></p>');
                    echo '<p>Konto możesz założyć przy pierwszym zakupie w naszym sklepie. '
                    . 'By oglądać wideo dostępne bezpłatnie, nie musisz zakładać konta.</p>';

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

                    if ($substatus === "expiring") {
                        $date = new DateTime("now", new DateTimeZone("Europe/Warsaw"));
                        $date->setTimestamp($cardstack_am->getExpirationDate());
                        echo ("<p><strong>Wygasające</strong>, ważne do: " . $date->format("Y-m-d H:i:s") .
                        ". <a href=\"https://animagia.pl/\">Zacznij oglądać anime!</a></p>");
                    } else if ($substatus === "active") {
                        echo ("<p><strong>Aktywne.</strong> " .
                        "<a href=\"https://animagia.pl/\">Zacznij oglądać anime!</a></p>" .
                        "<p><small>Jeśli chcesz zrezygnować, <a href=\"https://www.paypal.com/myaccount/autopay\">" .
                        "anuluj cykliczną płatność</a> w PayPal.</small></p>");
                    } else {
                        echo ("<p>Nieaktywne.</p>");
                    }

                    $current_user = wp_get_current_user();


                    echo ("<h2>Pliki do pobrania</h2>");

                    $cs_am_can_download_anything = false;


                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 1180)) {
                        echo ("<h3>Film K-On!</h3>");
                        $cardstack_am->printDdlLink("kon");
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
                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 1012)) {
                        echo ("<h3>Servamp: Alice in the Garden</h3>");
                        $cardstack_am->printServampLink();
						$cs_am_can_download_anything = true;
                    }
                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 989)) {
                        echo ("<h3>DanMachi: Arrow of the Orion</h3>");
                        $cardstack_am->printDanMachiLink();
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
                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 646)) {
                        echo ("<h3>Tamako Love Story</h3>");
                        $cardstack_am->printTamakoLink();
						$cs_am_can_download_anything = true;
                    }

                    if ($cs_am_can_download_anything === false) {
                        echo ("<p>Brak zakupionych anime. " .
                        "<a href=\"https://animagia.pl/sklep\">Przejdź do sklepu</a></p>");
                    }


                    ?>

                <?php endif; ?>

    <?php endwhile; ?>
<?php endif; ?>

    </article></main>

<?php get_footer(); ?>
	
