<?php

/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// echo '<pre>';print_r();
$attrs = $product->get_attributes();

?>

<?php woocommerce_breadcrumb(); ?>

<!-- CUSTOM PRODUCT -->
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

    <div class="si-product lp-product">
        <div class="container">
            <div class="row si-product__row">
                <div class="col-12 d-block d-lg-none">
                    <h4 class="si-product__title"><?= $product->get_title(); ?></h4>
                </div>
                <div class="col-lg-6">
                    <?php woocommerce_show_product_images(); ?>
                </div>
                <div class="col-lg-6">
                    <?php echo wc_get_stock_html($product); // WPCS: XSS ok.
                    ?>

                    <h4 class="si-product__title d-none d-lg-block">
                        <?= $product->get_title(); ?>
                    </h4>

                    <?php if ($product->is_in_stock() || $product->is_purchasable()) : ?>
                        <?php if ($product->get_type() == 'simple') : ?>
                            <form method="post" enctype='multipart/form-data' action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>">
                                <div class="si-product__custom-param">
                                    <div class="row">
                                        <div class="col-lg-5 order-2 order-lg-1">
                                            <?php woocommerce_quantity_input(
                                                array(
                                                    'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                                                    'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                                                    'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                                                )
                                            ); ?>
                                            <label class="si-opt si-product__si-opt">
                                                <span class="si-opt__caption">Ширина (см)</span>
                                                <div class="si-count-control">
                                                    <input name="custom-width" type="number" class="si-field si-field--hide-btn">
                                                </div>
                                            </label>
                                            <label class="si-opt si-product__si-opt">
                                                <span class="si-opt__caption">Высота (см)</span>
                                                <div class="si-count-control">
                                                    <input name="custom-height" type="number" class="si-field si-field--hide-btn">
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-lg-7 order-1 order-lg-2">
                                            <div class="si-price si-product__si-price">
                                                <div class="si-price__caption">Цена:</div>
                                                <div class="si-price__num"><?= $product->get_price() ?></div>
                                                <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button si-btn">
                                                    <?php echo esc_html($product->single_add_to_cart_text()); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php elseif ($product->get_type() == 'variable') : ?>
                            <p> Товар не доступен</p>
                            <?php //woocommerce_template_single_add_to_cart(); 
                            ?>
                        <?php else : ?>
                            <p> Товар не доступен</p>
                        <?php endif; ?>

                    <?php else : ?>
                        <p>Товара нет в наличие</p>
                    <?php endif; ?>

                </div>
            </div>
            <div class="row">

                <?php foreach ($attributes as $attribute_name => $options) : ?>
                    <label class="si-opt si-product__si-opt">
                        <span class="si-opt__caption"></span>
                        <div class="si-opt-group si-opt-group--medium">
                            <select name="<?= $attribute_name ?>" class="si-field si-select">
                                <?php foreach ($options as $option) : ?>
                                    <option value="<?= $option; ?>">
                                        <?= get_term_by('slug', $option, $attribute_name)->name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </label>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <!-- Tabs -->
    <?php woocommerce_output_product_data_tabs(); ?>

    <!-- Related products -->
    <?php woocommerce_output_related_products(); ?>


</div>