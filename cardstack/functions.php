<?php

function cardstack_theme_setup() {

    global $content_width;
    if (!isset($content_width))
        $content_width = 655;

    register_nav_menu('cardstack_nav_menu', 'Top');

    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');

    $custom_header_args = array(
        'default-text-color' => '333',
        'uploads' => true,
    );
    add_theme_support('custom-header', $custom_header_args);

    add_theme_support('custom-background');
}

add_action('after_setup_theme', 'cardstack_theme_setup');

function cardstack_widgets_setup() {
    register_sidebars(1, array(
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''));
}

add_action('widgets_init', 'cardstack_widgets_setup');


function cardstack_enqueue_stuff() {

    if (is_singular() && comments_open()) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'cardstack_enqueue_stuff');

function cardstack_favicon() {
    $faviconUrl = get_theme_mod('favicon');
    if (!empty($faviconUrl)) {

        $fileNameExtension = pathinfo($faviconUrl, PATHINFO_EXTENSION);

        if ($fileNameExtension == 'png') {
            print('<link rel="shortcut icon" type="image/png" href="' . esc_url(get_theme_mod('favicon')) . '" />');
        } else if ($fileNameExtension == 'gif') {
            print('<link rel="shortcut icon" type="image/gif" href="' . esc_url(get_theme_mod('favicon')) . '" />');
        }
    }
}

add_action('wp_head', 'cardstack_favicon');

function cardstack_customize_css() {
    $custom_css = get_theme_mod('custom_css');
    if (!empty($custom_css)) {
        print('<style type="text/css">' . $custom_css . '</style>');
    }
}

add_action('wp_head', 'cardstack_customize_css');



/* ==== Filters ==== */

function cardstack_wp_title($title) {
    if (empty($title)) {
        return get_bloginfo('name');
    } else {
        $separator = get_theme_mod('custom_title_separator');
        if (empty($separator)) {
            $separator = ' | ';
        } else {
            $separator = ' ' . $separator . ' ';
        }
        $title_buffer = get_the_title() . $separator;
        $ancestors = get_post_ancestors(get_the_ID());
        foreach ($ancestors as $ancestor) {
            $title_buffer .= (get_the_title($ancestor) . $separator);
        }
        return $title_buffer . get_bloginfo('name');
    }
}

add_filter('wp_title', 'cardstack_wp_title');

function cardstack_remove_more_jump_link($url) { //prevent the more link from jumping to the break in the post
    $startIndex = strpos($url, '#more-');
    if ($startIndex) {
        $endIndex = strpos($url, '"', $startIndex);
    }
    if ($endIndex) {
        $url = substr_replace($url, '', $startIndex, $endIndex - $startIndex);
    }
    return $url;
}

add_filter('the_content_more_link', 'cardstack_remove_more_jump_link');



/* ==== Editor styles ==== */

function cardstack_add_editor_styles() {
    add_editor_style();
}

add_action('init', 'cardstack_add_editor_styles');



/* ==== Theme Customization API ==== */

function cardstack_customize_register($wp_customize) { //All sections, settings, and controls go here

    class cardstack_Customize_Textarea_Control extends WP_Customize_Control {

        public $type = 'textarea';

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea($this->value()); ?></textarea>
            </label>
            <?php
        }

    }

    $wp_customize->add_section('cardstack_section', array(
        'title' => 'Card Stack theme',
        'priority' => 1337,
    ));

    $wp_customize->add_setting('custom_home_link_title', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_setting('custom_more_link_title', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_setting('custom_title_separator', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field'
    ));

    $wp_customize->add_setting('custom_footer_text', array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post'
    ));

    $wp_customize->add_setting('slider_image_urls', array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post'
    ));

    $wp_customize->add_setting('custom_welcome_section', array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post'
    ));

    $wp_customize->add_setting('custom_html_below_menu', array(
        'default' => '',
        'sanitize_callback' => 'wp_kses_post'
    ));

    $wp_customize->add_setting('custom_css', array(
        'default' => '',
        'sanitize_callback' => 'cardstack_sanitize_custom_css'
    ));

    $wp_customize->add_setting('favicon', array(
        'default' => ''
    ));

    $wp_customize->add_setting('post_breadcrumbs', array(
        'default' => false
    ));

    $wp_customize->add_setting('page_breadcrumbs', array(
        'default' => false
    ));

    $wp_customize->add_setting('hide_author', array(
        'default' => false
    ));

    $wp_customize->add_setting('hide_categories', array(
        'default' => false
    ));

    $wp_customize->add_setting('hide_tags', array(
        'default' => false
    ));

    $wp_customize->add_setting('hide_private_posts', array(
        'default' => false
    ));

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'custom_home_link_title_ctrl', array(
        'label' => 'Custom home link text',
        'section' => 'cardstack_section',
        'settings' => 'custom_home_link_title',
    )));

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'custom_more_link_title_ctrl', array(
        'label' => 'Custom "read more" link text',
        'section' => 'cardstack_section',
        'settings' => 'custom_more_link_title',
    )));

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'custom_title_separator_ctrl', array(
        'label' => 'Custom title separator (used in the <title> tag)',
        'section' => 'cardstack_section',
        'settings' => 'custom_title_separator',
    )));

    $wp_customize->add_control(new cardstack_Customize_Textarea_Control($wp_customize, 'custom_footer_text_ctrl', array(
        'label' => 'Custom footer text or HTML',
        'section' => 'cardstack_section',
        'settings' => 'custom_footer_text',
    )));

    $wp_customize->add_control(new cardstack_Customize_Textarea_Control($wp_customize, 'slider_image_urls_ctrl', array(
        'label' => 'Slider image URLs (separate with |)',
        'section' => 'cardstack_section',
        'settings' => 'slider_image_urls',
    )));

    $wp_customize->add_control(new cardstack_Customize_Textarea_Control($wp_customize, 'custom_welcome_section_ctrl', array(
        'label' => 'Welcome section text or HTML',
        'section' => 'cardstack_section',
        'settings' => 'custom_welcome_section',
    )));

    $wp_customize->add_control(new cardstack_Customize_Textarea_Control($wp_customize, 'custom_html_below_menu_ctrl', array(
        'label' => 'Additional HTML to append to the menu',
        'section' => 'cardstack_section',
        'settings' => 'custom_html_below_menu',
    )));

    $wp_customize->add_control(new cardstack_Customize_Textarea_Control($wp_customize, 'custom_css_ctrl', array(
        'label' => 'Custom CSS',
        'section' => 'cardstack_section',
        'settings' => 'custom_css',
    )));

    $wp_customize->add_control(
            new WP_Customize_Image_Control(
            $wp_customize, 'favicon_ctrl', array(
        'label' => 'Favicon (PNG or GIF)',
        'section' => 'cardstack_section',
        'settings' => 'favicon'
            )
            )
    );

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'post_breadcrumbs_ctrl', array(
        'label' => 'Enable breadcrumb links for posts',
        'section' => 'cardstack_section',
        'settings' => 'post_breadcrumbs',
        'type' => 'checkbox'
    )));

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'page_breadcrumbs_ctrl', array(
        'label' => 'Enable breadcrumb links for pages',
        'section' => 'cardstack_section',
        'settings' => 'page_breadcrumbs',
        'type' => 'checkbox'
    )));

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hide_author_ctrl', array(
        'label' => 'Hide post author',
        'section' => 'cardstack_section',
        'settings' => 'hide_author',
        'type' => 'checkbox'
    )));

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hide_categories_ctrl', array(
        'label' => 'Hide post categories',
        'section' => 'cardstack_section',
        'settings' => 'hide_categories',
        'type' => 'checkbox'
    )));

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hide_tags_ctrl', array(
        'label' => 'Hide post tags',
        'section' => 'cardstack_section',
        'settings' => 'hide_tags',
        'type' => 'checkbox'
    )));

    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'hide_private_posts_ctrl', array(
        'label' => 'On the posts index page, show only published posts even to logged-in users',
        'section' => 'cardstack_section',
        'settings' => 'hide_private_posts',
        'type' => 'checkbox'
    )));
}

add_action('customize_register', 'cardstack_customize_register');

//option to exclude private posts from the loop so that they don't appear even to logged-in users
function cardstack_exclude_private_posts($query) { 
    if (get_theme_mod('hide_private_posts')) {
        if ($query->is_home() && $query->is_main_query()) {
            $query->set('post_status', 'publish');
        }
    }
}

add_action('pre_get_posts', 'cardstack_exclude_private_posts');

function cardstack_sanitize_custom_css($custom_styles) {
    $custom_styles = strip_tags($custom_styles);
    $custom_styles = str_replace('@import', '', $custom_styles);
    $custom_styles = str_replace('behavior', '', $custom_styles);
    $custom_styles = str_replace('expression', '', $custom_styles);
    $custom_styles = str_replace('binding', '', $custom_styles);
    return $custom_styles;
}




/* ==== Animagia.pl-specific stuff ==== */


require_once( __DIR__ . '/includes/am_constants.php');


class CardStackAm {

    static function getStatus($sub) {
        return $sub->data["status"];
    }

    static function isActive($sub) {
        $subStatus = self::getStatus($sub);
        return ($subStatus === "active" || $subStatus === "on-hold");
    }

    static function isExpiring($sub) {
        if (self::getStatus($sub) != "pending-cancel") {
            return false;
        }

        $expiration = $sub->data["schedule_end"]->getTimestamp();

        if (time() < $expiration) {
            return true;
        }

        return false;
    }

    static function getSubStatus() {
        
        if (in_array(wp_get_current_user()->user_email,
                        CardStackAmConstants::getEmailsUsedBySiteOwner())) {
            return "active";
        }
        
        
        $expiring = false;

        $subscriptions = hforce_get_users_subscriptions();
        foreach ($subscriptions as $sub) {
            if (self::isActive($sub)) {
                return "active";
            }
            if (self::isExpiring($sub)) {
                $expiring = true;
            }
        }

        if ($expiring) {
            return "expiring";
        }

        return "invalid";
    }

    static function getExpirationDate() {
        $expiration = -1;

        $subscriptions = hforce_get_users_subscriptions();
        foreach ($subscriptions as $sub) {
            if (self::isExpiring($sub)) {
                $expiration = max($expiration, $sub->data["schedule_end"]->getTimestamp());
            }
        }

        if ($expiration == -1) {
            throw new Exception("Tried to get expiration date, but no sub is expiring.");
        }

        return $expiration;
    }

    static function userCanStreamProduct($product_id) {
        if (wc_customer_bought_product(wp_get_current_user()->user_email, get_current_user_id(),
                        $product_id)) {
            return true;
        }

        if (self::getSubStatus() == "invalid") {
            return false;
        }

        return true;
    }

    static function obfuscateString($pure_string) {
        $key = CardStackAmConstants::getKey();
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $obfuscated = bin2hex(mcrypt_encrypt(MCRYPT_BLOWFISH, $key, utf8_encode($pure_string),
                        MCRYPT_MODE_ECB, $iv));
        return $obfuscated;
    }

    static function printArukuLink() {

        echo("<p>");

        $pure_string = "Aruku_" . "00" . "_" . time() . "_" . $_SERVER['REMOTE_ADDR'];
        $key = CardStackAmConstants::getKey();

        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $obfuscated = bin2hex(mcrypt_encrypt(MCRYPT_BLOWFISH, $key, utf8_encode($pure_string),
                        MCRYPT_MODE_ECB, $iv));

        echo("<a href=\"" . CardStackAmConstants::getVidUrl() .
        "ddl/serve_ddl.php?token=" . $obfuscated . "\">");
        echo("[Animagia.pl] Aruku to Iu Koto 1080p.mkv");
        echo("</a>");

        echo("</p>");

        echo("<p>Aruku to Iu Koto i jego tłumaczenie są na otwartej licencji. " .
        "Zobacz <a href=\"https://animagia.pl/credits\">" .
        "uzanania autorstwa</a>.</p>");
    }

    static function printShakeLink() {

        echo("<p>");

        $pure_string = "Shake_" . "00" . "_" . time() . "_" . $_SERVER['REMOTE_ADDR'];
        $key = CardStackAmConstants::getKey();

        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $obfuscated = bin2hex(mcrypt_encrypt(MCRYPT_BLOWFISH, $key, utf8_encode($pure_string),
                        MCRYPT_MODE_ECB, $iv));

        echo("<a href=\"" . CardStackAmConstants::getVidUrl() .
        "ddl/serve_ddl.php?token=" . $obfuscated . "\">");
        echo("[Animagia.pl] Shake-chan 720p.mkv");
        echo("</a>");

        echo("</p>");

        echo("<p>Shake-chan i jego tłumaczenie są na otwartej licencji. " .
        "Zobacz <a href=\"https://animagia.pl/credits\">" .
        "uzanania autorstwa</a>.</p>");
    }

    static function printAmagiLinks() {
        if (IP_Geo_Block::get_geolocation()['code'] !== 'PL') {
            ?>
                                                    <p>Linki do ściągnięcia działają tylko w Polsce. Skontaktuj się z nami,
                                                        jeśli kraj rozpoznano niepoprawnie, lub jeśli tymczasowo przebywasz
                                                        w innym kraju UE.</p>
            <?php
            return;
        }

        self::printIpNotice();

        echo("<p>");
        foreach (["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "NCOP", "NCED"] as
                    $cardstack_am_link_iter) {
            $pure_string = "Amagi_" . $cardstack_am_link_iter . "_" . time() . "_" . $_SERVER['REMOTE_ADDR'];
            $key = CardStackAmConstants::getKey();

            $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $obfuscated = bin2hex(mcrypt_encrypt(MCRYPT_BLOWFISH, $key, utf8_encode($pure_string),
                            MCRYPT_MODE_ECB, $iv));

            echo("<a href=\"" . CardStackAmConstants::getVidUrl() .
            "ddl/serve_ddl.php?token=" . $obfuscated . "\">");
            echo("[Animagia.pl] Amagi Brilliant Park " . $cardstack_am_link_iter .
            " 1080p.mkv");
            echo("</a>");

            if ($cardstack_am_link_iter !== "NCED") {
                echo "<br />";
            }
        }
        echo("</p>");

        echo("<p>Polskie napisy są połączeniem dwóch tłumaczeń, z których po jednym wykonały Studio PDK " .
        "i wydawnictwo Animagia.pl. Zobacz <a href=\"https://animagia.pl/credits\">" .
        "uzanania autorstwa</a>.</p>");
    }
	
	
    static function printChuuniLink() {
		echo('<p><strong>Uwaga:</strong> Plik wideo z filmem jest przeznaczony tylko do Twojego <strong>osobistego użytku</strong> ' .
		'i nie może być udostępniany innym osobom, chyba że przepisy prawa stanowią inaczej.</p>');
		
        echo("<p>");

        $pure_string = "Chuuni_" . "00" . "_" . time() . "_" . $_SERVER['REMOTE_ADDR'];
        $key = CardStackAmConstants::getKey();

        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $obfuscated = bin2hex(mcrypt_encrypt(MCRYPT_BLOWFISH, $key, utf8_encode($pure_string),
                        MCRYPT_MODE_ECB, $iv));

        echo("<a href=\"" . CardStackAmConstants::getVidUrl() .
        "ddl/serve_ddl.php?token=" . $obfuscated . "\">");
        echo("[Animagia.pl] Chuunibyou…Take On Me 1920×1036.mkv");
        echo("</a>");

        echo("</p>");
    }
	

    static function printIpNotice() {
        echo("<p>");

        echo '<strong>Uwaga:</strong> ' .
        'poniższe pliki wideo są wolne od ograniczeń technicznych, ale przeznaczone tylko do Twojego ' .
        '<strong>osobistego użytku</strong> i nie mogą być udostępniane innym osobom, ' .
        'chyba że przepisy prawa stanowią inaczej. Ustawa o prawie autorskim i prawach pokrewnych ' .
        'przewiduje odpowiedzialność karną za rozpowszechnianie cudzych utworów bez uprawnienia. ' .
        '<strong>Szanuj prawa nasze i japońskich twórców.</strong>';

        echo("</p>");
    }

}

$cardstack_am = new CardStackAm();

function cardstack_am_no_repeat_purchase( $purchasable, $product ) {
    $premium = CardStackAmConstants::getPremiumServiceId();
    $amagi = CardStackAmConstants::getAmagiId();
    
    $product_id = $product->id;
    
    if ($amagi == $product_id && wc_customer_bought_product(wp_get_current_user()->user_email,
                    get_current_user_id(), $product_id)) {
        return false;
    }
    
    if($premium == $product_id) {
        $substatus = CardStackAm::getSubStatus();
        if("invalid" != $substatus) {
            return false;
        }
    }
    
    return $purchasable;
}
add_filter( 'woocommerce_is_purchasable', 'cardstack_am_no_repeat_purchase', 10, 2 );

remove_filter( 'the_content', 'wpautop' );

// remove unnecessary junk from checkout process
 
function cardstack_am_remove_checkout_phone( $fields ) {

    unset($fields['billing']['billing_phone']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_company']);

    if (0 === $woocommerce->cart->total) {
        unset($fields['billing_country']);
        unset($fields['billing_first_name']);
        unset($fields['billing_last_name']);
        unset($fields['billing_address_1']);
        unset($fields['billing_city']);
        unset($fields['billing_state']);
        unset($fields['billing_postcode']);
    }

    return $fields;
}

add_filter( 'woocommerce_checkout_fields' , 'cardstack_am_remove_checkout_phone' );

add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );


// Add checkboxes at checkout
 
add_action( 'woocommerce_review_order_before_submit', 'cardstack_am_add_checkout_privacy_policy', 9 );
   
function cardstack_am_add_checkout_privacy_policy() {

    woocommerce_form_field('am_privacy_policy',
            array(
        'type' => 'checkbox',
        'class' => array('form-row privacy'),
        'label_class' => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
        'input_class' => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
        'required' => true,
        'label' => 'Akceptuję <a target="_blank" href="https://animagia.pl/regulamin">regulamin</a> oraz ' .
        '<a target="_blank" href="https://animagia.pl/privacy">politykę prywatności i ciasteczek</a>.',
    ));

    woocommerce_form_field('am_instant_access',
            array(
        'type' => 'checkbox',
        'class' => array('form-row privacy'),
        'label_class' => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
        'input_class' => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
        'required' => true,
        'label' => 'Wyrażam zgodę na udostępnienie mi cyfrowych materiałów wideo natychmiast ' .
        'po zakończeniu procesu płatności, ze świadomością, że od momentu ich udostępnienia ' .
        'nie będzie już możliwości odstąpienia od umowy.',
    ));
}

// Show notice if customer does not tick
   
add_action('woocommerce_checkout_process', 'cardstack_am_not_approved_privacy');
add_action('woocommerce_checkout_process', 'cardstack_am_not_approved_instant_access');

function cardstack_am_not_approved_privacy() {
    if (!(int) isset($_POST['am_privacy_policy'])) {
        wc_add_notice('Akceptacja regulaminu oraz polityki prywatności i ciasteczek jest wymagana.',
                'error');
    }
}

function cardstack_am_not_approved_instant_access() {
    if (!(int) isset($_POST['am_instant_access'])) {
        wc_add_notice('Zgoda na natychmiastowe udostępnienie materiałów wideo jest wymagana. ' .
                'Zamówienie bez takiej zgody, z opóźnioną o 14 dni realizacją, ' .
                'można złożyć wyłącznie mailowo.', 'error');
    }
}

function cardstack_am_reset_pass_url() {
    $siteURL = get_option('siteurl');
    return "{$siteURL}/wp-login.php?action=lostpassword";
}

add_filter('lostpassword_url', 'cardstack_am_reset_pass_url', 11, 0);

function cardstack_amreplacePayPalIcon($iconUrl) {
	return get_bloginfo('stylesheet_directory') . '/pp_all_cards.png';
}
 
add_filter('woocommerce_paypal_icon', 'cardstack_amreplacePayPalIcon');

function cardstack_am_wc_empty_cart_redirect_url() {
	return 'https://animagia.pl/sklep/';
}
add_filter( 'woocommerce_return_to_shop_redirect', 'cardstack_am_wc_empty_cart_redirect_url' );


/* block PayPal in countries other than Poland */

function cardstack_am_disable_countries($available_gateways) {
    global $woocommerce;
    if (isset($available_gateways['paypal'])  && WC_Geolocation::geolocate_ip()['country'] !== 'PL' ) {
        unset($available_gateways['paypal']);
    }
    return $available_gateways;
}

add_filter( 'woocommerce_available_payment_gateways', 'cardstack_am_disable_countries' );


/* Customize Subscriptions plugin */

remove_action('woocommerce_review_order_after_order_total', array(HForce_Subscription_Cart, 'display_recurring_totals'));
remove_action('woocommerce_cart_totals_after_order_total', array(HForce_Subscription_Cart, 'display_recurring_totals'));


/* Only load WC scripts and styles on cart/checkout page */
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_styles_scripts', 99 );

function dequeue_woocommerce_styles_scripts() {
    if ( function_exists( 'is_woocommerce' ) ) {
        if ( /* ! is_woocommerce()  && */ ! is_cart() && ! is_checkout() ) {
            # Styles
            wp_dequeue_style( 'woocommerce-general' );
            wp_dequeue_style( 'woocommerce-layout' );
            wp_dequeue_style( 'woocommerce-smallscreen' );
            wp_dequeue_style( 'woocommerce_frontend_styles' );
            wp_dequeue_style( 'woocommerce_fancybox_styles' );
            wp_dequeue_style( 'woocommerce_chosen_styles' );
            wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
            # Scripts
            wp_dequeue_script( 'wc_price_slider' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'wc-checkout' );
            wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
        }
    }
}

/* when product is added to cart, remove other products */
add_filter( 'woocommerce_add_cart_item_data', 'cardstack_am_only_one_in_cart' );

function cardstack_am_only_one_in_cart( $cart_item_data ) {

    global $woocommerce;
    $woocommerce->cart->empty_cart();

    // Do nothing with the data and return
    return $cart_item_data;
}



require_once( __DIR__ . '/includes/product_related_actions.php');

