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
					<?php echo wc_get_stock_html($product); ?>

					<h4 class="si-product__title d-none d-lg-block">
						<?= $product->get_title(); ?>
					</h4>

					<?php if ($product->is_in_stock() || $product->is_purchasable()) : ?>
						<form id="lp-product__form-add-to-cart">
							<div class="si-product__custom-param">
								<div class="row">
									<div class="col-lg-5 order-2 order-lg-1">
										<label class="si-opt si-product__si-opt">
											<span class="si-opt__caption">Количество</span>
											<div class="si-count-control">
												<button type="button" class="si-count-control__btn si-count-control__btn--prev">–</button>
												<input type="number" class="si-field si-count-control__si-field" name="quantity" value="1">
												<button type="button" class="si-count-control__btn si-count-control__btn--next">+</button>
											</div>
										</label>
										<label class="si-opt si-product__si-opt">
											<span class="si-opt__caption">Ширина (см)</span>
											<div class="si-count-control">
												<input name="width" type="number" class="si-field si-field--hide-btn" min="100" value="100" required>
											</div>
										</label>
										<label class="si-opt si-product__si-opt">
											<span class="si-opt__caption">Высота (см)</span>
											<div class="si-count-control">
												<input name="height" type="number" class="si-field si-field--hide-btn" min="100" value="100" required>
											</div>
										</label>
									</div>
									<?php if ('' === $product->get_price() || 0 == $product->get_price()) : ?>
										<div class="col-lg-7 order-1 order-lg-2">
											<div class="btn btn--alert btn--alert-success">Цена по запросу !</div>
										</div>
									<?php else : ?>
										<div class="col-lg-7 order-1 order-lg-2">
											<div class="si-price si-product__si-price">
												<input type="hidden" name="lp_add_to_cart" value="<?= $product->get_id() ?>">
												<div class="si-price__caption">Цена:</div>
												<div class="si-price__num">
													<?= $product->get_price() ?>
												</div>
												<button type="submit" class="si-btn si-btn-add-to-cart">
													<?php echo esc_html($product->single_add_to_cart_text()); ?>
												</button>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</form>
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