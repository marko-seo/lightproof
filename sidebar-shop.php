<?php if ($attrs = get_attributes_products()) : ?>

    <div class="col-lg-3 col-md-4">
        <aside class="lp-sitebar lp-catalog__lp-sitebar" id="filters">
<!--            --><?php //dynamic_sidebar('filter') ?>

            <?php foreach ($attrs as $tax => $attr) : ?>
                <details class="lp-filter lp-sitebar__lp-filter" open>
                    <summary class="lp-filter__name"><?= $attr['name'] ?></summary>
                    <?php if ($tax == 'pa_color') : ?>
                        <ul class="lp-colors">
                            <?php foreach ($attr['options'] as $option) : ?>
                                <li class="lp-colors__item">
                                    <label class="lp-color-box" style="background-color: <?= $option['slug'] ?>">
                                        <input
                                                data-filter-link="<?= $option['link'] ?>"
                                                class="filter-control lp-color-box__input"
                                                type="checkbox"
                                            <?= isset($option['active']) ? 'checked' : ''; ?>
                                        >
                                        <span class="lp-color-box__check"></span>
                                        <span class="lp-color-box__tooltip"><?= $option['name'] ?></span>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <ul class="lp-filter__items <?= $tax == 'pa_width' || $tax == 'pa_height' ? 'lp-filter__items--grid' : '' ?>">
                            <?php foreach ($attr['options'] as $option) : ?>
                                <li class="lp-filter__item">
                                    <label class="lp-check">
                                        <input
                                                data-filter-link="<?= $option['link'] ?>"
                                                class="filter-control lp-check__input"
                                                type="checkbox"
                                            <?= isset($option['active']) ? 'checked' : ''; ?>
                                        >
                                        <span class="lp-check__box"></span>
                                        <span class="lp-check__caption"><?= $option['name'] ?></span>
                                    </label>
                                    <?php if ($tax == 'pa_lighttransmission') : ?>
                                        <span class="lp-icon lp-filter__lp-icon">
                                            <svg>
                                                <use href="<?= PATH_THEME ?>/images/dest/sprite.svg#icon-sun">
                                            </svg>
                                        </span>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </details>
            <?php endforeach; ?>

        </aside>
    </div>

<?php else : ?>

    <div class="col-lg-3 col-md-4"></div>

<?php endif; ?>