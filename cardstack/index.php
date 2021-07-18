<?php

get_header();

$cardstack_sliderUrlBundle = get_theme_mod('slider_image_urls');
if (!empty($cardstack_sliderUrlBundle)) :

    $cardstack_sliderUrls = explode('|', $cardstack_sliderUrlBundle);

    print('<ul class="slides">');

    $cardstack_checkedString = ' checked';
    $cardstack_iteration = 1;
    $cardstack_navdotshtml = '';
    foreach ($cardstack_sliderUrls as $cardstack_url) {
        print('<input type="radio" name="radio-btn" id="img-' . $cardstack_iteration . '"' . $cardstack_checkedString . ' /><li class="slide-container"><div class="slide">');
        $cardstack_checkedString = '';

        print('<img src="' . $cardstack_url . '" />');
        print('</div><div class="nav">');

        $cardstack_prevslide = $cardstack_iteration - 1;
        if ($cardstack_prevslide == 0) {
            $cardstack_prevslide = sizeof($cardstack_sliderUrls);
        }
        $cardstack_nextslide = $cardstack_iteration + 1;
        if ($cardstack_nextslide > sizeof($cardstack_sliderUrls)) {
            $cardstack_nextslide = 1;
        }

        $cardstack_navdotshtml = $cardstack_navdotshtml . '<label for="img-' . $cardstack_iteration . '" class="nav-dot" id="img-dot-' . $cardstack_iteration . '"></label>';

        print('<label for="img-' . $cardstack_prevslide . '" class="prev">&#x2039;</label>');
        print('<label for="img-' . $cardstack_nextslide . '" class="next">&#x203a;</label>');

        print('</div></li>');
        
        $cardstack_iteration++;
    }

    print('<li class="nav-dots">');
    print($cardstack_navdotshtml);
    print('</li></ul>');

endif;


$cardstack_welcomeSectionText = get_theme_mod('custom_welcome_section');
$cardstack_jsonObject = json_decode($cardstack_welcomeSectionText);
if ($cardstack_jsonObject != null) {
    $cardstack_jsonLanguage = substr(get_bloginfo('language'), 0, 2);
    if (isset($cardstack_jsonObject->{$cardstack_jsonLanguage})) {
        $cardstack_welcomeSectionText = $cardstack_jsonObject->{$cardstack_jsonLanguage};
    } else if (isset($cardstack_jsonObject['default'])) {
        $cardstack_welcomeSectionText = $cardstack_jsonObject['default'];
    } else {
        $cardstack_welcomeSectionText = '';
    }
}

if (!empty($cardstack_welcomeSectionText)) {
    if(!have_posts()) {
        $cardstack_additionalClass = ' class="solo"';
    } else {
        $cardstack_additionalClass = '';
    }
    print('<section id="welcome"' . $cardstack_additionalClass . '>' . $cardstack_welcomeSectionText . '</section>');
}

if (have_posts()) {

    while (have_posts()) {

        the_post();

        print( '<article><h1 ');
        post_class('post-title');
        print('><a href="' . get_permalink() . '">');
        the_title();
        print('</a></h1>');

        print('<span class="date"><a class="secondary-permalink" href="' . get_permalink() . '">');
        if (!get_theme_mod('hide_author')) {
            the_author();
            print(', ');
        }
        print('<time datetime="' . get_the_date('Y-m-d') . '">');
        print(get_the_date());
        print('</time>');
        print('</a>');
        if (has_category() && !get_theme_mod('hide_categories')) {
            print(' &middot; ' . __('Categories') . ': ');
            the_category(", ");
        }
        if (has_tag() && !get_theme_mod('hide_tags')) {
            print(' &middot; ' . __('Tags') . ': ');
            the_tags("", ", ");
        }
        print('</span>');

        $cardstack_customMoreLinkText = get_theme_mod('custom_more_link_title');
        if (empty($cardstack_customMoreLinkText)) {
            $cardstack_customMoreLinkText = __('(more&hellip;)');
        }
        the_content($cardstack_customMoreLinkText, false);

        $cardstack_pos = strpos($post->post_content, '<!--more-->');

        if ($numpages > 1 && !($cardstack_pos)) {
            print('<p><a class="more-link" href="' . get_permalink() . '">' . $cardstack_customMoreLinkText . '</a></p>');
        }

        $cardstack_num_comments = get_comments_number();
        if ((comments_open() || $cardstack_num_comments > 0) && !($cardstack_pos)) {
            print('<p class="link-to-comments">');
            comments_popup_link();
            print('</p>');
        }

        print('</article>');
    }

    if (get_next_posts_link() != null || get_previous_posts_link() != null) {
        print('<nav class="page-switcher"><p>');
        posts_nav_link();
        print('</p></nav>');
    }

    print('</main>');
}

if (is_active_sidebar(1)) :
    print('<aside id="bottom-bar">');
    dynamic_sidebar();
    print('</aside>');
endif;

get_footer();
