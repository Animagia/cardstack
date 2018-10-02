<?php
/**
 * Template Name: Password change
 */
?>


<?php get_header(); ?>

<main <?php if (is_active_sidebar(1)): print('class="with-sidebar"'); endif ?> >

    <article class="page<?php if (get_theme_mod('page_breadcrumbs')): print(' has-breadcrumbs'); endif ?>" >

        <?php if (have_posts()): ?>
            <?php while (have_posts()): the_post(); ?>

                <h1><?php the_title(); ?></h1>

                <?php
                $cs_am_pw_changed = false;

                if (isset($_POST['submit_update'])) {

                    $cs_am_currentUserID = get_current_user_id();

                    $cs_am_currentpassword = $_POST['currentpassword'];
                    $cs_am_newpassword = $_POST['newpassword'];
                    $cs_am_confirmpassword = $_POST['confirmpassword'];

                    require_once ABSPATH . 'wp-includes/class-phpass.php';
                    $cs_am_wp_hasher = new PasswordHash(8, true);

                    $cs_am_db_prefix = CardStackAmConstants::getDbPrefix();

                    $cs_am_user_info = get_userdata($cs_am_currentUserID);
                    $cs_am_user_pass = $cs_am_user_info->user_pass;

                    if ($cs_am_confirmpassword !== $cs_am_newpassword) {
                        echo "<p>Hasła nie są takie same.</p>";
                    } else if ($cs_am_wp_hasher->CheckPassword($cs_am_currentpassword,
                                    $cs_am_user_pass)) {
                        $cs_am_passhash = wp_hash_password($cs_am_newpassword);
                        $upd = $wpdb->query("UPDATE " . $cs_am_db_prefix .
                                "_users SET user_pass = '" . $cs_am_passhash .
                                "' WHERE ID = " . $cs_am_currentUserID . " LIMIT 1");
                        if ($upd) {
                            echo "<p>Hasło zostało zmienione. <a href=\"";
                            echo get_home_url();
                            echo '/konto\"">Zaloguj się ponownie</a></p>';
                            $cs_am_pw_changed = true;
                        }
                    } else {
                        echo "<p>Podano niepoprawne aktualne hasło.</p>";
                    }
                }

                if (!$cs_am_pw_changed):
                    ?>

                    <form method='post' action='<?php echo get_home_url() ?>/chpasswd'>

                        <p class="login-password">
                            <label for="currentpassword">Aktualne hasło</label>
                            <input type='password' name='currentpassword' size='25'
                                   required="required">
                        </p>
                        <p class="login-password">
                            <label for="newpassword">Nowe hasło</label>
                            <input type='password' name='newpassword' size='25'
                                   required="required">
                        </p>
                        <p class="login-password">
                            <label for="confirmpassword">Potwierź nowe hasło</label>
                            <input type='password' name='confirmpassword' size='25'
                                   required="required">
                        </p>
                        <p class="login-submit">
                            <input type='submit' name='submit_update' value='Zmień hasło' class='subUpt'>
                        </p>

                    </form>

                <?php endif; ?>

            <?php endwhile; ?>
        <?php endif; ?>

    </article>
</main>

<?php get_footer(); ?>
