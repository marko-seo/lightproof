<?php get_header() ?>

<?php if (have_posts()) : ?>

    <div class="template template--page">

        <div class="container">
            
            <?php the_post() ?>

            <h1>
                <?php the_title() ?>
            </h1>

            <?php the_content() ?>

        </div>

    </div>

<?php endif ?>

<?php get_footer() ?>
