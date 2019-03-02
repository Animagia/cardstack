<!DOCTYPE html>

<html <?php language_attributes(); ?>>

    <head>
        <meta http-equiv="content-type"
              content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <title><?php wp_title(); ?></title>
        
        <?php 
        $csam_description =  get_post_meta(get_the_id(), "csam_description", true);
        if($csam_description) : ?>
        
        <meta name="description" content="<?php print($csam_description); ?>"/>
        
        <?php endif; ?>
        
        <link href="<?php print(get_stylesheet_uri()); ?>" rel="stylesheet" type="text/css" />

        <?php
        wp_head();
        ?>

        <?php
        /* Animagia.pl-specific hack */
        if (CardStackAm::templateHasVideo()) {
            print '<link href="https://static.animagia.pl/video-js.css" ' .
                    ' rel="stylesheet" type="text/css" />' . PHP_EOL;
            
            print '<link href="https://static.animagia.pl/video-js-custom.css" ' .
                    ' rel="stylesheet" type="text/css" />';
        }

        if (is_page_template('templates/page-welcome.php')){
            print '<link href="' . get_template_directory_uri() . '/page-welcome-style.css" ' .
                ' rel="stylesheet" type="text/css" />';
        }
        /* end hack */
        ?>



    </head>

    <body <?php body_class(); ?>>


        <header id="header-main">

            <?php

            if (!is_singular() || is_front_page()) {
                print('<h1 ' . $cardstack_additionalClass . ' id="site-title">');
            } else {
                print('<p ' . $cardstack_additionalClass . ' id="site-title">');
            }

            $cardstack_headerImageUrl = get_header_image();
            if (!empty($cardstack_headerImageUrl)) {
                $cardstack_headerImageAlt = get_bloginfo('name') . " – " . get_bloginfo('description');
                print('<img id="titular-logo" src="' . $cardstack_headerImageUrl . '" alt="' .
                        $cardstack_headerImageAlt  . '" />');
            }
			
			$cardstack_headerTextColor = get_header_textcolor();
			if($cardstack_headerTextColor != 'blank') :

            print('<a id="home-link" href="' . esc_url(home_url()) . '">');
            bloginfo('name');
            print('</a>');

            if (!is_singular() || is_front_page()) {
                print('</h1>');
            } else {
                print('</p>');
            }

            print('<p ' . $cardstack_additionalClass . ' id="site-description">');
            bloginfo('description');
            print('</p>');
			
			else:
			
			print('</h1>');
			
			endif;
			
            ?>


            <nav id="nav-main">

                <span id="m1"></span>
                <span id="m2"></span>

<?php
if (has_nav_menu('cardstack_nav_menu')) :

	echo "<ul>";

    $cardstack_menuArgs = array(
        'theme_location' => 'cardstack_nav_menu',
        'container' => false,
        'container_class' => '',
        'container_id' => '',
        'menu_class' => '',
        'menu_id' => 'nav0',
        'echo' => true,
        'fallback_cb' => false,
        'before' => '',
        'after' => '',
        'link_before' => '',
        'link_after' => '',
        'items_wrap' => '%3$s', //'<ul id="%1$s" class="%2$s">%3$s</ul>'
        'depth' => 2,
        'walker' => ''
    );

    wp_nav_menu($cardstack_menuArgs);
    
    ?>
	<li><a style="background-image: url(<?php print get_template_directory_uri() . '/fbbtn_plain.svg'?>);
	background-size: contain; background-repeat: no-repeat; background-position: right;
	box-sizing: border-box; padding-right: 40px; color: #4267b2;"
	id="home-link" href="https://facebook.com/WydawnictwoAnimagia">Polub nas</a></li></ul>
    


                <?php else : ?>

                    <ul id="nav0">

    <?php if ('page' != get_option('show_on_front')) : ?>
                            <li><a id="home-link" href="<?php print(esc_url(home_url())); ?>"><?php
                            $cardstack_customHomeLinkName = get_theme_mod('custom_home_link_title');
                            if (!empty($cardstack_customHomeLinkName)) {
                                print($cardstack_customHomeLinkName);
                            } else {
                                //print(__('Home'));
                                print( '<img src="' . get_template_directory_uri() . '/home.png" />' );
                            }
                            ?></a></li>
                                    <?php
                                endif;

                                $cardstack_listPagesArgs = array(
                                    'sort_order' => 'ASC',
                                    'sort_column' => 'menu_order',
                                    'depth' => 2,
                                    'post_type' => 'page',
                                    'post_status' => 'publish',
                                    'title_li' => null
                                );
                                wp_list_pages($cardstack_listPagesArgs);
                                ?>
                    </ul>

<?php endif; ?>

                <a id="menu-open" href="#m1" tabindex="-1">&#9662;</a>
                <a id="menu-close" href="#m2" tabindex="-1">&#9652;</a>

            </nav>


<?php if (function_exists('pll_the_languages')) : ?>
                <nav id="language-switcher">
                    <ul>
    <?php pll_the_languages($args); ?>
                    </ul>
                </nav>
<?php endif; ?>

        </header>
