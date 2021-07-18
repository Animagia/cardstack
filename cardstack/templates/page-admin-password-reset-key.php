<?php
/**
 * Template page for generating new password reset key for requested user (login or e-mail).
 *
 * Template Name: Admin Password Reset Key
 */
?>

<?php get_header(); ?>

    <article class="page<?php if (get_theme_mod('page_breadcrumbs')): print(' has-breadcrumbs'); endif ?>" >

        <?php if (current_user_can('administrator')): ?>
            <?php if (have_posts()): ?>
                <?php while (have_posts()): the_post(); ?>

                    <h1><?php the_title(); ?></h1>

                    <?php the_content() ?>
                <?php endwhile; ?>
            <?php endif; ?>

            <form class="form" action="" method='post'>
                <label for="user_login">Nazwa użytkownika lub e-mail<br /></label>
                <input type="text" name="user_login" id="user_login" class="input" value="" size="20" required>

                <input type="submit" name="submit" id="submit" class="button" value="Wygeneruj klucz">
                <?php wp_nonce_field( 'get_password_reset_key_form'); ?>
            </form>

            <?php
            if (!empty($_POST) && check_admin_referer( 'get_password_reset_key_form')) {
                $input = $_POST['user_login'];

                $user = get_user_by('login', $input);

                if (!$user) {
                    $user = get_user_by('email', $input);
                }

                echo 'Zapytanie: ' . $input . '<br /><hr>';

                if ($user) {
                    echo 'Użytkownik: ' . $user->user_login . '<br />';
                    echo 'E-mail: ' . '<a href="mailto:' . $user->user_email . '">' . $user->user_email . '</a><br />';
                    echo 'Role: ' . implode(', ', $user->roles) . '<br />';

                    $key = get_password_reset_key($user);

                    if (!is_wp_error($key)) {
                        echo '<br />';

                        echo 'Klucz: ' . $key . '<br />';

                        /**
                         * Code taken from handler sending password retrieval email to user.
                         *
                         * @see retrieve_password() in wp-login.php line 369
                         */
                        $link = network_site_url( 'wp-login.php?action=rp&key=$key&login=' . rawurlencode( $user->user_login ), 'login' );

                        echo 'Link: ' . '<a href="' . $link . '">' . $link . '</a><br />';
                    }
                    else {
                        echo 'Error: ' . $key->get_error_message();
                    }
                }
                else {
                    print('Nie znaleziono użytkownika.');
                }
            }
            ?>
        <?php endif; ?>
    </article>
</main>

<?php get_footer(); ?>
