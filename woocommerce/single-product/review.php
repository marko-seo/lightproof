<?php

/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.6.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>

<blockquote class="si-reviews__review">
	<b class="si-reviews__name"><?= $comment->comment_author ?></b>
	<time class="si-reviews__date">
		<?= date("Y:m:d", strtotime($comment->comment_date)); ?>
	</time>
	<?php if ($rating = get_comment_meta($comment->comment_ID, 'rating', true)) : ?>
		<ul class="si-stars">
			<?php for ($i = 0; $i < $rating; $i++) : ?>
				<li class="si-stars__item"></li>
			<?php endfor; ?>
		</ul>
	<?php endif; ?>
	<p class="si-reviews__text">
		<?= $comment->comment_content ?>
	</p>
</blockquote>