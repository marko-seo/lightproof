<!DOCTYPE html>
<html <?php language_attributes() ?>>

<head>
    <title><?= wp_get_document_title() ?></title>
    <meta charset="<?php bloginfo('charset') ?>">

    <meta name="format-detection" content="telephone=no"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head() ?>
</head>

<body>

<div id="root" class="root <?= is_home() || is_product_taxonomy() || is_shop() ? '' : 'inner-pages'; ?>" data-theme="default">

    <header>
        <div class="container">
            <div class="nav-block">
                <div class="menu-toggle">
                    <svg>
                        <use href="<?= PATH_THEME ?>/images/icons-menu.svg#icon"></use>
                    </svg>
                    <span>Меню</span>
                </div>
                <div class="search-toggle">
                    <svg>
                        <use href="<?= PATH_THEME ?>/images/icons-search.svg#icon"></use>
                    </svg>
                    <span>Поиск</span>
                </div>
            </div>
            <a href="" class="logo">
                <img src="<?= PATH_THEME ?>/images/logo_full.png" class="logo-full">
                <img src="<?= PATH_THEME ?>/images/logo_mini_white.png" class="logo-full-white">
                <img src="<?= PATH_THEME ?>/images/logo_mini.png" class="logo-small">
            </a>
            <div class="user-block">
                <?php if ($telPrimary = get_field('gts_tel_primary', 'option')) : ?>
                    <div class="phone"><a href="tel:<?= $telPrimary ?>"><?= $telPrimary ?></a></div>
                <?php endif; ?>
                <!--div class="login-block">
                        <svg><use href="<?= PATH_THEME ?>/images/icons-user.svg#login"></use></svg>
                        <span>Войти</span>
                    </div-->
                <div class="login-block">
                    <svg>
                        <use href="<?= PATH_THEME ?>/images/icons-user.svg#user"></use>
                    </svg>
                    <span class="phone"><a href="/my-account/">
						Личный кабинет</a></span>
                </div>
            </div>
        </div>
    </header>

    <aside id="search" class="aside">
        <div class="aside__header">
            <div class="search-toggle">
                <svg>
                    <use href="<?= PATH_THEME ?>/images/icons-search.svg#icon"></use>
                </svg>
                <span>Поиск</span>
            </div>
            <div class="search-input">
                <svg>
                    <use href="<?= PATH_THEME ?>/images/icons-search.svg#icon"></use>
                </svg>
                <input v-model="searchLine" @input="onHandleInput" name="input-search" type="text" placeholder="Поиск">
                <!-- <div class="search__info">Найдено: 5 категорий, 600 товаров</div> -->
                <div class="search__info">
                    {{ searchResultInfo.message }}
                </div>
            </div>
            <div class="aside-close">
                <svg>
                    <use href="<?= PATH_THEME ?>/images/icons-close.svg#icon"></use>
                </svg>
                <span>Закрыть</span>
            </div>
        </div>
        <div class="aside__body">

            <!-- Main menu -->
            <?php wp_nav_menu([
                'depth' => 3,
                'container' => false,
                'menu_id' => 'aside__menu',
                'menu_class' => 'aside__menu',
                'theme_location' => 'head_menu',
                'items_wrap' => '<nav id="%1$s" class="%2$s">%3$s</nav>',
                'walker' => new Si_Main_Menu()
            ]) ?>
            <!-- End main menu -->

            <div class="aside__search">

                <!-- Preloader -->
                <preloader v-show="isLoading"></preloader>

                <div v-if="searchLine.length > 2" class="search__result">

                    <list-result 
						:posts="posts"
                        :search="searchLine"
						v-if="posts.length > 0"
					></list-result>

					<div v-else>По этому запросу ничего не найдено !</div>

                </div>

            </div>
        </div>
    </aside>


