<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

?>

<?php get_header() ?>

<?= do_shortcode('[component id="18"]') ?>

<?= do_shortcode('[component id="16"]') ?>

<!-- Cats -->
<section class="lp-cats">
    <div class="container">
        <div class="lp-cats__head">
            <?php woocommerce_page_title(); ?>
        </div>
    </div>

    <?php woocommerce_breadcrumb() ?>

    <?php if ($prod_cats = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true])) : ?>
        <div class="lp-cats__list">
            <div class="container">
                <div class="row">
                    <?php foreach ($prod_cats as $prod_cat) : ?>
                        <div class="col-md-4 col-sm-6 col-12 lp-cats__col">
                            <div class="lp-cats__item"
                                <?php
                                if ($thumbnail_id = get_term_meta($prod_cat->term_id, 'thumbnail_id', true)) {
                                    $style = "style='background-image: url(".wp_get_attachment_url($thumbnail_id).")'";
                                } else {
                                    $style = "style='background-image: url(".wc_placeholder_img_src()."); background-blend-mode: darken;'";
                                }
                                echo $style;
                                ?>
                            >
                                <div class="lp-cats__title"><?= $prod_cat->name ?></div>
                                <div class="lp-cats__price">от <?= wpq_get_min_price_per_product_cat($prod_cat->term_id) ?> ₽</div>
                                <a class="lp-cats__link" href="<?= get_term_link($prod_cat) ?>"></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

</section>
<!-- End cats-->

<!-- Catalog -->
<div class="lp-catalog" id="catalog">
    <div class="container">
        <div class="row">

            <?php get_sidebar('shop') ?>

            <div class="col-lg-9 col-md-8">

                <?php if (woocommerce_product_loop()) : ?>

                    <?php woocommerce_output_all_notices() ?>

                    <div class="lp-products">

                        <?php woocommerce_catalog_ordering() ?>

                        <?php
                        woocommerce_product_loop_start();

                        if (wc_get_loop_prop('total')) {
                            while (have_posts()) {
                                the_post();

                                /**
                                 * Hook: woocommerce_shop_loop.
                                 */
                                do_action('woocommerce_shop_loop');

                                wc_get_template_part('content', 'product');
                            }
                        }

                        woocommerce_product_loop_end();
                        ?>

                    </div>

                    <?php woocommerce_pagination() ?>

                <?php else : ?>

                    <?php wc_no_products_found() ?>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<!-- End Catalog -->

<?= do_shortcode('[component id="10"]') ?>

<?= do_shortcode('[component id="9"]') ?>

<?php get_template_part('partials/order-form') ?>

<?php get_footer() ?>
