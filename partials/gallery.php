<?php if (!empty($images = $args['images'])) : ?>
    <section class="section-works">
        <div class="container">
            <h2>Наши работы</h2>
            <div class="works__list wow fadeIn" data-wow-delay="0.2s">
                <?php foreach ($images as $image): ?>
                    <a class="works__list-item" href="<?= esc_url($image['url']); ?>" data-fancybox="works">
                        <img src="<?= esc_url($image['url']); ?>">
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="works__button">
                <a href="" class="btn">Смотреть все</a>
            </div>
        </div>
    </section>
<?php endif; ?>