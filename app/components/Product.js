export default {
	props: ['post'],
	template: `
		<div class="lp-products__col col-lg-3 col-md-3 col-sm-4 col-6">
			<div class="lp-card lp-products__lp-card">
				<div class="lp-card__wrap-picture">
					<picture class="lp-card__picture">
						<img :src="post.product.image" alt="">
					</picture>
					<a class="lp-card__link-overlay" :href="post.product.url"></a>
				</div>
				<div class="lp-card__main">
					<a class="lp-card__title" :href="post.product.url">{{ post.title }}</a>
					<div class="lp-card__checkout-sect">
						<div class="lp-card__price" v-html="post.product.price"></div>
						<a :href="post.product.add_to_cart" class="lp-card__btn lp-card__btn--in-cart">В корзину</a>
					</div>
				</div>
			</div>
		</div>
	`
}