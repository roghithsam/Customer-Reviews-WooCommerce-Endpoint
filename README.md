# Customer Reviews WooCommerce Endpoint

## Description

The **Custom WooCommerce "My Reviews" Endpoint** is a piece of custom code that adds a new endpoint to the WooCommerce "My Account" page, where users can view and manage their reviews on purchased products. This custom solution provides a streamlined experience for customers to track their reviews, including product images, ratings, and pagination for easy navigation.

## Features

- Adds a "My Reviews" section to the WooCommerce "My Account" page.
- Displays product reviews with images, dates, and ratings.
- Pagination support to navigate through multiple reviews.
- Secure and optimized for better performance.

## Installation

To add this custom endpoint to your WooCommerce store, follow these steps:

1. Open your WordPress theme’s `functions.php` file, located in: /wp-content/themes/your-active-theme/functions.php
2. Copy and paste the customer-reviews-wooCommerce-endpoint.php code at the end of your `functions.php` file.
3. Save the functions.php file.
4. Flush the rewrite rules by going to Settings > Permalinks in your WordPress admin and simply clicking Save Changes.

## Usage

- After installation, users can access the "My Reviews" section from their WooCommerce account page.
- This section will display all the approved reviews they have made on published products.
- Pagination will automatically handle displaying multiple reviews, 10 per page by default.

## Customization

- To change the number of reviews displayed per page, modify the $reviews_per_page variable in the code.
- You can further style the review table using custom CSS by targeting the .shop_table and .woocommerce-orders-table__header classes.
