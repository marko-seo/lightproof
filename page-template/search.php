<?php
/*
 * Template name: Страница поиска
 */
?>

<?php get_header(); ?>

<main class="page-search container">
	
	<?php while (have_posts()) : the_post();
		the_title('<h1 class="entry-title">', '</h1>'); ?>

		<form role="search" method="get" id="searchform" action="">
			<label class="screen-reader-text" for="ss">Поиск: </label>
			<input type="text" value="<?php echo get_search_query() ?>" name="ss" id="ss" />
			<input type="submit" id="searchsubmit" value="найти" />
		</form>

	<?php the_content();
	endwhile; // End of the loop. 
	?>

	<br>

	<?php if (isset($_GET["ss"])) : ?>

		<?php

		$the_query = new WP_Query(['s' => $_GET["ss"]]);

		if ($the_query->have_posts()) { ?>
			<ul>
				<?php while ($the_query->have_posts()) {
					$the_query->the_post(); ?>
					<li>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</li>
				<?php } ?>
			</ul>
		<? } else { ?>
			<div class="alert alert-info">
				<p>По этому запросу ничего не найдено !</p>
			</div>
		<?php } ?>

	<?php endif; ?>

</main>

<?php get_footer(); ?>