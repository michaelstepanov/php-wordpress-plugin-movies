<?php
/*
  Plugin Name: MS Movies
  Plugin URI:
  Description: Declares a plugin that will create a custom post type displaying movies.
  Version: 1.0
  Author: Michael Stepanov
  Author URI:
  License: GPL2
 */

add_action('init', array('MS_Movies', 'register_post_type_movie'));
add_action('admin_init', array('MS_Movies', 'display_movie_details'));
add_action('save_post', array('MS_Movies', 'add_movie_details'), 10, 2);
add_action('wp_head', array('MS_Movies', 'add_noindex'), 1, 1);
add_action('wp_head', array('MS_Movies', 'add_nofollow'), 1, 1);
add_action('wp_ajax_set_movie_rating', array('MS_Movies', 'set_movie_rating'));
add_action('wp_ajax_nopriv_set_movie_rating', array('MS_Movies', 'set_movie_rating'));
add_filter('template_include', array('MS_Movies', 'include_template_function'), 1);

wp_register_style('ms_movie', plugins_url('styles/style.css', __FILE__));
wp_enqueue_style('ms_movie');

wp_enqueue_script('jquery');
wp_register_script('ms_movie', plugins_url('js/index.js', __FILE__));
wp_enqueue_script('ms_movie');

class MS_Movies
{

    public function register_post_type_movie()
    {
        register_post_type('movie', array(
            'labels' => array(
                'name' => 'Movies',
                'singular_name' => 'Movie',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Movie',
                'edit' => 'Edit',
                'edit_item' => 'Edit Movie',
                'new_item' => 'New Movie',
                'view' => 'View',
                'view_item' => 'View Movie',
                'search_items' => 'Search Movies',
                'not_found' => 'No Movies found',
                'not_found_in_trash' => 'No Movies found in Trash',
                'parent' => 'Parent Movie',
            ),
            'public' => true,
            'menu_position' => 15,
            'supports' => array('title', 'editor', 'comments', 'thumbnail', 'custom-fields'),
            'taxonomies' => array(''),
            'has_archive' => true,
        ));
    }

    public function set_movie_rating()
    {
        $id = $_POST['id'];
        $new_rating = $_POST['rating'];
        // Get current rating
        $rating = get_post_meta($id, 'rating', true);
        // If rating have been already set
        if ($rating >= 1)
        {
            die('false');
        }
        // Update rating
        update_post_meta($id, 'rating', $new_rating);
        die();
    }

    public function include_template_function($template_path)
    {
        if (get_post_type() == 'movie')
        {
            if (is_single())
            {
                // Checks if the file exists in the theme first,
                // otherwise serve the file from the plugin
                if ($theme_file = locate_template(array('single-movie.php')))
                {
                    $template_path = $theme_file;
                } else
                {
                    $template_path = plugin_dir_path(__FILE__) . '/single-movie.php';
                }
            }
        }
        return $template_path;
    }

    public function add_noindex()
    {
        if (get_post_type() === 'movie')
        {
            ?>
            <meta name="robots" content="noindex">
            <?php
        }
    }

    public function add_nofollow()
    {
        if (get_post_type() === 'movie')
        {
            ?>
            <meta name="robots" content="nofollow">
            <?php
        }
    }

    public function display_movie_details()
    {
        add_meta_box('movie_meta_box', 'Movie Details', array(self::class, 'display_movie_meta_box'), 'movie', 'normal', 'high');
    }

    public function display_movie_meta_box($movie)
    {
        // Retrieve current Movie Rating based on Movie ID
        $movie_rating = intval(get_post_meta($movie->ID, 'rating', true));
        ?>
        <table>
            <tr>
                <td style="width: 150px">Movie Rating</td>
                <td>
                    <select style="width: 100px" name="rating">
                        <?php
                        // Generate all items of drop-down list
                        for ($rating = 0; $rating <= 5; ++$rating)
                        {
                            ?>
                            <option value="<?php echo $rating; ?>" <?php echo selected($rating, $movie_rating); ?>>
                                <?php echo $rating; ?> stars <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    public function add_movie_details($movie_id, $movie)
    {
        // Check post type for movie
        if ($movie->post_type == 'movie')
        {
            // Store data in post meta table if present in post data
            if (isset($_POST['rating']) && $_POST['rating'] != '')
            {
                update_post_meta($movie_id, 'rating', $_POST['rating']);
            }
        }
    }

}
