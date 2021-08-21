<?php
if (!empty($args['product_cat']->childrens)) : ?>
	<section class="section-cat-curtains">
		<div class="container">
			<h2><?= $args['product_cat']->name ?></h2>
			<div class="cat-curtains">
				<?php foreach ($args['product_cat']->childrens as $key => $prod_cat) : ?>
					<?php
					if ($thumbnail_id = get_term_meta($prod_cat->term_id, 'thumbnail_id', true)) {
						$img = wp_get_attachment_url($thumbnail_id);
					} else {
						$img = wc_placeholder_img_src();
					}
					?>
					<div class="cat-curtains__item wow fadeInRight" data-wow-delay="0.<?= $key + 1 ?>s">
						<a href="<?= get_term_link($prod_cat) ?>" class="cat-curtains__link">
							<div class="cat-curtains__image"><img src="<?= $img ?>"></div>
							<div class="cat-curtains__title"><?= $prod_cat->name ?></div>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>