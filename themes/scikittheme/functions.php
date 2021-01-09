<?php
    require get_template_directory() . '/views/view1.php';

    /*init actions and filters*/
    add_action('wp_enqueue_scripts','style_theme');
    add_action('wp_footer','scripts_theme');
    add_action( 'after_setup_theme', 'theme_register_nav_menu' );
    add_filter( 'nav_menu_css_class', 'filter_add_my_class_to_nav_menu', 10, 2 );
    add_filter( 'nav_menu_link_attributes', 'filter_nav_menu_link_attributes', 10, 1 );    
    add_action( 'widgets_init', 'register_my_widgets' );
    add_action( 'pre_get_posts', 'wpse10691_alter_query' ); //сортировка постов перед выводом
    add_filter('widget_title', 'widgettitle_off');  //фильтр для того чтобы убрать заголовок из виджета

    /*http://wordsmall.ru/sajtostroenie/kak-ubrat-lishnie-tegi-br-i-p-v-wordpress.html*/
    remove_filter( 'the_content', 'wpautop' );// для контента
    remove_filter( 'the_excerpt', 'wpautop' );// для анонсов
    remove_filter( 'comment_text', 'wpautop' );// для комментарий

    function widgettitle_off( $title ) {
        if (empty($title)) return '';
        if ($title[0] == '!') return '';
        return $title;
    }
        
    
    /*
        сортировка постов перед выводом
        https://wordpress.stackexchange.com/questions/49940/how-to-order-posts-by-modified-date-without-using-query-posts
    */
    function wpse10691_alter_query( $query )
    {
        //if ( $query->is_main_query() && ( $query->is_home() || $query->is_search() || $query->is_archive() )  )
        //{
            $query->set( 'orderby', 'create' );
            $query->set( 'order', 'asc' );
        //}
    }

    function theme_register_nav_menu() {
        register_nav_menu( 'primary', 'Primary Menu' );
        add_post_type_support( 'page', 'excerpt' );
        add_post_type_support( 'post', 'post_excerpt' );
    }


    function style_theme(){
        wp_enqueue_style('style',get_stylesheet_uri());

        wp_enqueue_style('bootstrap','https://scikit-learn.org/stable/_static/css/vendor/bootstrap.min.css');
        wp_enqueue_style('gallery','https://scikit-learn.org/stable/_static/gallery.css');

        wp_enqueue_style('gallery','https://scikit-learn.org/stable/_static/gallery.css');
        wp_enqueue_style('gallery-binder','https://scikit-learn.org/stable/_static/gallery-binder.css');
        wp_enqueue_style('gallery-dataframe','https://scikit-learn.org/stable/_static/gallery-dataframe.css');
        wp_enqueue_style('theme','https://scikit-learn.org/stable/_static/css/theme.css');
        
      
    }
    function scripts_theme(){
        wp_enqueue_script('documentation_options','https://scikit-learn.org/stable/_static/documentation_options.js');
        wp_enqueue_script('jquery');//,'https://scikit-learn.org/stable/_static/jquery.js');
        wp_enqueue_script('bootstrap','https://scikit-learn.org/stable/_static/js/vendor/bootstrap.min.js',['jquery']);
        wp_enqueue_script('versionwarning','https://scikit-learn.org/versionwarning.js');        
    }


    
    function filter_add_my_class_to_nav_menu($classes, $item){
        // if( is_single() && $item->title == "Blog" ){
        //     $classes[] = "special-class";
        // }
        $classes = ['nav-item'];
        return $classes;
    }
    function filter_nav_menu_link_attributes($atts){
        $atts['class'] = 'sk-nav-link nav-link';
        return $atts;
    }

    function register_my_widgets(){

        register_sidebar( array(
            'name'          => 'Left sk sidebar toc',
            'id'            => "sk-sidebar-toc",
            // ,'description'   => '',
            // 'class'         => '',
             'before_widget' => '<div id="%1$s"  class="sk-sidebar-toc %2$s">',
             'after_widget'  => "</div>\n",
            // 'before_title'  => '<h2 class="widgettitle">',
            // 'after_title'   => "</h2>\n",
            // 'before_sidebar' => '', // WP 5.6
            // 'after_sidebar'  => '', // WP 5.6
        ) );
    }