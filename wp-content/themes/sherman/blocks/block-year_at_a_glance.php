<?php
//
// MODULE - Year at a Glance
//
// Renders all tours with links to print PDF. For internal use only, not for public
//

?>
<?php
if( !function_exists( 'duvine_show_US_tours' ) ) :
/**
 * List all US tours...this is used to group US tours
 *
 * @return none
 */
function duvine_show_US_tours(){

    $filterLevel = ( isset($_GET['glevel']) && !empty($_GET['glevel']) ) ?  $_GET['glevel'] : '';
    $filterYear = ( isset($_GET['gyear']) && !empty($_GET['gyear']) ) ?  $_GET['gyear'] : date('Y');
    $filterPrivate = ( isset($_GET['tourtype']) && !empty($_GET['tourtype']) ) ?  $_GET['tourtype'] : '';

    if ( $post = get_page_by_path( 'united-states', OBJECT, 'location' ) )
        $id = $post->ID;
    else
        $id = 0;

    if( $id === 0 ) return false;

    $defaults = array(
        'post_type' => 'tour',
        'orderby'   => array( 'title' => 'ASC'),
        'posts_per_page' => -1,
        'post_status'      => 'publish',
        'meta_query'      => array(
            array(
                'key'     => 'd_tour_location',
                'value'   => '"' . $id . '"',
                'compare' => 'LIKE'
            )
        )
    );

    // The Query
    $tourList = new WP_Query( $defaults );

    $depth = 2;

    $tourQuery = duvine_organize_tours_by_location( $tourList->posts, $depth );

     if ( $tourQuery ) :
        //$tour_locations = count( $tourQuery );
        //error_log( print_r($tourQuery, true) );
        uksort($tourQuery, 'duvine_sort_tours_by_location'); ?>

        <div class="year-glance__region" data-country="united-states">
            <h3 class="year-glance__region-name">United  States</h3>

        <?php foreach( $tourQuery as $loc => $tours ) : ?>
        <?php if( $tours ) : ?>
        
            <div class="year-glance__region-tours">
                <?php 
                foreach ( $tours as $index => $tour ): 
                    $tour_regions = get_field("d_tour_location", $tour->ID);
                    $new_sub_region = (!empty($tour_regions[1])) ? $tour_regions[1] : "";

                    // filter for private/schedule
                    if( get_field('d_private_only', $tour->ID) && $filterPrivate == 'scheduled' ) continue;

                    if( !get_field('d_private_only', $tour->ID) && $filterPrivate == 'private' ) continue;

                    $level = 0;
                    if( get_field('d_private_only', $tour->ID) ) :
                        $level = get_field("d_cycling_level", $tour->ID);
                        $level = $level['value'];
                    else : 
                        // not private, so get level from centaur feed
                        $centaur_id = trim(get_field('d_tour_centaur_id', $tour->ID));
                        $centaur_level = duvine_get_tour_level($centaur_id);
                        $level = ($centaur_level) ? $centaur_level : 0;
                    endif;
                    if( is_numeric($filterLevel) ) :
                        if( $level != $filterLevel ) continue;
                    endif;
                    if($sub_region !== $new_sub_region): ?>
                    <div class="year-glance__region-dates">
                        <div class="year-glance__region-dates-sub"><?php echo ($new_sub_region == "") ? "" : get_the_title($new_sub_region); echo " $filterYear"; ?></div>
                        <?php 
                        for($month = 1; $month <= 12; $month++): 
                            $dateObj = DateTime::createFromFormat('!m', $month);
                            ?>
                            <div class="year-glance__region-dates-month"><?php echo $dateObj->format('M'); ?></div>
                        <?php endfor; ?>
                    </div>
                    <?php endif; $sub_region = $new_sub_region; ?>
                    <div class="year-glance__region-tour">
                        <div class="year-glance__region-tour-info">
                            <p><a href="<?php the_permalink($tour); ?>"><strong><?php echo $tour->post_title; ?></strong></a></p>
                            <?php
                            // Get the lowest price for this tour
                            $price = 99999;

                            if( get_field('d_private_only', $tour->ID) ) {
                                // if private tour, there are no dates, so just use starting price
                                $price = get_field("d_private_starting_price", $tour->ID);
                                $price = apply_filters('format_price', floatval($price));

                            } else {
                                if( $centaur_id ) :
                                    $price = duvine_get_minimum_price($centaur_id );
                                endif;
                            }
                            ?>
                            <p>Price From: <?php echo $price; ?></p>
                            <p>Level: <?php echo $level; ?></p>
                            <a href="/print-to-pdf/?tour_id=<?php echo $tour->ID; ?>" class="year-glance__region-tour-print" target="_blank"></a>
                        </div>

                        <?php
                        // get all dates for this tour from Centaur
                        $tour_dates = false;
                        if( !get_field('d_private_only', $tour->ID) ) :
                            $tour_dates = duvine_get_tour_dates($tour);
                        endif;
                        if( isset($tour_dates[$filterYear]) ) :
                            $tour_dates = $tour_dates[$filterYear];
                        else :
                            $tour_dates = false;
                        endif;
                        ?>
                        <?php for($month = 1; $month <= 12; $month++): ?>
                        <?php
                        $display_dates = '';
                        
                        if( $tour_dates ) :
                            foreach( $tour_dates as $tour_date ) :
                                //var_dump($tour_date);
                                
                                $tour_month = explode('/',$tour_date['start_date']);
                                $tour_month = $tour_month[0];
                                if( $tour_month == $month ) :
                                    $label = explode('&ndash;', $tour_date['label']);
                                    $start_date = $label[0];
                                    //echo 'start: '.$start_date.'<br>';
                                    $start_month = explode(' ',$start_date);
                                    $display_month = date('n', strtotime("$start_month[0] 1 $filterYear"));

                                    //$start_date = $display_month.'/'.$start_month[1];
                                    $start_date = $start_month[1];

                                    //echo 'start: '.$start_date.'<br>';
                                    
                                    $end_date = $label[1];
                                    
                                    // end on a different month?
                                    if( strlen(trim($end_date)) > 2 ) :
                                        $display_end_month = ($display_month < 12) ? $display_month + 1 : 1;

                                        $end_day = explode(' ',$end_date);
                                        $end_day = $end_day[2];

                                    else :
                                        $display_end_month = '';
                                        $end_day = trim($end_date);
                                    endif;

                                    $tour_status = duvine_get_tour_date_status($tour_date['flags']);

                                    if( strlen($display_end_month) > 0 ) :
                                        $display_dates = $display_dates.'<span class="'.$tour_status.'">'.$start_date.' - '.$display_end_month.'/'.$end_day.'</span>';
                                    else :
                                        $display_dates = $display_dates.'<span class="'.$tour_status.'">'.$start_date.' - '.$end_day.'</span>';
                                    endif;

                                endif;
                            endforeach;
                        endif;
                        ?>
                        <div class="year-glance__region-tour-month">
                            <?php echo $display_dates; ?>
                        </div>
                        <?php endfor; ?>
                    </div>
                    <?php 
                endforeach; ?>
            </div>
        <?php endif;
        endforeach; ?>
        </div>
    <?php endif;

    return;
}
endif; // duvine_show_US_tours

// used to find out if a tour has a sub region, for example 'Alps'
function countdim($array) {
    if (is_array(reset($array))) {
        $return = countdim(reset($array)) + 1;
    } else {
        $return = 1;
    }

    return $return;
}
?>

<?php
global $post;

if ( post_password_required( $post ) ) {
    echo get_the_password_form( $post );
} else {
    $filterYear = ( isset($_GET['gyear']) && !empty($_GET['gyear']) ) ?  $_GET['gyear'] : date('Y');
    $filterLevel = ( isset($_GET['glevel']) && !empty($_GET['glevel']) ) ?  $_GET['glevel'] : '';
    $filterPrivate = ( isset($_GET['tourtype']) && !empty($_GET['tourtype']) ) ?  $_GET['tourtype'] : '';
?>
<div class="year-glance__greybar">
    <div class="year-glance__greybar-container l-container">
        <div class="year-glance__greybar-filter">
            <div class="year-glance__greybar-filter-item">
                <label>Year</label>
                <select id="glance-year">
                    <option value="<?php echo date('Y'); ?>"<?php if( $filterYear == date('Y') ) echo ' selected'; ?>><?php echo date('Y'); ?></option>
                    <option value="<?php echo date('Y')+1; ?>"<?php if( $filterYear == date('Y')+1 ) echo ' selected'; ?>><?php echo date('Y')+1; ?></option>
                </select>
            </div>
            <div class="year-glance__greybar-filter-item">
                <label>Level</label>
                <select id="glance-level">
                    <option value=""<?php if( $filterLevel == '' ) echo ' selected'; ?>>Choose Level</option>
                    <option value="1"<?php if( $filterLevel == 1 ) echo ' selected'; ?>>Level 1</option>
                    <option value="2"<?php if( $filterLevel == 2 ) echo ' selected'; ?>>Level 2</option>
                    <option value="3"<?php if( $filterLevel == 3 ) echo ' selected'; ?>>Level 3</option>
                    <option value="4"<?php if( $filterLevel == 4 ) echo ' selected'; ?>>Level 4</option>
                </select>
            </div>
            <div class="year-glance__greybar-filter-item">
                <label>View:</label><br />

                <input type="radio" name="tourtype" id="tourtype--all" value="all"<?php if( $filterPrivate !== 'scheduled' && $filterPrivate !== 'private' ) echo 'checked'; ?>><label class="tourtype__label" for="tourtype--all">All Tours</label>

                <input type="radio" name="tourtype" id="tourtype--scheduled" value="scheduled"<?php if( $filterPrivate === 'scheduled' ) echo 'checked'; ?>><label class="tourtype__label" for="tourtype--scheduled">Scheduled Tours</label>

                <input type="radio" name="tourtype" id="tourtype--private" value="private"<?php if( $filterPrivate === 'private' ) echo 'checked'; ?>><label class="tourtype__label" for="tourtype--private">Private Only</label>
            </div>
            <div class="year-glance__greybar-filter-item">
                <label>&nbsp;</label>
                <div><button class="cta" type="submit" id="glance-apply">Apply</button></div>
            </div>
        </div>
    </div>
</div>
<div class="year-glance-key">
    <div class="year-glance__greybar-key">
        <span>Key:</span>
        <ul>
            <li class="book-now">Book Now</li>
            <li class="limited-space">Limited Space</li>
            <li class="call-to-book">Call to Book</li>
            <li class="sold-out">Sold Out</li>
        </ul>
    </div>
</div>

<div class="year-glance__table l-container">

    <?php

    //$filterYear = ( isset($_GET['gyear']) && !empty($_GET['gyear']) ) ?  $_GET['gyear'] : date('Y');

    //$filterYear = date('Y');
    //$filterYear = "2020";

    // Get all tours for year
    $startDate = $filterYear . "0101";
    $endDate = $filterYear . "1231";

    $defaults = array(
        'post_type' => 'tour',
        'orderby'   => array( 'title' => 'ASC'),
        'posts_per_page' => -1,
        'post_status'      => 'publish',
        /**** NOTE THAT THIS meta_query WILL NOT INCLUDE PRIVATE TOURS ***/
        /*'meta_query'	=> array(
            array(
                'key' => 'd_tour_schedules_%_start_date',
                'compare'	=> 'BETWEEN',
                'type'		=> 'numeric',
                'value'		=> array($startDate, $endDate)
            )
        )*/
    );

    $depth = 2;

    //$queryArgs = array_merge( $defaults );

    // The Query
    $tourList = new WP_Query( $defaults );

    $tourQuery = duvine_organize_tours_by_location_and_sublocation( $tourList->posts, $depth );

    // The Loop
    if ( $tourQuery ) :
        uksort($tourQuery, 'duvine_sort_tours_by_location');
        foreach( $tourQuery as $loc => $tours ) : ?>
            <?php if( $tours ) : ?>
            <?php 
            // skip US tours so we can list as group
            if( duvine_has_US_tour($tours) ||  strtolower( get_the_title($loc) ) === 'california' ) { continue; }

            // if this is Uruguay, list all US tours first
            if( strtolower( get_the_title($loc) ) === 'uruguay' ) duvine_show_US_tours();
            ?>
            <div class="year-glance__region" data-country="<?php echo $loc; // this is the country ?>">
                <h3 class="year-glance__region-name"><?php echo get_the_title($loc); ?></h3>
                <div class="year-glance__region-tours">
                    <?php 
                    $sub_region = null;
                    if( countdim($tours) === 2 ) {
                        // reorder the tour regions alphabetically
                        ksort( $tours );
                    }
                    foreach ( $tours as $index => $tour ): 
                        // does this tour have a sub region? For instance, Tuscany?
                        if( countdim($tours) === 2 ) {
                            foreach ( $tour as $index2 => $tour2 ):

                                // filter for private/schedule
                                if( get_field('d_private_only', $tour2->ID) && $filterPrivate == 'scheduled' ) continue;

                                if( !get_field('d_private_only', $tour2->ID) && $filterPrivate == 'private' ) continue;

                                $tour_regions = get_field("d_tour_location", $tour2->ID);
                                $new_sub_region = (!empty($tour_regions[2])) ? $tour_regions[2] : "";

                                // get tour level from centaur
                                if( get_field('d_private_only', $tour2->ID) ) :
                                    $level = get_field("d_cycling_level", $tour2->ID);
                                    $level = $level['value'];
                                else : 
                                    // not private, so get level from centaur feed
                                    $centaur_id = trim(get_field('d_tour_centaur_id', $tour2->ID));
                                    $centaur_level = duvine_get_tour_level($centaur_id);
                                    $level = ($centaur_level) ? $centaur_level : 0;
                                endif;

                                if( is_numeric($filterLevel) ) :
                                    if( $level != $filterLevel ) continue;
                                endif;
                                if($sub_region !== $new_sub_region): ?>
                                <div class="year-glance__region-dates">
                                    <div class="year-glance__region-dates-sub"><?php echo ($new_sub_region == "") ? "" : get_the_title($new_sub_region); echo " $filterYear"; ?></div>
                                    <?php 
                                    for($month = 1; $month <= 12; $month++): 
                                        $dateObj = DateTime::createFromFormat('!m', $month);
                                        ?>
                                        <div class="year-glance__region-dates-month"><?php echo $dateObj->format('M'); ?></div>
                                    <?php endfor; ?>
                                </div>
                                <?php endif; $sub_region = $new_sub_region; ?>
                                <div class="year-glance__region-tour">
                                    <div class="year-glance__region-tour-info">
                                        <p><a href="<?php the_permalink($tour2->ID); ?>"><strong><?php echo $tour2->post_title; ?></strong></a></p>
                                        <?php
                                        // Get the lowest price for this tour
                                        $price = 99999;
                                        if( get_field('d_private_only', $tour2->ID) ) {
                                            // if private tour, there are no dates, so just use starting price
                                            $price = get_field("d_private_starting_price", $tour2->ID);
                                            $price = apply_filters('format_price', floatval($price));

                                        } else {
                                            if( $centaur_id ) :
                                                $price = duvine_get_minimum_price($centaur_id );
                                            endif;
                                        }
                                        ?>
                                        <p>Price From: <?php echo $price; ?></p>
                                        <p>Level: <?php echo $level; ?></p>
                                        <a href="/print-to-pdf/?tour_id=<?php echo $tour2->ID; ?>" class="year-glance__region-tour-print" target="_blank"></a>
                                    </div>
                                    <?php
                                    // get all dates for this tour from Centaur
                                    $tour_dates = false;
                                    if( !get_field('d_private_only', $tour2->ID) ) :
                                        $tour_dates = duvine_get_tour_dates($tour2);
                                    endif;
                                    if( isset($tour_dates[$filterYear]) ) :
                                        $tour_dates = $tour_dates[$filterYear];
                                    else :
                                        $tour_dates = false;
                                    endif;
                                    
                                    ?>
                                    <?php for($month = 1; $month <= 12; $month++): ?>
                                    <?php
                                    $display_dates = '';
                                    
                                    if( $tour_dates ) :
                                        foreach( $tour_dates as $tour_date ) :
                                            //var_dump($tour_date);
                                            
                                            $tour_month = explode('/',$tour_date['start_date']);
                                            $tour_month = $tour_month[0];
                                            if( $tour_month == $month ) :
                                                $label = explode('&ndash;', $tour_date['label']);
                                                $start_date = $label[0];
                                                //echo 'start: '.$start_date.'<br>';
                                                $start_month = explode(' ',$start_date);
                                                $display_month = date('n', strtotime("$start_month[0] 1 $filterYear"));

                                                //$start_date = $display_month.'/'.$start_month[1];
                                                $start_date = $start_month[1];

                                                //echo 'start: '.$start_date.'<br>';
                                                
                                                $end_date = $label[1];
                                                
                                                // end on a different month?
                                                if( strlen(trim($end_date)) > 2 ) :
                                                    $display_end_month = ($display_month < 12) ? $display_month + 1 : 1;

                                                    $end_day = explode(' ',$end_date);
                                                    $end_day = $end_day[2];

                                                else :
                                                    $display_end_month = '';
                                                    $end_day = trim($end_date);
                                                endif;

                                                $tour_status = duvine_get_tour_date_status($tour_date['flags']);

                                                if( strlen($display_end_month) > 0 ) :
                                                    $display_dates = $display_dates.'<span class="'.$tour_status.'">'.$start_date.' - '.$display_end_month.'/'.$end_day.'</span>';
                                                else :
                                                    $display_dates = $display_dates.'<span class="'.$tour_status.'">'.$start_date.' - '.$end_day.'</span>';
                                                endif;

                                            endif;
                                        endforeach;
                                    endif;
                                    ?>
                                    <div class="year-glance__region-tour-month">
                                        <?php echo $display_dates; ?>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            <?php endforeach;
                        } else {
                            $tour_regions = get_field("d_tour_location", $tour->ID);
                            $new_sub_region = (!empty($tour_regions[2])) ? $tour_regions[2] : "";
                            //$level = get_field("d_cycling_level", $tour->ID);
                            //$level = $level['value'];

                            // filter for private/schedule
                            if( get_field('d_private_only', $tour->ID) && $filterPrivate == 'scheduled' ) continue;

                            if( !get_field('d_private_only', $tour->ID) && $filterPrivate == 'private' ) continue;

                            // get tour level from centaur
                            if( get_field('d_private_only', $tour->ID) ) :
                                $level = get_field("d_cycling_level", $tour->ID);
                                $level = $level['value'];
                            else : 
                                // not private, so get level from centaur feed
                                $centaur_id = trim(get_field('d_tour_centaur_id', $tour->ID));
                                $centaur_level = duvine_get_tour_level($centaur_id);
                                $level = ($centaur_level) ? $centaur_level : 0;
                            endif;

                            if( is_numeric($filterLevel) ) :
                                    if( $level != $filterLevel ) continue;
                                endif;
                            if($sub_region !== $new_sub_region): ?>
                            <div class="year-glance__region-dates">
                                <div class="year-glance__region-dates-sub"><?php echo ($new_sub_region == "") ? "" : get_the_title($new_sub_region); echo " $filterYear"; ?></div>
                                <?php 
                                for($month = 1; $month <= 12; $month++): 
                                    $dateObj = DateTime::createFromFormat('!m', $month);
                                    ?>
                                    <div class="year-glance__region-dates-month"><?php echo $dateObj->format('M'); ?></div>
                                <?php endfor; ?>
                            </div>
                            <?php endif; $sub_region = $new_sub_region; ?>
                            <div class="year-glance__region-tour">
                                <div class="year-glance__region-tour-info">
                                    <p><a href="<?php the_permalink($tour->ID); ?>"><strong><?php echo $tour->post_title; ?></strong></a></p>
                                    <?php
                                    // Get the lowest price for this tour
                                    $price = 99999;
                                    if( get_field('d_private_only', $tour->ID) ) {
                                        // if private tour, there are no dates, so just use starting price
                                        $price = get_field("d_private_starting_price", $tour->ID);
                                        $price = apply_filters('format_price', floatval($price));

                                    } else {
                                        if( $centaur_id ) :
                                            $price = duvine_get_minimum_price($centaur_id );
                                        endif;
                                    }
                                    ?>
                                    <p>Price From: <?php echo $price; ?></p>
                                    <p>Level: <?php echo $level; ?></p>
                                    <a href="/print-to-pdf/?tour_id=<?php echo $tour->ID; ?>" class="year-glance__region-tour-print" target="_blank"></a>
                                </div>
                                <?php
                                // get all dates for this tour from Centaur
                                $tour_dates = false;
                                if( !get_field('d_private_only', $tour->ID) ) :
                                    $tour_dates = duvine_get_tour_dates($tour);
                                endif;
                                if( isset($tour_dates[$filterYear]) ) :
                                    $tour_dates = $tour_dates[$filterYear];
                                else :
                                    $tour_dates = false;
                                endif;
                                
                                ?>
                                <?php for($month = 1; $month <= 12; $month++): ?>

                                <?php
                                $display_dates = '';
                                
                                if( $tour_dates ) :
                                    foreach( $tour_dates as $tour_date ) :
                                        //var_dump($tour_date);
                                        
                                        $tour_month = explode('/',$tour_date['start_date']);
                                        $tour_month = $tour_month[0];
                                        if( $tour_month == $month ) :
                                            $label = explode('&ndash;', $tour_date['label']);
                                            $start_date = $label[0];
                                            //echo 'start: '.$start_date.'<br>';
                                            $start_month = explode(' ',$start_date);
                                            $display_month = date('n', strtotime("$start_month[0] 1 $filterYear"));

                                            //$start_date = $display_month.'/'.$start_month[1];
                                            $start_date = $start_month[1];

                                            //echo 'start: '.$start_date.'<br>';
                                            
                                            $end_date = $label[1];
                                            
                                            // end on a different month?
                                            if( strlen(trim($end_date)) > 2 ) :
                                                $display_end_month = ($display_month < 12) ? $display_month + 1 : 1;

                                                $end_day = explode(' ',$end_date);
                                                $end_day = $end_day[2];

                                            else :
                                                $display_end_month = '';
                                                $end_day = trim($end_date);
                                            endif;

                                            $tour_status = duvine_get_tour_date_status($tour_date['flags']);

                                            if( strlen($display_end_month) > 0 ) :
                                                $display_dates = $display_dates.'<span class="'.$tour_status.'">'.$start_date.' - '.$display_end_month.'/'.$end_day.'</span>';
                                            else :
                                                $display_dates = $display_dates.'<span class="'.$tour_status.'">'.$start_date.' - '.$end_day.'</span>';
                                            endif;

                                        endif;
                                    endforeach;
                                endif;
                                ?>
                                <div class="year-glance__region-tour-month">
                                    <?php echo $display_dates; ?>
                                </div>
                                <?php endfor; ?>
                            </div>
                        <?php }

                    endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php endforeach;
    endif; ?>
</div>
<?php } // end password protection ?>
