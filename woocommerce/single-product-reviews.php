<?php

/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.3.0
 */

defined('ABSPATH') || exit;

global $product;

if (!comments_open()) {
	return;
}

$appraisals = ['ужасно', 'плохо', 'средне', 'хорошо', 'отлично'];

?>

<?php if (have_comments()) : ?>
	<div class="si-reviews">
		<?php wp_list_comments(apply_filters('woocommerce_product_review_list_args', array('callback' => 'woocommerce_comments'))); ?>
	</div>
<?php else : ?>
	<p class="woocommerce-noreviews"><?php esc_html_e('There are no reviews yet.', 'woocommerce'); ?></p>
<?php endif; ?>

<?php if (get_option('woocommerce_review_rating_verification_required') === 'no' || wc_customer_bought_product('', get_current_user_id(), $product->get_id())) : ?>
	<h4 class="si-tabs__title">Добавить отзыв</h4>
	<form class="si-form" action="<?php echo site_url('wp-comments-post.php') ?>" method="post">
		<div class="row">
			<div class="col-lg-6">
				<div class="row">
					<div class="col-lg-6">
						<label class="si-form-group">
							<span class="si-form__label">Ваше имя <b class="si-require">*</b></span>
							<input name="author" class="si-field si-form__si-field" type="text" placeholder="Иван Иванович" required>
						</label>
					</div>
					<div class="col-lg-6">
						<label class="si-form-group">
							<span class="si-form__label">E-mail</span>
							<input name="email" class="si-field si-form__si-field" type="email" placeholder="mail@gmail.com" required>
						</label>
					</div>
					<div class="col-12">
						<label class="si-form-group">
							<span class="si-form__label">Ваш отзыв <b class="si-require">*</b></span>
							<textarea name="comment" class="si-textarea si-field" placeholder="Текст отзыва" required></textarea>
						</label>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="si-form-group">
					<span class="si-form__label">Ваша оценка <b class="si-require">*</b></span>
					<ul class="si-list-radio">
						<?php foreach ($appraisals as $key => $appraisal) : ?>
							<li class="si-list-radio__item">
								<label class="si-radio">
									<input name="rating" value="<?= (int) $key + 1 ?>" class="si-radio__input" type="radio">
									<span class="si-radio__ellipse"></span>
									<span class="si-radio__caption"><?= $appraisal ?></span>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<div class="col-12">
				<button class="si-btn si-btn--large">Отправить</button>
				<?php
				comment_id_fields();
				do_action('comment_form', $product->get_id());
				?>
			</div>
		</div>
	</form>
<?php else : ?>
	<p class="woocommerce-verification-required"><?php esc_html_e('Only logged in customers who have purchased this product may leave a review.', 'woocommerce'); ?></p>
<?php endif; ?>