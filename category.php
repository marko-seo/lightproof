<?php get_header() ?>

<?php if (have_posts()) : ?>

	<div class="template template--page">

		<div class="container">

			<?php while (have_posts()) : the_post() ?>

				<?php the_post() ?>

				<h4>
					<?php the_title() ?>
				</h4>

				<p>
					<?php the_content() ?>
				</p>

			<?php endwhile; ?>

		</div>

	</div>

<?php endif ?>

<?php get_footer() ?>