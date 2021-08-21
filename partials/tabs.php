<?php if (!empty($tabs = $args['tabs'])) : ?>
    <section class="section-about">
        <div class="container">
            <div class="about__tab">
                <?php foreach ($tabs as $key => $tab) : ?>
                    <div class="about__tab-item <?= $key + 1 == '1' ? 'active' : '' ?> wow fadeInUp"
                         data-wow-delay="0.<?= $key + 1 ?>s" data-tab="<?= $key ?>">
                        <?= $tab['caption'] ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="about__content wow fadeInUp" data-wow-delay="0.1s">
                <?php foreach ($tabs as $key => $tab) : ?>
                    <div class="about__content-item <?= $key + 1 == '1' ? 'active' : '' ?>"
                         data-tab="<?= $key ?>">
                        <?= $tab['content'] ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>