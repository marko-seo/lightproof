<?php

define('PATH_THEME', get_template_directory_uri());

// Disable admin panel
add_filter('show_admin_bar', '__return_false');

// Require styles and scripts

function lp_js_and_css()
{
    // Css
    wp_enqueue_style('main', get_stylesheet_directory_uri() . '/css/styles.css', [], null);
    wp_enqueue_style('catalog', get_stylesheet_directory_uri() . '/css/catalog.css', [], null);
    wp_enqueue_style('product', get_stylesheet_directory_uri() . '/css/product.css', [], null);

    // Js
    wp_deregister_script('jquery');
    // wp_register_script('jquery', site_url().'/wp-includes/js/jquery/jquery.js', [], null, true);
    wp_register_script('jquery', get_stylesheet_directory_uri() . '/js/jquery.min.js', [], null, true);
    wp_enqueue_script('jquery');

    wp_enqueue_script('fancybox', get_stylesheet_directory_uri() . '/js/fancybox.min.js', ['jquery'], null, true);
    wp_enqueue_script('slick', get_stylesheet_directory_uri() . '/js/slick.min.js', ['jquery'], null, true);
    wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/libs/swiper/swiper.min.js', [], null, true);
    wp_enqueue_script('wow', get_stylesheet_directory_uri() . '/js/wow.min.js', ['jquery'], null, true);
    wp_enqueue_script('script', get_stylesheet_directory_uri() . '/js/script.js', ['jquery'], null, true);

    /* === App === */
    wp_enqueue_script('vue', get_stylesheet_directory_uri() . '/libs/vue/dist/vue.global.js', [], null, false);
    // wp_enqueue_script('vue', get_stylesheet_directory_uri() . '/libs/dist/vue.global.prod.js', [], null, false);
    wp_enqueue_script('app', get_stylesheet_directory_uri() . '/app/index.js', ['vue'], null, true);
    wp_script_add_data('app', 'module', true);

    // For ajax
    wp_localize_script('app', 'wpRoute', [
        'url' => admin_url('admin-ajax.php'),
    ]);

    /* === Deregister WC === */
    if (!is_cart() && !is_checkout()) {
        wp_deregister_style('woocommerce-general');
        wp_deregister_style('woocommerce-layout');
    }

    //  else {
    //     wp_enqueue_style( 'twenty-one-1', get_template_directory_uri() . '/../twentytwentyone/style.css' );
    //     wp_enqueue_style( 'twenty-one-2', get_template_directory_uri() . '/../twentytwentyone/assets/css/print.css', array(), wp_get_theme()->get( 'Version' ), 'print' );
    // }
}
add_action('wp_enqueue_scripts', 'lp_js_and_css');

function get_product_callback()
{
    $data = [];

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $product = wc_get_product($_GET['id']);

        $data = [
            'price' => $product->get_price(),
            'url' => $product->get_permalink(),
            'add_to_cart' => $product->add_to_cart_url(),
            'image' => get_the_post_thumbnail_url($product->get_id()),
        ];
    } else {
        $data['error'] = true;
    }

    echo json_encode($data);

    wp_die();
}

function add_to_cart_callback()
{
    $data = [];

    if (($product = wc_get_product($_POST['lp_add_to_cart']))) {

        if (
            isset($_POST['width']) && !empty($_POST['width']) &&
            isset($_POST['height']) && !empty($_POST['height'])
        ) {
            $product_meta = [];
            $price = (int) trim(str_replace(get_woocommerce_currency_symbol(), '', $product->get_price()));
            $quantity = $_POST['quantity'] ? (int) $_POST['quantity'] : 1;
            $new_price = ((int) $_POST['width'] * (int) $_POST['height'] / 10000) * $price;
            
            $product_meta['custom_data'] = [
                'new_price' => $new_price,
                'width' =>  $_POST['width'],
                'height' => $_POST['height']
            ];

            if (WC()->cart->add_to_cart($product->get_id(), $quantity, 0, [], $product_meta)) {
                $data['new_price'] = ($new_price * $quantity). ' ' . get_woocommerce_currency_symbol();
                $data['message'] = 'Товар добавлен в корзину !';
            } else {
                $data['message'] = 'Произошла ошибка свяжитесь с менеджером!';
                $data['error'] = true;
            }
        } else {
            $data['message'] = 'Заполните размеры !';
            $data['error'] = true;
        }
    } else {
        $data['message'] = 'Произошла ошибка свяжитесь с менеджером!';
        $data['error'] = true;
    }

    echo json_encode($data);

    wp_die();
}

if (wp_doing_ajax()) {
    // action get product
    add_action('wp_ajax_get_product', 'get_product_callback');
    add_action('wp_ajax_nopriv_get_product', 'get_product_callback');
    // Custom add to cart
    add_action('wp_ajax_add_to_cart', 'add_to_cart_callback');
    add_action('wp_ajax_nopriv_add_to_cart', 'add_to_cart_callback');
}

function custom_cart_item_price($cart)
{
    if (is_admin() && !defined('DOING_AJAX'))
        return;

    if (did_action('woocommerce_before_calculate_totals') >= 2)
        return;

    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['custom_data']['new_price'])) {
            $cart_item['data']->set_price($cart_item['custom_data']['new_price']);
        }
    }
}
add_action('woocommerce_before_calculate_totals', 'custom_cart_item_price', 30, 1);

function iconic_add_engraving_text_to_order_items( $item, $cart_item_key, $values, $order ) {
    if ( empty( $values['custom_data'] ) ) {
                    return;
    }

    $item->add_meta_data("Ширина", $values['custom_data']['width'] );
    $item->add_meta_data("Высота", $values['custom_data']['height'] );
}
add_action( 'woocommerce_checkout_create_order_line_item', 'iconic_add_engraving_text_to_order_items', 10, 4 );

// function custome_add_to_cart() {
    // echo '<pre>';print_r($_SERVER['REDIRECT_URL']);die;
//     wp_redirect($_SERVER['REDIRECT_URL'], 301);
//     die;
// }
// add_action('woocommerce_add_to_cart', 'custome_add_to_cart');
function filter_function_name_2593( $url, $adding_to_cart ){
	// filter...
    // echo '<pre>123';print_r($url);die;
    $url = $_SERVER['REDIRECT_URL'];

	return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'filter_function_name_2593', 10, 2 );

// Add gallery
function woocommerce_support()
{
    //    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'woocommerce_support');

// Add attr module
add_filter('script_loader_tag', 'my_script_loader_tag', 10, 2);
function my_script_loader_tag($tag, $handle)
{
    if (wp_scripts()->get_data($handle, 'module')) {
        $tag = str_replace("text/javascript", "module", $tag);
    }

    return $tag;
}

// Register menu
register_nav_menus([
    'head_menu' => 'Main menu',
    'footer_menu' => 'Footer menu',
]);

// Custom header menu
class Si_Main_Menu extends Walker_Nav_Menu
{
    private $arrowRight = "<svg><use href='" . PATH_THEME . "/images/icons-arrow.svg#menu'></use></svg>";
    private $arrowDown = "<svg><use href='" . PATH_THEME . "/images/icons-arrow.svg#sub'></use></svg>";

    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);

        // Default class.
        if ($depth < 1) {
            $classes = array('menu__sub');
        } else {
            $classes = array('menu__sub-item');
        }

        /**
         * Filters the CSS class(es) applied to a menu list element.
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
         * @param stdClass $args An object of `wp_nav_menu()` arguments.
         * @param int $depth Depth of menu item. Used for padding.
         * @since 4.8.0
         *
         */
        $class_names = implode(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= "{$n}{$indent}<ul$class_names>{$n}";
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';

        $classes = ['menu__item'];

        if (count($item->classes) > 3) {
            foreach ($item->classes as $class) {
                if (!empty($clas) || strpos($class, 'menu-item') === false) {
                    $classes[] = $class;
                }
            }
        }

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param WP_Post $item Menu item data object.
         * @param int $depth Depth of menu item. Used for padding.
         * @since 4.4.0
         *
         */
        $args = apply_filters('nav_menu_item_args', $args, $item, $depth);

        /**
         * Filters the CSS classes applied to a menu item's list item element.
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
         * @param WP_Post $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item. Used for padding.
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         */
        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item. Used for padding.
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         */
        $id = apply_filters('nav_menu_item_id', '', $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        if ($depth > 0) {
            $output .= $indent . '<li>';
        } else {
            $output .= $indent . '<div' . $id . $class_names . '>';
        }

        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        if ('_blank' === $item->target && empty($item->xfn)) {
            $atts['rel'] = 'noopener';
        } else {
            $atts['rel'] = $item->xfn;
        }
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts['aria-current'] = $item->current ? 'page' : '';

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         * @type string $title Title attribute.
         * @type string $target Target attribute.
         * @type string $rel The rel attribute.
         * @type string $href The href attribute.
         * @type string $aria_current The aria-current attribute.
         * }
         * @param WP_Post $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item. Used for padding.
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         */
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (is_scalar($value) && '' !== $value && false !== $value) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters('the_title', $item->title, $item->ID);

        /**
         * Filters a menu item's title.
         *
         * @param string $title The menu item's title.
         * @param WP_Post $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item. Used for padding.
         * @since 4.4.0
         *
         */
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        if ($depth > 0 && $this->has_children) {
            $item_output = $args->before;
            $item_output .= '<div class="menu__sub-toggle"><a' . $attributes . '>';
            $item_output .= $args->link_before . $title . $args->link_after;
            $item_output .= '</a>' . $this->arrowDown . '</div>';
            $item_output .= $args->after;
        } else {
            $item_output = $args->before;
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . $title . $args->link_after;
            $item_output .= $this->has_children ? $this->arrowRight . '</a>' : '</a>';
            $item_output .= $args->after;
        }

        /**
         * Filters a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param WP_Post $item Menu item data object.
         * @param int $depth Depth of menu item. Used for padding.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @since 3.0.0
         *
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }

        if ($depth > 0) {
            $output .= "</li>{$n}";
        } else {
            $output .= "</div>{$n}";
        }
    }
}

// Custom, footer
class Si_Footer_Menu extends Walker_Nav_Menu
{
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);

        // Default class.
        $classes = array('footer-menu');

        /**
         * Filters the CSS class(es) applied to a menu list element.
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
         * @param stdClass $args An object of `wp_nav_menu()` arguments.
         * @param int $depth Depth of menu item. Used for padding.
         * @since 4.8.0
         *
         */
        $class_names = implode(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= "{$n}{$indent}<ul$class_names>{$n}";
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';

        if ($depth == 0) {
            $classes[] = 'footer-nav__item';
        }

        if (count($item->classes) > 3) {
            foreach ($item->classes as $class) {
                if (!empty($clas) || strpos($class, 'menu-item') === false) {
                    $classes[] = $class;
                }
            }
        }

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param WP_Post $item Menu item data object.
         * @param int $depth Depth of menu item. Used for padding.
         * @since 4.4.0
         *
         */
        $args = apply_filters('nav_menu_item_args', $args, $item, $depth);

        /**
         * Filters the CSS classes applied to a menu item's list item element.
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
         * @param WP_Post $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item. Used for padding.
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         */
        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item. Used for padding.
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         */
        $id = apply_filters('nav_menu_item_id', '', $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        if ($depth > 0) {
            $output .= $indent . '<li' . $id . $class_names . '>';
        } else {
            $output .= $indent . '<div' . $id . $class_names . '>';
        }

        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        if ('_blank' === $item->target && empty($item->xfn)) {
            $atts['rel'] = 'noopener';
        } else {
            $atts['rel'] = $item->xfn;
        }
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts['aria-current'] = $item->current ? 'page' : '';

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         * @type string $title Title attribute.
         * @type string $target Target attribute.
         * @type string $rel The rel attribute.
         * @type string $href The href attribute.
         * @type string $aria_current The aria-current attribute.
         * }
         * @param WP_Post $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item. Used for padding.
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         */
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (is_scalar($value) && '' !== $value && false !== $value) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters('the_title', $item->title, $item->ID);

        /**
         * Filters a menu item's title.
         *
         * @param string $title The menu item's title.
         * @param WP_Post $item The current menu item.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @param int $depth Depth of menu item. Used for padding.
         * @since 4.4.0
         *
         */
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $item_output = $args->before;
        if ($depth > 0) {
            $item_output .= '<a' . $attributes . '>';
            $item_output .= $args->link_before . $title . $args->link_after;
            $item_output .= '</a>';
        } else {
            $item_output .= '<div class="footer-nav__title">';
            $item_output .= $args->link_before . $title . $args->link_after;
            $item_output .= '</div>';
        }
        $item_output .= $args->after;

        /**
         * Filters a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param WP_Post $item Menu item data object.
         * @param int $depth Depth of menu item. Used for padding.
         * @param stdClass $args An object of wp_nav_menu() arguments.
         * @since 3.0.0
         *
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }

        if ($depth > 0) {
            $output .= "</li>{$n}";
        } else {
            $output .= "</div>{$n}";
        }
    }
}

// Page setting
if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => 'Настройки',
        'menu_title' => 'Настройки темы',
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ]);
}
// Register post type component
add_action('init', 'register_component_types');
function register_component_types()
{
    register_post_type('component', [
        'labels' => [
            'name' => 'Компоненты', // Основное название типа записи
            'singular_name' => 'Компонент', // отдельное название записи типа Book
            'add_new' => 'Добавить компонент',
            'add_new_item' => 'Добавить новый компонент',
            'edit_item' => 'Редактировать компонент',
            'new_item' => 'Новый компонент',
            'view_item' => 'Посмотреть компонент',
            'search_items' => 'Найти компонент',
            'not_found' => 'Компонентов не найдено',
            'not_found_in_trash' => 'В корзине не найдено компонентов',
            'parent_item_colon' => '',
            'menu_name' => 'Компоненты'
        ],
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-block-default',
        'query_var' => false,
        'rewrite' => false,
        'has_archive' => false,
        'hierarchical' => false,
        'supports' => ['title', 'editor', 'custom-fields']
    ]);
}

// Add custom columns
function true_add_post_columns($my_columns)
{
    $my_columns['shortcode'] = 'Шорткод';
    return $my_columns;
}

add_filter('manage_edit-component_columns', 'true_add_post_columns', 10, 1);

function true_fill_post_columns($column)
{
    global $post;
    switch ($column) {
        case 'shortcode':
            echo '<input type="text" onfocus="this.select()" value="[component id=&quot;' . $post->ID . '&quot;]" readonly />';
            break;
    }
}

add_action('manage_posts_custom_column', 'true_fill_post_columns', 10, 1);

// Settings components
add_filter('post_updated_messages', 'component_updated_messages');
function component_updated_messages($messages)
{
    global $post;

    $messages['component'] = array(
        0 => '', // Не используется. Сообщения используются с индекса 1.
        1 => sprintf('Компонент обновлен. <a href="%s">Посмотреть Компонент</a>', esc_url(get_permalink($post->ID))),
        2 => 'Произвольное поле обновлено.',
        3 => 'Произвольное поле удалено.',
        4 => 'Запись component обновлена.',
        /* %s: дата и время ревизии */
        5 => isset($_GET['revision']) ? sprintf('Запись component восстановлена из ревизии %s', wp_post_revision_title((int)$_GET['revision'], false)) : false,
        6 => sprintf('Компонент опубликован.'),
        7 => 'Компонент сохранен.',
        8 => sprintf('Компонент сохранен.'),
        9 => sprintf(
            'Запись component запланирована на: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Предпросмотр записи component</a>',
            // Как форматировать даты в PHP можно посмотреть тут: http://php.net/date
            date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)),
            esc_url(get_permalink($post->ID))
        ),
        10 => sprintf('Черновик записи component обновлен. <a target="_blank" href="%s">Предпросмотр записи component</a>', esc_url(add_query_arg('preview', 'true', get_permalink($post->ID)))),
    );

    return $messages;
}

if (function_exists('acf_add_local_field_group')) {

    // Основные настройки
    acf_add_local_field_group([
        'key' => 'group_theme_setting',
        'title' => 'Редактирование',
        'fields' => [
            [
                'key' => 'field_gts_tab_main',
                'name' => 'gts_tab_main',
                'label' => 'Основное',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'placement' => 'top',
                'endpoint' => 0,
            ],
            [
                'key' => 'field_gts_tel_primary',
                'name' => 'gts_tel_primary',
                'label' => 'Телефон',
                'type' => 'text',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
            ],
            [
                'key' => 'field_gts_tel_secondary',
                'name' => 'gts_tel_secondary',
                'label' => 'Телефон',
                'type' => 'text',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
            ],
            [
                'key' => 'field_gts_email',
                'name' => 'gts_email',
                'label' => 'Email',
                'type' => 'email',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
            ],
            [
                'key' => 'field_gts_address',
                'name' => 'gts_address',
                'label' => 'Адрес',
                'type' => 'text',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
            ],
            [
                'key' => 'field_gts_timework',
                'name' => 'gts_timework',
                'label' => 'Режим работы',
                'type' => 'text',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
            ],
            [
                'key' => 'field_gts_copyright',
                'name' => 'gts_copyright',
                'label' => 'Копирайт',
                'type' => 'text',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'theme-general-settings'
                ]
            ]
        ],
    ]);

    // Доп. поля для табов
    acf_add_local_field_group([
        'key' => 'group_tabs',
        'title' => 'Доп. поля',
        'fields' => [
            [
                'key' => 'field_gt_repeater',
                'name' => 'gt_repeater',
                'label' => 'Табы',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'block',
                'button_label' => '',
                'sub_fields' => [
                    [
                        'key' => 'field_gt_repeater_tab',
                        'label' => 'Вкладка',
                        'name' => 'gt_repeater_tab',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_gt_repeater_content',
                        'label' => 'Содержимое',
                        'name' => 'gb_tabs_content',
                        'type' => 'wysiwyg',
                        'default_value' => '',
                        'tabs' => 'all',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                        'delay' => 0,
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post',
                    'operator' => '==',
                    'value' => '9'
                ]
            ]
        ],
    ]);

    // Доп. поля для наших работ
    acf_add_local_field_group([
        'key' => 'group_сomponent_10',
        'title' => 'Доп. поля',
        'fields' => [
            [
                'key' => 'field_gp10_gallery',
                'name' => 'gp10_gallery',
                'label' => 'Изображения',
                'type' => 'gallery',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => [
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ],
                'insert' => 'append',
                'library' => 'all',
            ]
        ],
        'location' => [
            [
                [
                    'param' => 'post',
                    'operator' => '==',
                    'value' => '10'
                ]
            ]
        ],
    ]);

    // Доп. поля для банера
    acf_add_local_field_group([
        'key' => 'group_сomponent_18',
        'title' => 'Доп. поля',
        'fields' => [
            [
                'key' => 'field_gp18_banner',
                'name' => 'gp18_banner',
                'label' => 'Слайды',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'block',
                'button_label' => '',
                'sub_fields' => [
                    [
                        'key' => 'field_gp18_banner_img',
                        'label' => 'Изображение',
                        'name' => 'gp18_banner_img',
                        'type' => 'image',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'return_format' => 'url',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                    ],
                    [
                        'key' => 'field_gp18_banner_title',
                        'label' => 'Заголовок',
                        'name' => 'gp18_banner_title',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_gp18_banner_desc',
                        'label' => 'Описание',
                        'name' => 'gp18_banner_desc',
                        'type' => 'textarea',
                        'rows' => 3
                    ],
                    [
                        'key' => 'field_gp18_banner_link',
                        'label' => 'Ссылка',
                        'name' => 'gp18_banner_link',
                        'type' => 'link',
                    ],
                ],
            ]
        ],
        'location' => [
            [
                [
                    'param' => 'post',
                    'operator' => '==',
                    'value' => '18'
                ]
            ]
        ],
    ]);
}

function component_render($atts)
{
    $data = [];
    $template = null;

    switch ($atts['id']) {
        case '9':
            $data['tabs'] = [];
            if (have_rows('field_gt_repeater', $atts['id'])) {
                while (have_rows('field_gt_repeater', $atts['id'])) {
                    the_row();
                    $data['tabs'][] = [
                        'caption' => get_sub_field('gt_repeater_tab'),
                        'content' => get_sub_field('gb_tabs_content'),
                    ];
                }
            }
            $template = 'partials/tabs';
            break;
        case '18':
            $data['slides'] = [];
            if (have_rows('field_gp18_banner', $atts['id'])) {
                while (have_rows('field_gp18_banner', $atts['id'])) {
                    the_row();
                    $data['slides'][] = [
                        'image' => get_sub_field('gp18_banner_img'),
                        'title' => get_sub_field('gp18_banner_title'),
                        'description' => get_sub_field('gp18_banner_desc'),
                        'link' => get_sub_field('gp18_banner_link')
                    ];
                }
            }
            $template = 'partials/banner';
            break;
        case '10':
            $data['images'] = get_field('field_gp10_gallery', $atts['id']);
            $template = 'partials/gallery';
            break;
        default:
            return get_post_field('post_content', $atts['id']);
    }

    //    echo '<pre>';print_r($data);die;
    return get_template_part($template, null, $data);
}

add_shortcode('component', 'component_render');

add_theme_support('woocommerce');

function custom_breadcrumb()
{
    return [
        'delimeter' => '',
        'wrap_before' => '<nav class="lp-breadcrumb lp-cats__lp-breadcrumb"><div class="container"><ul class="lp-breadcrumb__list">',
        'wrap_after' => '</ul></div></nav>',
        'before' => '<li class="lp-breadcrumb__item">',
        'after' => '</li>',
        'home' => 'Главная'
    ];
}

add_filter('woocommerce_breadcrumb_defaults', 'custom_breadcrumb');

// Min price prod in category
function wpq_get_min_price_per_product_cat($term_id)
{
    global $wpdb;

    $sql = "
    SELECT MIN( meta_value+0 ) as minprice
    FROM {$wpdb->posts} 
    INNER JOIN {$wpdb->term_relationships} ON ({$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id)
    INNER JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id) 
    WHERE  
      ( {$wpdb->term_relationships}.term_taxonomy_id IN (%d) ) 
    AND {$wpdb->posts}.post_type = 'product' 
    AND {$wpdb->posts}.post_status = 'publish' 
    AND {$wpdb->postmeta}.meta_key = '_price'
  ";

    return $wpdb->get_var($wpdb->prepare($sql, $term_id));
}

function get_attributes_products()
{
    if (!is_shop() && !is_product_taxonomy()) {
        return;
    }

    global $wp_query;

    $data = [];

    $args = [
        'numberposts' => -1,
        'post_type' => 'product',
        'post_status' => 'publish',
        'suppress_filters' => true,
        'tax_query' => $wp_query->query_vars['tax_query'],
    ];

    if (is_product_taxonomy()) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $wp_query->query_vars['product_cat']
        ];
    }

    if ($posts = get_posts($args)) {
        foreach ($posts as $key => $post) {
            if ($product = wc_get_product($post)) {
                foreach ($product->get_attributes() as $taxonomy => $attribute) {
                    if ($terms = $attribute->get_terms()) {
                        $data[$taxonomy]['name'] =  wc_attribute_label($taxonomy);
                        $filter_name = "filter_" . str_replace('pa_', '', $taxonomy);
                        foreach ($terms as $term) {
                            $option = [
                                'id' => $term->term_id,
                                'name' => $term->name,
                                'slug' => $term->slug,
                            ];

                            if (isset($_GET[$filter_name]) && !empty($_GET[$filter_name])) {

                                $val = explode(',', $_GET[$filter_name]);

                                if (count($val) > 1) {
                                    if (($key = array_search($term->slug, $val)) !== false) {
                                        array_splice($val, $key, 1);
                                        $option['active'] = true;
                                        $link = esc_url(add_query_arg([$filter_name => implode(',', $val)]));
                                    } else {
                                        $val[] = $term->slug;
                                        $link = esc_url(add_query_arg([$filter_name => implode(',', $val)]));
                                    }
                                } else {
                                    if ($val[0] == $term->slug) {
                                        $option['active'] = true;
                                        $link = remove_query_arg($filter_name);
                                    } else {
                                        $val[] = $term->slug;
                                        $link = esc_url(add_query_arg([$filter_name => implode(',', $val)]));
                                    }
                                }
                            } else {
                                $link = esc_url(add_query_arg([$filter_name => $term->slug]));
                            }

                            if (is_paged() || strpos($_SERVER['REQUEST_URI'], '/page/1')) {
                                $link = preg_replace('/.page\/([0-9]{1,})/', '', $link);
                            }

                            $option['link'] = $link;

                            if (in_array($option, $data[$taxonomy]['options'])) {
                                continue;
                            }

                            $data[$taxonomy]['options'][] = $option;
                        }
                    }
                }
            }
        }
    }

    //    echo '<pre>';print_r($wp_query);die;
    return $data;
}

register_sidebar([
    'id' => 'filter',
    'name' => 'tmp'
]);

function change_product_price($price)
{
    $price .= ' ' . get_woocommerce_currency_symbol();
    return $price;
}
add_filter('woocommerce_get_price', 'change_product_price');

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);


/**
 * Edit product data tabs
 */
function get_content_delivery_and_buy()
{
    echo do_shortcode('[component id="106"]');
}

function get_content_warranty()
{
    echo do_shortcode('[component id="108"]');
}

function get_content_placeholder()
{
    echo "Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorem praesentium est quidem reprehenderit saepe. Sunt nihil iusto quos soluta possimus minus. At nisi veniam eaque ipsa ea excepturi nihil error.";
}

function woo_remove_product_tabs($tabs)
{

    unset($tabs['additional_information']);

    $tabs['reviews']['priority'] = 50;

    $tabs['delivery'] = array(
        'title'     => 'Доставка и оплата',
        'priority'     => 20,
        'callback'     => 'get_content_delivery_and_buy'
    );

    $tabs['warranty'] = array(
        'title'     => 'Гарантии и возврат',
        'priority'     => 30,
        'callback'     => 'get_content_warranty'
    );

    $tabs['measure'] = array(
        'title'     => 'Как измерить',
        'priority'     => 30,
        'callback'     => 'get_content_placeholder'
    );

    $tabs['installation'] = array(
        'title'     => 'Монтаж',
        'priority'     => 30,
        'callback'     => 'get_content_placeholder'
    );

    return $tabs;
}
add_filter('woocommerce_product_tabs', 'woo_remove_product_tabs', 98);


function product_categories_func($atts)
{
    $data = [];
    $template = '';

    if ($atts['id'] != '0') {
        $data['product_cat'] = get_term($atts['id'], 'product_cat');
        $data['product_cat']->childrens = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => $atts['id']]);
        $template = 'partials/product-categories-hierarchy';
    } else {
        $data = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => true]);
        $template = 'partials/product-categories';
    }

    return get_template_part($template, null, $data);
}
add_shortcode('lp_product_categories', 'product_categories_func');
