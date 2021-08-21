<footer>
    <div class="footer-top">
        <div class="container">
            <div class="footer-info">
                <a href="/" class="footer-logo"><img src="<?= get_template_directory_uri() ?>/images/logo_gold.png"></a>
                <div class="footer-contacts">
                    <?php if ($address = get_field('gts_address', 'option')) : ?>
                        <div class="address">
                            <svg>
                                <use href="<?= get_template_directory_uri() ?>/images/icons-address.svg#icon"></use>
                            </svg>
                            <span><?= $address ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($timework = get_field('gts_timework', 'option')) : ?>
                        <div class="time">
                            <?= $timework ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="footer-phone">
                    <?php if ($telPrimary = get_field('gts_tel_primary', 'option')) : ?>
                        <div class="phone"><a href="tel:<?= $telPrimary ?>"><?= $telPrimary ?></a></div>
                    <?php endif; ?>
                    <?php if ($telSecondary = get_field('gts_tel_secondary', 'option')) : ?>
                        <div class="phone"><a href="tel:<?= $telSecondary ?>"><?= $telSecondary ?></a></div>
                    <?php endif; ?>
                    <div class="callback">
                        <svg>
                            <use href="<?= get_template_directory_uri() ?>/images/icons-phone.svg#icon"></use>
                        </svg>
                        <span class="btn-feedback">Заказать звонок</span>
                    </div>
                </div>
                <div class="social">
                    <a href="">
                        <svg>
                            <use href="<?= get_template_directory_uri() ?>/images/icons-social.svg#whatsapp"></use>
                        </svg>
                    </a>
                    <a href="">
                        <svg>
                            <use href="<?= get_template_directory_uri() ?>/images/icons-social.svg#telegram"></use>
                        </svg>
                    </a>
                    <a href="">
                        <svg>
                            <use href="<?= get_template_directory_uri() ?>/images/icons-social.svg#instagram"></use>
                        </svg>
                    </a>
                    <a href="">
                        <svg>
                            <use href="<?= get_template_directory_uri() ?>/images/icons-social.svg#facebook"></use>
                        </svg>
                    </a>
                </div>
                <?php if ($email = get_field('gts_email', 'option')) : ?>
                    <a href="mailto:<?= $email ?>" class="footer-email">
                        <svg>
                            <use href="<?= get_template_directory_uri() ?>/images/icons-email.svg#icon"></use>
                        </svg>
                        <span><?= $email ?></span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Footer menu -->
            <?php wp_nav_menu([
                'depth' => 2,
                'container' => false,
                'menu_id' => 'footer-nav',
                'menu_class' => 'footer-nav',
                'theme_location' => 'footer_menu',
                'items_wrap' => '<div id="%1$s" class="%2$s">%3$s</div>',
                'walker' => new Si_Footer_Menu(),
            ]) ?>
            <!-- End footer menu -->

        </div>
    </div>
    <?php if ($copyright = get_field('gts_copyright', 'option')) : ?>
        <div class="footer-bottom">
            <div class="container">
                <div class="copyright"><?= $copyright ?></div>
            </div>
        </div>
    <?php endif; ?>
</footer>

<div class="hide-element" id="hide-element">
    <div @click="onHandleClick" class="overlay" v-show="show">
        <transition enter-active-class="zoomIn" leave-active-class="zoomOut">
            <modal @handle-submit="onHandleSubmit" v-if="show" :info="info" :alert-info="alertInfo"></modal>
        </transition>
    </div>
</div>

</div>

<?php wp_footer() ?>
</body>

</html>