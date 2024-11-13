<?php
/*
Plugin Name: WooCommerce Custom Tag Filter
Description: Adds a custom tag filter to the WooCommerce All Products page.
Version: 1.0
Author:
*/

add_action('restrict_manage_posts', 'wc_custom_product_tag_filter');

function wc_custom_product_tag_filter() {
    global $typenow;
    if ($typenow == 'product') {
        $tags = get_terms(array(
            'taxonomy' => 'product_tag',
            'hide_empty' => false,
        ));
        ?>
        <select name="product_tag" id="product_tag">
            <option value=""><?php _e('Filter by Tag', 'woocommerce'); ?></option>
            <?php
            foreach ($tags as $tag) {
                $selected = (isset($_GET['product_tag']) && $_GET['product_tag'] == $tag->slug) ? 'selected="selected"' : '';
                echo '<option value="' . esc_attr($tag->slug) . '" ' . $selected . '>' . esc_html($tag->name) . '</option>';
            }
            ?>
        </select>
        <?php
    }
}

add_action('pre_get_posts', 'wc_filter_products_by_tag');

function wc_filter_products_by_tag($query) {
    global $typenow, $pagenow;
    if ($typenow == 'product' && is_admin() && $pagenow == 'edit.php' && isset($_GET['product_tag']) && $_GET['product_tag'] != '') {
        $query->query_vars['tax_query'] = array(
            array(
                'taxonomy' => 'product_tag',
                'field'    => 'slug',
                'terms'    => $_GET['product_tag'],
            ),
        );
    }
}
