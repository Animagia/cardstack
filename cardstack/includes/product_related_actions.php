<?php

add_action('template_redirect','cs_am_product_redirect');
function cs_am_product_redirect(){
    if (class_exists('WooCommerce')){
        if(is_product()){
            wp_redirect(home_url());
            exit();
        }
    } 
    return;
} 
