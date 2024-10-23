<?php 

// Register the custom endpoint for user reviews
function add_custom_reviews_endpoint() {
    add_rewrite_endpoint('my-reviews', EP_PAGES);
}
add_action('init', 'add_custom_reviews_endpoint');

// Add the reviews link to the WooCommerce account menu
function add_custom_reviews_link_my_account($items) {
    $items['my-reviews'] = __('My Reviews', 'your-text-domain');
    return $items;
}
add_filter('woocommerce_account_menu_items', 'add_custom_reviews_link_my_account');

// Content for the custom reviews endpoint
add_action('woocommerce_account_my-reviews_endpoint', 'display_my_reviews_content');
function display_my_reviews_content() {
    // Ensure the user is logged in
    if (!is_user_logged_in()) {
        echo '<div class="woocommerce-info">You need to be logged in to view your reviews.</div>';
        return;
    }

    global $wpdb; // Access the database

    // Set the number of reviews to display per page
    $reviews_per_page = 10;
    $current_page = get_query_var('paged', 1);
    $user_id = get_current_user_id();

    // Calculate the offset for pagination
    $offset = ($current_page - 1) * $reviews_per_page;

    // Fetch comments
    $query = $wpdb->prepare("
        SELECT * FROM {$wpdb->comments}
        WHERE user_id = %d
        AND comment_approved = '1'
        AND comment_post_ID IN (
            SELECT ID FROM {$wpdb->posts}
            WHERE post_type = 'product'
            AND post_status = 'publish'
        )
        ORDER BY comment_date DESC
        LIMIT %d, %d
    ", $user_id, $offset, $reviews_per_page);

    $comments = $wpdb->get_results($query);

    // Display reviews if found
    if (!empty($comments)) {
        echo '<table class="shop_table my_account_orders">
        <thead>
            <tr>
                <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-number"><span class="nobr">Date</span></th>
                <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-items"><span class="nobr">Product</span></th>
                <th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions"><span class="nobr">Review</span></th>
            </tr>
        </thead>
        <tbody>';

        foreach ($comments as $comment) {
            $comment_date = new DateTime($comment->comment_date);
            $product_id = $comment->comment_post_ID;
            $product_link = esc_url(get_permalink($product_id));
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'single-post-thumbnail');
            $rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));

            echo '<tr>
                <td data-title="Date">' . esc_html($comment_date->format('F j, Y')) . '</td>
                <td data-title="Product">
                    <a href="' . $product_link . '">
                        <img src="' . esc_url($image[0] ?? '') . '" width="50" height="50" alt="' . esc_attr(get_the_title($product_id)) . '"><br>' . esc_html(get_the_title($product_id)) . '
                    </a>
                </td>
                <td data-title="Review">' . wp_kses_post($comment->comment_content) . '<br>' . wc_get_rating_html($rating) . '</td>
            </tr>';
        }

        echo '</tbody>
        </table>';
    } else {
        echo '<div class="woocommerce-info">No reviews found.</div>';
    }

    // Pagination
    $total_comments = $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*) FROM {$wpdb->comments}
        WHERE user_id = %d AND comment_approved = '1'
        AND comment_post_ID IN (
            SELECT ID FROM {$wpdb->posts}
            WHERE post_type = 'product'
            AND post_status = 'publish'
        )
    ", $user_id));

    if ($total_comments > $reviews_per_page) {
        echo paginate_links([
            'base' => get_pagenum_link(1) . '%_%',
            'format' => 'page/%#%',
            'current' => max(1, $current_page),
            'total' => ceil($total_comments / $reviews_per_page),
        ]);
    }
}
