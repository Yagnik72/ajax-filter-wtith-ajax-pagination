<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
    wp_enqueue_style(
            'hello-elementor-child-style',
            get_stylesheet_directory_uri() . '/style.css',
            [
                'hello-elementor-theme-style',
            ],
            '1.0.0'
    );
    wp_enqueue_style('hello-elementor-bootstrap-style', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css', '', '1.0.0');
    wp_enqueue_script('hello-elementor-bootstrap-script', get_stylesheet_directory_uri() . '/assets/js/bootstrap.min.js', ['jquery'], '1.0.0', true);
    wp_register_script('hello-elementor-custom-script', get_stylesheet_directory_uri() . '/assets/js/custom-script.js', ['jquery'], '1.0.0', true);
    wp_localize_script('hello-elementor-custom-script', 'branch_ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20);

/**
 * ADT branch location shortcode
 *
 */
function adt_branch_shortcode_function() {
    wp_enqueue_script('hello-elementor-custom-script');
    ?>
    <div class="container">
        <div class="col-md-4 col-xs-12">
            <div class="checkbox_check">
                <a class="accordion-toggle collapsed cst_mobile_show" data-toggle="collapse" data-parent="#accordion" href="#acc_supp_mobile_checkbox" title=".">
                    <div class="customerSuppCat_heading">
                        <img src="<?php echo site_url(); ?>/wp-content/uploads/2021/08/filter_list.svg" alt="faq_panel">
                        <h3><?php _e('Filter by:', 'hello-elementor'); ?></h3>
                    </div>
                </a>
                <div class="customerSuppCat_heading cst_mobile_hide">
                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2021/08/filter_list.svg" alt="faq_panel">
                    <h3><?php _e('Filter by:', 'hello-elementor'); ?></h3>
                </div>
                <?php
                $location_field = get_field_object('field_611e216450d1f');
                $location_choices = $location_field['choices'];
                if (!empty($location_choices)) {
                    ?>
                    <div class="panel branch-locator-provinces collapse desktop_block" id="acc_supp_mobile_checkbox">
                        <ul class="unstyled centered">
                            <?php foreach ($location_choices as $key => $value) { ?>
                                <li>
                                    <input type="radio" name="adt_location" class="eastern-cape-toggle adt-location" value="<?php echo $key; ?>" id="<?php echo $key; ?>">
                                    <label for="<?php echo $key; ?>"><?php echo $value; ?></label>
                                </li>
                            <?php } ?>
                            <li id="no_border_btn">
                                <input type="radio" class="all-toggle adt-location" name="adt_location" value="all" checked="checked" id="location_all">
                                <label for="location_all"><?php _e('View All', 'hello-elementor'); ?></label>
                            </li>
                        </ul>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="checkbox_check checkbox_second">
                <div class="customerSuppCat_heading cst_mobile_hide">
                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2021/08/filter_list.svg" alt="faq_panel">
                    <h3><?php _e('View:', 'hello-elementor'); ?></h3>
                </div>
                <a class="accordion-toggle collapsed cst_mobile_show" data-toggle="collapse" data-parent="#accordion" href="#acc_supp_mobile_checkbox_second" title=".">
                    <div class="customerSuppCat_heading">
                        <img src="<?php echo site_url(); ?>/adt/wp-content/uploads/2021/08/filter_list.svg" alt="faq_panel">
                        <h3><?php _e('View:', 'hello-elementor'); ?></h3>
                    </div>
                </a>
                <?php
                $groups_field = get_field_object('field_611e21d750d20');
                $groups_choices = $groups_field['choices'];
                if (!empty($groups_choices)) {
                    ?>
                    <div class="panel collapse desktop_block" id="acc_supp_mobile_checkbox_second">
                        <ul class="unstyled centered">
                            <?php foreach ($groups_choices as $key => $value) { ?>
                                <li>
                                    <input type="radio" name="adt_groups" class="fidelity-adt-toggle adt-groups" value="<?php echo $key; ?>" id="<?php echo $key; ?>">
                                    <label for="<?php echo $key; ?>"><?php echo $value; ?></label>
                                </li>                                
                            <?php } ?>
                            <li id="no_border_btn">
                                <input type="radio" name="adt_groups" class="fidelity-services-group-toggle adt-groups" value="all" checked="checked" id="groups_all">
                                <label for="groups_all"><?php _e('View All', 'hello-elementor'); ?></label>
                            </li>
                        </ul>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="col-md-8 col-xs-12">
            <div class="desktop_panel">
                <div class="panel-group ng-scope">
                    <div class="eastern-cape">
                        <div class="branch-search-container">
                            <div class="branch-search">
                                <span><?php _e('Search:', 'hello-elementor'); ?></span>
                                <input type="text" name="adt_search" id="adt_search" placeholder="Search by Branch Name">
                            </div>
                        </div>
                        <div class="eastern-cape-branch fidelity-services-group">
                            <?php
                            /* Build a query */
                            $args = array(
                                'post_type' => 'branch-locator',
                                'post_status' => 'publish',
                                'posts_per_page' => 6,
                                'orderby' => 'date',
                                'order' => 'DESC',
                            );
                            $wp_query = new WP_Query($args);
                            if ($wp_query->have_posts()):
                                while ($wp_query->have_posts()): $wp_query->the_post();
                                    $post_id = get_the_ID();
                                    $address_field = get_field('address', $post_id);
                                    $phone_field = get_field('phone', $post_id);
                                    $map_link_field = get_field('map_link', $post_id);

                                    $groups_field_keys = get_field_object('field_611e21d750d20');
                                    $groups_choices = $groups_field_keys['choices'];
                                    $groups_field = get_field('groups', $post_id);
                                    ?>
                                    <div class="panel panel-default new_panel">
                                        <div class="panel-heading ph_spacing">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $post_id; ?>" title=".">
                                                    <div class="faq_panel" style="font-size: 20px;">
                                                        <?php the_title(); ?> <span class="province"></span>
                                                        <span class="label-info pull-right"></span>
                                                    </div>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse<?php echo $post_id; ?>" class="panel-collapse collapse" aria-expanded="false" style="font-size: 40px;">
                                            <div class="panel-body">
                                                <div class="branch_deets">
                                                    <?php if (!empty($address_field)) { ?>
                                                        <div class="branch_info">
                                                            <img src="<?php echo site_url(); ?>/wp-content/uploads/2021/08/location_on_24px.svg" alt="">
                                                            <p><?php _e($address_field, 'hello-elementor'); ?></p>
                                                        </div>
                                                        <?php
                                                    }
                                                    if (!empty($phone_field)) {
                                                        ?>
                                                        <div class="branch_info">
                                                            <img src="<?php echo site_url(); ?>/wp-content/uploads/2021/08/phone_24px.svg" alt="">
                                                            <p><?php echo $phone_field; ?></p>
                                                        </div>
                                                        <?php
                                                    }
                                                    if (!empty($groups_choices[$groups_field])) {
                                                        ?>
                                                        <div class="branch_info branch_bottom">
                                                            <img src="<?php echo site_url(); ?>/wp-content/uploads/2021/08/business_24px.svg" alt="">
                                                            <p><?php _e($groups_choices[$groups_field], 'hello-elementor'); ?></p>
                                                        </div>
                                                        <?php
                                                    }
                                                    if (!empty($map_link_field)) {
                                                        ?>
                                                        <div class="vomaps" align="right">
                                                            <a target="_blank" href="<?php echo esc_url($map_link_field); ?>">
                                                                <p>VIEW ON MAPS</p>
                                                                <img src="<?php echo site_url(); ?>/wp-content/uploads/2021/08/map_24px.svg" alt="">
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                endwhile;

                                $big = 999999999;
                                echo '<nav class="gt-pagination pagination" id="gt_pagination">';
                                echo paginate_links(array(
                                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                                    'format' => '?paged=%#%',
                                    'current' => max(1, get_query_var('paged')),
                                    'total' => $wp_query->max_num_pages
                                ));
                                echo '</nav>';
                            else :
                                ?>
                                <p><?php _e('No branchs found.', 'hello-elementor'); ?></p>
                            <?php
                            endif;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

add_shortcode('adt_branch_list', 'adt_branch_shortcode_function');


/*
 * Branch Filter Handler AJAX
 */
add_action('wp_ajax_nopriv_branch_filter', 'branch_filter_handler');
add_action('wp_ajax_branch_filter', 'branch_filter_handler');

function branch_filter_handler() {
    // Get all post data
    $paged = (!empty($_POST['page_number'])) ? sanitize_text_field($_POST['page_number']) : 1;
    $adt_search = isset($_POST['adt_search']) ? sanitize_text_field($_POST['adt_search']) : '';
    $adt_location = isset($_POST['adt_location']) ? sanitize_text_field($_POST['adt_location']) : '';
    $adt_groups = isset($_POST['adt_groups']) ? sanitize_text_field($_POST['adt_groups']) : '';

    // Build a query
    $args = array(
        'post_type' => 'branch-locator',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'paged' => $paged,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    // Search by Branch Name
    if ($adt_search) {
        $args['s'] = $adt_search;
    }

    // Search by Location and Groups
    if (!empty($adt_groups) || !empty($adt_location)) {
        $adt_location_array = $adt_groups_array = array();
        $args['meta_query'] = array(
            'relation' => 'AND'
        );

        // Check location field is not empty
        if ($adt_location != 'all') {
            $adt_location_array = array(
                'key' => 'location',
                'value' => $adt_location,
                'compare' => '='
            );
            // Push the data to the meta_query
            array_push($args['meta_query'], $adt_location_array);
        }

        // Check groups field is not empty
        if ($adt_groups != 'all') {
            $adt_groups_array = array(
                'key' => 'groups',
                'value' => $adt_groups,
                'compare' => '='
            );
            // Push the data to the meta_query
            array_push($args['meta_query'], $adt_groups_array);
        }
    }
    
    // Fire the WP_Query
    $wp_query = new WP_Query($args);

    ob_start();
    if ($wp_query->have_posts()):
        while ($wp_query->have_posts()): $wp_query->the_post();
            $post_id = get_the_ID();
            $address_field = get_field('address', $post_id);
            $phone_field = get_field('phone', $post_id);
            $map_link_field = get_field('map_link', $post_id);

            $groups_field_keys = get_field_object('field_611e21d750d20');
            $groups_choices = $groups_field_keys['choices'];
            $groups_field = get_field('groups', $post_id);
            ?>
            <div class="panel panel-default new_panel">
                <div class="panel-heading ph_spacing">
                    <h4 class="panel-title">
                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $post_id; ?>" title=".">
                            <div class="faq_panel" style="font-size: 20px;">
                                <?php the_title(); ?> <span class="province"></span>
                                <span class="label-info pull-right"></span>
                            </div>
                        </a>
                    </h4>
                </div>
                <div id="collapse<?php echo $post_id; ?>" class="panel-collapse collapse" aria-expanded="false" style="font-size: 40px;">
                    <div class="panel-body">
                        <div class="branch_deets">
                            <?php if (!empty($address_field)) { ?>
                                <div class="branch_info">
                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2021/08/location_on_24px.svg" alt="">
                                    <p><?php _e($address_field, 'hello-elementor'); ?></p>
                                </div>
                                <?php
                            }
                            if (!empty($phone_field)) {
                                ?>
                                <div class="branch_info">
                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2021/08/phone_24px.svg" alt="">
                                    <p><?php echo $phone_field; ?></p>
                                </div>
                                <?php
                            }
                            if (!empty($groups_choices[$groups_field])) {
                                ?>
                                <div class="branch_info branch_bottom">
                                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2021/08/business_24px.svg" alt="">
                                    <p><?php _e($groups_choices[$groups_field], 'hello-elementor'); ?></p>
                                </div>
                                <?php
                            }
                            if (!empty($map_link_field)) {
                                ?>
                                <div class="vomaps" align="right">
                                    <a target="_blank" href="<?php echo esc_url($map_link_field); ?>">
                                        <p><?php _e('VIEW ON MAPS', 'hello-elementor'); ?></p>
                                        <img src="<?php echo site_url(); ?>/wp-content/uploads/2021/08/map_24px.svg" alt="">
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        endwhile;

        $big = 999999999;
        echo '<nav class="gt-pagination pagination" id="gt_pagination">';
        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, $paged),
            'total' => $wp_query->max_num_pages
        ));
        echo '</nav>';
    else :
        ?>
        <p><?php _e('No branch found.', 'hello-elementor'); ?></p>
    <?php
    endif;
    wp_reset_postdata();

    $post_data = ob_get_clean();
    echo $post_data;
    die;
}


// add_action('wp_head', 'add_custom_js');
function add_custom_js () {
?>    
<script>
    jQuery( document ).ready(function() {
        jQuery(window).scroll(function(){
		  var sticky = jQuery('.header-hide'),
		      scroll = jQuery(window).scrollTop();

		  if (scroll >= 400) {
			sticky.addClass('true');  
		  }else {
		 	 sticky.removeClass('true');
		  }
		});
    });  
</script>
<?php
}
