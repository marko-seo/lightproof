<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
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

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}

$product_published = $product->get_date_created();

$productJson = json_encode([
    'product' => $product->get_title(),
    'link' => $product->get_permalink(),
    'price' => $product->get_price()
]);

?>

<!-- Start single product -->
<div <?php wc_product_class('lp-products__col col-xl-3 col-sm-4 col-6', $product); ?>>
    <div class="lp-card lp-products__lp-card">
        <div class="lp-card__wrap-picture">
            <picture class="lp-card__picture">
                <?= $product->get_image() ?>
            </picture>
            <a class="lp-card__link-overlay" href="<?= $product->get_permalink() ?>"></a>
        </div>
        <div class="lp-card__main">
            <a class="lp-card__title" href="<?= $product->get_permalink() ?>"><?= $product->get_title() ?></a>
            <?php if ('' === $product->get_price() || 0 == $product->get_price()) : ?>
                <div class="lp-card__checkout-sect">
                    <div class="lp-card__btn lp-card__btn--in-cart">Цена по запросу !</div>
                </div>
            <?php else : ?>
                <div class="lp-card__checkout-sect">
                    <div class="lp-card__price">
                        <?= $product->get_price() ?>
                    </div>
                    <a href="<?= $product->add_to_cart_url() ?>" class="lp-card__btn lp-card__btn--in-cart">
                        <!-- В корзину-->
                        <?= $product->add_to_cart_text() ?>
                    </a>
                </div>
            <?php endif; ?>

        </div>
        <div class="lp-card__dropdown">
            <?php
            $handle = new WC_Product_Variable($product->get_id());
            ?>
            <?php if ($variations = $handle->get_children()) : ?>
                <p class="lp-card__dropdown-title">Другие варианты товара:</p>
                <div class="lp-card__options">
                    <?php foreach ($variations as $key => $value) : $variation = new WC_Product_Variation($value); ?>
                        <div class="lp-card__option <?= ((int) $key + 1) > 2 ? 'd-none' : ''; ?>">
                            <a class="lp-card__option-link" href="<?= $variation->get_permalink() ?>">
                                <?= $variation->get_image() ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                    <div class="lp-card__option lp-card__option--large">
                        <span href="#" class="lp-card__options-all">Больше</span>
                    </div>
                </div>
            <?php endif; ?>
            <button class="lp-card__boc" data-product="<?= htmlspecialchars($productJson) ?>">Купить в 1 клик</button>
        </div>
    </div>
</div>
<!-- End single product -->