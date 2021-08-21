<?php if (!empty($slides = $args['slides'])) : ?>
    <section class="showcase">
        <div class="showcase-list">
            <?php foreach ($slides as $slide) : ?>
                <div class="showcase-list__item"
                     style="background-image: url('<?= $slide['image'] ?>')">
                    <div class="container">
                        <div class="showcase-list__title"><?= $slide['title'] ?></div>
                        <div class="showcase-list__text">
                            <?= $slide['description'] ?>
                        </div>
                        <div class="showcase-list__button">
                            <a href="<?= $slide['link'] ?>" class="btn btn--border">Подробнее</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

