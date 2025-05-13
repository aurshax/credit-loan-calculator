<?php
namespace CreditLoanCalculator\PostTypes;

class Submissions
{
    /**
     *  Constructor
     */
    public function __construct()
    {
        add_action('init', array($this, 'register_post_types'));
        add_action('add_meta_boxes', array($this, 'create_meta_box'));
        add_filter('manage_submission_posts_columns', array($this, 'add_table_columns'));
        add_action('manage_submission_posts_custom_column', array($this, 'populate_table_columns'), 10, 2);
    }

    /**
     * @return array[]
     */
    public function init_post_types()
    {
        $supports = array(
            'title',
            'excerpt',
        );

        return [
            [
                'slug' => 'submission',
                'label' => [
                    'name' => __('Submissions', 'ymca'),
                    'singular_name' => __('Submission', 'ymca'),
                ],
                'supports' => $supports
            ]
        ];
    }

    /**
     * @return void
     */
    public function register_post_types()
    {
        $post_types = $this->init_post_types();

        foreach ($post_types as $post_type) {
            $args = array(
                'labels' => $post_type['label'],
                'supports' => $post_type['supports'],
                'taxonomies' => array(), // Allowed taxonomies
                'hierarchical' => false, // Allows hierarchical categorization, if set to false, the Custom Post Type will behave like Post, else it will behave like Page
                'public' => false,  // Makes the post type public
                'show_ui' => true,  // Displays an interface for this post type
                'show_in_menu' => true,  // Displays in the Admin Menu (the left panel)
                'show_in_nav_menus' => true,  // Displays in Appearance -> Menus
                'show_in_admin_bar' => true,  // Displays in the black admin bar
                'menu_position' => 5,     // The position number in the left menu
                'menu_icon' => true,  // The URL for the icon used for this post type
                'can_export' => true,  // Allows content export using Tools -> Export
                'has_archive' => false,  // Enables post type archive (by month, date, or year)
                'exclude_from_search' => true, // Excludes posts of this type in the front-end search result page if set to true, include them if set to false
                'publicly_queryable' => true,  // Allows queries to be performed on the front-end part if set to true
                'capability_type' => 'page',
                'map_meta_cap'  => true,
            );

            register_post_type($post_type['slug'], $args);
        }
    }

    /**
     * @return void
     */
    public function create_meta_box() {
        add_meta_box(
            'submission-data',
            'Submission Data',
            array($this, 'render_meta_box'),
            'submission',
            'normal',
            'high'
        );
    }

    /**
     * @return void
     */
    public function render_meta_box() {
        global $post;
        $email = get_post_meta($post->ID, 'email', true);
        $name = get_post_meta($post->ID, 'name', true);
        $phone = get_post_meta($post->ID, 'phone', true);
        $birthday = get_post_meta($post->ID, 'birthday', true);
        $loan = get_post_meta($post->ID, 'loan', true);

        ?>
        <div class="wrapper" style="padding-top: 5px">
            <div class="row" style="margin-bottom: 10px"><strong>Email</strong>: <span><?php echo $email ?></span></div>
            <div class="row" style="margin-bottom: 10px"><strong>Name</strong>: <span><?php echo $name ?></span></div>
            <div class="row" style="margin-bottom: 10px"><strong>Phone</strong>: <span><?php echo $phone ?></span></div>
            <div class="row" style="margin-bottom: 10px"><strong>Birthday</strong>: <span><?php echo $birthday ?></span></div>
            <div class="row"><strong>Loan Amount</strong>: <span><?php echo $loan ?></span></div>
        </div>
        <?php
    }

    /**
     * Add Columns
     *
     * @param $columns
     * @return mixed
     */
    public function add_table_columns($columns) {
        $date_column = $columns['date'];
        unset($columns['date']);

        $columns['name'] = 'Name';
        $columns['phone'] = 'Phone';
        $columns['date'] = $date_column;

        return $columns;
    }

    /**
     * Populate Table Columns
     *
     * @param $column
     * @param $post_id
     * @return void
     */
    public function populate_table_columns($column, $post_id) {
        $name = get_post_meta($post_id, 'name', true);
        $phone = get_post_meta($post_id, 'phone', true);

        if ($column == 'name') {
            echo $name;
        }

        if ($column == 'phone') {
            echo $phone;
        }
    }
}
