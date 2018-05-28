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
                        ". <a href=\"https://animagia.pl/amagi-brilliant-park-odc-1/\">Zacznij oglądać anime!</a></p>");
                    } else if ($substatus === "active") { //active
                        echo ("<p><strong>Aktywne.</strong> " .
                        "<a href=\"https://animagia.pl/amagi-brilliant-park-odc-1/\">Zacznij oglądać anime!</a></p>" .
                        "<p><small>Jeśli chcesz zrezygnować, <a href=\"https://www.sandbox.paypal.com/myaccount/autopay\">" .
                        "anuluj cykliczną płatność</a> w PayPal.</small></p>");
                    } else {
                        echo ("<p>Nieaktywne.</p>");
                    }

                    $current_user = wp_get_current_user();

                    echo ("<h2>Zakupione anime</h2>");

                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID,
                                    CardStackAmConstants::getAmagiId())) {
                        echo ("<p>Amagi Brilliant Park – <a href=\"https://animagia.pl/amagi-brilliant-park-odc-1/\">zacznij oglądać</a></p>");
                    } else {
                        echo ("<p>Brak zakupionych anime. " .
                        "<a href=\"https://animagia.pl/sklep\">Przejdź do sklepu</a></p>");
                    }

                    echo ("<h2>Pliki do pobrania</h2>");

                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID,
                                    CardStackAmConstants::getAmagiId())) {
                        $cardstack_am->printAmagiLinks();
                    } else {
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
	