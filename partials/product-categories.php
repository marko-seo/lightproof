<?php if (!empty($args)) : ?>
	<section class="section-cat">
		<div class="container">
			<div class="cat__wrap wow fadeInLeft" data-wow-delay="0.1s">
				<h2>Каталог</h2>
				<div class="cat__list">
					<?php foreach ($args as $key => $prod_cat) : ?>
						<div class="cat__list-item <?= $key == 1 ? 'active' : ''; ?>" data-cat="<?= $key ?>"><?= $prod_cat->name ?></div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="cat__images wow fadeInRight" data-wow-delay="0.1s">
				<?php foreach ($args as $key => $prod_cat) : ?>
					<?php
					if ($thumbnail_id = get_term_meta($prod_cat->term_id, 'thumbnail_id', true)) {
						$img = wp_get_attachment_url($thumbnail_id);
					} else {
						$img = wc_placeholder_img_src();
					}
					?>
					<a href="<?= get_term_link($prod_cat) ?>" class="cat__images-item <?= $key == 1 ? 'active' : ''; ?>" data-cat="<?= $key ?>">
						<img src="<?= $img ?>">
						<div class="cat__images-price">
							от <span><?= wpq_get_min_price_per_product_cat($prod_cat->term_id) ?> ₽</span>
						</div>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php else : ?>
	<div class="container">
		<p>Нет категорий !</p>
	</div>
<?php endif; ?>