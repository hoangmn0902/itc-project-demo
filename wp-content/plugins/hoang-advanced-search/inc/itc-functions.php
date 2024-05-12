<?php

/**
 * Search by method GET
 * @param array $get
 */
function itc_search_by_param($get){
	if ( empty( $get ) ) {
		return;
	}

	$args = itc_generate_search_param($get);

	//display search result
    itc_display_search_result($args);
}

/**
 *
 * Ajax callback function
 */
function itc_search_ajax() {
    if (!isset($_POST)){
        die();
    }
	$post  = wp_unslash( $_POST );

    if (isset($post['tags']) && !empty($post['tags'])){
	    $post['tags'] = explode(",",$post['tags']);
    }

	$args = itc_generate_search_param($post);

	//display search result
	itc_display_search_result($args);
	exit();
}
add_action('wp_ajax_itc_search_ajax','itc_search_ajax');

/**
 * generate_search_param
 * @param array $args
 * @return array
 */
function itc_generate_search_param($param): array{

	$keyword = $param['q'] ?? null;
	$cat     = $param['cat'] ?? null;
	$tags    = $param['tags'] ?? null; //array of tags
	$paged   = $param['paged'] ?? 1;

	$args  = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 5,
		'paged'          => $paged,
	);

	if ( ! empty( $keyword ) ) {
		$args['s'] = $keyword;
	}
	if ( ! empty( $cat ) ) {
		$args['cat'] = $cat;
	}
	if ( ! empty( $tags ) ) {
		$args['tag__in'] = $tags;
	}

	return $args;
}

function itc_enqueue() {
		wp_enqueue_script('itc-search-view-js', ITC_PLUGIN_ADV_SEARCH_URI . 'src/view.js', array(), time(), true);
		$js_object = array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'nonce-itc-search' ),
		);
		wp_localize_script( 'itc-search-view-js', 'itc_search', $js_object );

}
add_action('wp_enqueue_scripts', 'itc_enqueue');

/**
 * Display search result
 * @param array $args param for WP_Query
 * @return void
 */
function itc_display_search_result( array $args): void{
	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
	    $total_pages = $query->max_num_pages;

		while ( $query->have_posts() ):
			$query->the_post();
        ?>
            <div class="item-result">
                <div class="feature-image">
					<?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail(); // Change 'medium' to desired image size ?>
                        </a>
                    <?php else:?>
                        <?php $image_url = ITC_PLUGIN_ADV_SEARCH_URI . '/assets/no-image.jpg'; ?>
                        <img width="64" height="64" src="<?php echo esc_url($image_url)?>" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" decoding="async" loading="lazy">
					<?php endif; ?>
                </div>
                <div class="post-content">
                    <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <div class="excerpt"><?php echo substr( get_the_excerpt(),0,150 ).'...'; ?></div>
                </div>
            </div>
		<?php endwhile; ?>

        <!--     pagination-->
        <div class="search-pagination">
            <a>Previous</a>
            <?php
                for ($i = 1; $i <= $total_pages; $i++){
                    $class_active = ($i == $args['paged'])? 'active' :'';
	                echo "<a class='$class_active'>$i</a>";
                }
            ?>
            <a>Next</a>
        </div>

	<?php
	} else {
		echo 'No posts found.';
	}

	wp_reset_postdata();
}