import Product from './Product.js';

export default {
	props: ['posts', 'search'],
	methods: {
		defineType(type) {
			let name = '';

			switch (type) {
				case 'page':
					name = 'Страницы';
					break;
				case 'product':
					name = 'Товары';
					break;
				case 'post':
					name = 'Статьи';
					break;
				default:
					break;
			}

			return name;
		}
	},
	components: {
		Product
	},
	computed: {
		splitPosts() {
			const data = [];
			const self = this;
			let index;

			for (const post of this.posts) {
				if (data.length == 0) {
					data.push({ 'name': self.defineType(post.subtype), 'type': post.subtype, 'posts': [post] });
				} else if ((index = data.findIndex(item => item.type == post.subtype)) !== -1) {
					data[index]['posts'].push(post);
				} else {
					data.push({ 'name': self.defineType(post.subtype), 'type': post.subtype, 'posts': [post] });
				}
			}

			if ((index = data.findIndex(item => item.type == 'product' && item.posts.length > 3)) !== -1) {
				data[index].posts = data[index].posts.slice(0, 4);
			}

			return data;
		}
	},
	template: `
		<div v-for="section in splitPosts">
			<div class="search__result-title">{{ section.name }}:</div>
			<div class="search__result-cat" v-if="section.type != 'product'">
				<div :key="post.id" v-for="post in section.posts" class="search__result-link">
					<a :href="post.url">{{ post.title }}</a>
				</div>
			</div>
			<div v-else class="search__result-products row">
				<product :key="post.id" v-for="(post, i) in section.posts" :post="post"></product>
			</div>
			<div class="search__result-more">
            	<a :href="'/search?ss='+search">
            	    <span>Показать все результаты</span>
            	    <svg><use href="/wp-content/themes/lightproof/images/icons-arrow.svg#menu"></use></svg>
            	</a>
            </div>
		</div>
	`
}