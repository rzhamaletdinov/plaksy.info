<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

class Warp_Breadcrumbs extends \WP_Widget
{
    public function Warp_Breadcrumbs()
    {
        $widget_ops = array('description' => 'Display your sites breadcrumb navigation');
        parent::WP_Widget(false, 'Warp - Breadcrumbs', $widget_ops);
    }

    public function widget($args, $instance)
    {
        global $wp_query;

        extract($args);

        $title = $instance['title'];
        $home_title = trim($instance['home_title']);

        if (empty($home_title)) {
            $home_title = 'Home';
        }

        echo $before_widget;

        if ($title) {
            echo $before_title . $title . $after_title;
        }

        if (!is_home() && !is_front_page()) {

            $output = '<ul class="uk-breadcrumb">';

            $output .= '<li><a href="'.get_option('home').'">'.Главная.'</a></li>';

            if (is_single()) {
                $cats = get_the_category();
                $cat = $cats[0];
                if (is_object($cat)) {
                    if ($cat->parent != 0) {
                        $cats = explode("@@@", get_category_parents($cat->term_id, true, "@@@"));

                        unset($cats[count($cats)-1]);
                        $output .= str_replace('<li>@@','<li>', '<li>'.implode("</li><li>", $cats).'</li>');
                    } else {
                        $output .= '<li><a href="'.get_category_link($cat->term_id).'">'.$cat->name.'</a></li>';
                    }
                }
            }
            //Раздел афиша
            if ($_SERVER['REQUEST_URI'] == '/concert/') {

                $output .='<li class="uk-active">';
                $output .= '<span>Афиша</span>';
                $output .='</li>';
                $output .='</ul>';
                echo $output;
                echo $after_widget;
                return 0;
            }

            //Раздел новости
            if ($_SERVER['REQUEST_URI'] == '/news/') {

                $output .='<li class="uk-active">';
                $output .= '<span>Новости</span>';
                $output .='</li>';
                $output .='</ul>';
                echo $output;
                echo $after_widget;
                return 0;
            }

            //Раздел релизы
            if ($_SERVER['REQUEST_URI'] == '/audio/') {

                $output .='<li class="uk-active">';
                $output .= '<span>Релизы</span>';
                $output .='</li>';
                $output .='</ul>';
                echo $output;
                echo $after_widget;
                return 0;
            }

            //Раздел медиа
            if ($_SERVER['REQUEST_URI'] == '/media/') {

                $output .='<li class="uk-active">';
                $output .= '<span>Медиа</span>';
                $output .='</li>';
                $output .='</ul>';
                echo $output;
                echo $after_widget;
                return 0;
            }

            //Раздел поиск
            if ($_SERVER['REQUEST_URI'] == '/search/') {

                $output .='<li class="uk-active">';
                $output .= '<span>Поиск по запросу</span>';
                $output .='</li>';
                $output .='</ul>';
            }

            //Раздел афиша - персональная страница афиши.
            if (strstr($_SERVER['REQUEST_URI'],'concert'))
                $output .= '<li><a href="/concert/">Афиша</a></li>';

            //Раздел новости - персональная страница новости.
            if (strstr($_SERVER['REQUEST_URI'],'news'))
                $output .= '<li><a href="/news/">Новости</a></li>';

            //Раздел аудио - персональная страница аудио.
            if (strstr($_SERVER['REQUEST_URI'],'audio'))
                $output .= '<li><a href="/audio/">Релизы</a></li>';

            //Раздел медиа - персональная страница медиа.
            if (strstr($_SERVER['REQUEST_URI'],'media'))
                $output .= '<li><a href="/media/">Медиа</a></li>';


            if (is_category()) {

                $cat_obj = $wp_query->get_queried_object();

                $cats = explode("@@@", get_category_parents($cat_obj->term_id, TRUE, '@@@'));

                unset($cats[count($cats)-1]);

                $cats[count($cats)-1] = '@@<span>'.strip_tags($cats[count($cats)-1]).'</span>';

                $output .= str_replace('<li>@@','<li class="uk-active">', '<li>'.implode("</li><li>", $cats).'</li>');
            } elseif (is_tag()) {
                $output .= '<li class="uk-active"><span>'.single_cat_title('',false).'</span></li>';
            } elseif (is_date()) {
                $output .= '<li class="uk-active"><span>'.single_month_title(' ',false).'</span></li>';
            } elseif (is_author()) {

                $user = !empty($wp_query->query_vars['author_name']) ? get_userdatabylogin($wp_query->query_vars['author']) : get_user_by("id", ((int) $_GET['author']));

                $output .= '<li class="uk-active"><span>'.$user->display_name.'</span></li>';
            } elseif (is_search()) {
                $output .= '<li class="uk-active"><span>'.stripslashes(strip_tags(get_search_query())).'</span></li>';
            } elseif (is_tax()) {
                $taxonomy = get_taxonomy (get_query_var('taxonomy'));
                $term = get_query_var('term');
                $output .= '<li class="uk-active"><span>'.$taxonomy->label .': '.$term.'</span></li>';
            } else {
                $output .= '<li class="uk-active"><span>'.get_the_title().'</span></li>';
            }

            $output .= '</ul>';

        } else {

            $output = '<ul class="uk-breadcrumb">';

            $output .= '<li class="uk-active"><span>'.$home_title.'</span></li>';

            $output .= '</ul>';

        }

        echo $output;

        echo $after_widget;

    }

    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }

    public function form($instance)
    {
        $title = esc_attr($instance['title']);
        $home_title = esc_attr($instance['home_title']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Title:','warp') ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title') ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title') ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('home_title') ?>"><?php _e('Home title:','warp') ?></label>
            <input type="text" placeholder="Home" name="<?php echo $this->get_field_name('home_title') ?>"  value="<?php echo $home_title ?>" class="widefat" id="<?php echo $this->get_field_id('home_title') ?>">
        </p>
        <?php
    }
}

register_widget('Warp_Breadcrumbs');
