<?php
/*
  Template Name: Scratchpad
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

                else :
                    ?>

                    <p>Custom paragraph.</p>


                    <?php
                    $substatus = $cardstack_am->getSubStatus();

                    echo ("<p>Status: " . $substatus . "</p>");

                    $date = new DateTime("now", new DateTimeZone("Europe/Warsaw"));
                    $date->setTimestamp($cardstack_am->getExpirationDate());

                    if ($substatus == "expiring") {
                        echo ("<p>Valid until: " . $date->format("Y-m-d H:i:s") . "</p>");
                    }


                    //amagi id: 118

                    $current_user = wp_get_current_user();

                    if (wc_customer_bought_product($current_user->user_email, $current_user->ID, 118)) {
                        echo ("This user bought Amagi!");

                        $pure_string = "12345";
                        $key = "!@#$%";

                        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
                        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
                        $obfuscated = mcrypt_encrypt(MCRYPT_BLOWFISH, $key,
                                utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);


                        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
                        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
                        $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $key,
                                $obfuscated, MCRYPT_MODE_ECB, $iv);


                        echo("staging.animagia.pl/vid/" . $obfuscated . " " . $decrypted_string);
                        
                        echo("<p>" . CardStackAmConstants::getKey() . "</p>");
                    }
                    ?>

                    <?php the_content(); ?> 

                <?php endif; ?>

                <?php comments_template(); ?>

            <?php endwhile; ?>
<?php endif; ?>

    </article></main>

<?php get_footer(); ?>
	