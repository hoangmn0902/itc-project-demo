<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

require_once (ITC_PLUGIN_ADV_SEARCH_PATH.'/inc/itc-functions.php');

//enqueue jQuery
if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
	wp_enqueue_script( 'jquery');
}

$get = wp_unslash($_GET);
?>

<div class="itc-search-container">
    <form id="itc-search-form">
        <div class="container-top">
            <div>
                <label for="fname">Keyword</label><br>
                <input type="text" name="q" id="q" size="30" value="<?php echo esc_attr($get['q']) ?? null;?>">
            </div>
            <div>
                <label for="fname">Category</label><br>
                <select id="cat" name="cat">
				    <?php
				    $categories = get_categories();

				    if ( $categories ) {
					    foreach ( $categories as $category ) {
						    $selected = (isset($get['cat']) && $get['cat'] == $category->term_id)? 'selected' : null;
						    echo "<option value='$category->term_id' $selected>$category->name</option>";
					    }
				    }
				    ?>
                </select>
            </div>
            <div style="align-content: end;">
                <button id="btn-submit" type="submit">Search</button>
            </div>
        </div>

        <!--    show tags-->
        <div class="container-tag">
		    <?php
		    $tags = get_tags();
		    if ( $tags ) {
			    foreach ( $tags as $tag ) {
				    $checked = (isset($get['tags']) && in_array($tag->term_id, $get['tags']))? 'checked' : null;
				    ?>
                    <input type="checkbox" id="checkbox-<?php echo $tag->term_id; ?>" name="tags[]" value="<?php echo $tag->term_id; ?>" <?php echo $checked?>>
                    <label for="checkbox-<?php echo $tag->term_id; ?>"><?php echo $tag->name; ?></label>
				    <?php
			    }
		    }
		    ?>
        </div>
    </form>

    <!--    show search result-->
    <div class="container-result">
        <?php
            $get = wp_unslash($_GET);
            itc_search_by_param($get);
        ?>
    </div>


</div>
