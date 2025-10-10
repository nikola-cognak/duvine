<?php 
//
// MODULE - Tour Browser
//
// Browse for tours
//


$q_destinations  = get_query_var( 'destination' );         // d_tour_location
$q_levels        = get_query_var( 'level' );               // d_cycling_level
$q_collections   = get_query_var( 'collection' );          // d_tour_collection
$q_tax_landscape = get_query_var( 'taxfilter_landscape' ); // d_tax_landscape
$q_tax_activity  = get_query_var( 'taxfilter_activity' );  // d_tax_activities
$q_tax_culinary  = get_query_var( 'taxfilter_culinary' );  // d_tax_culinary_experiences
$q_tax_culture   = get_query_var( 'taxfilter_culture' );
$q_morefilters = get_query_var( 'morefilters' );
$q_dates         = get_query_var( 'date' );
$q_tourtype      = get_query_var( 'tourtype' );
$q_duration      = get_query_var( 'duration' );

$otherFiltersCount = 0;

//if( $q_tax_landscape ){
//    $otherFiltersCount += count( $q_tax_landscape);
//}
//if( $q_tax_activity ){
//    $otherFiltersCount += count( $q_tax_activity);
//}
//if( $q_tax_culinary ){
//    $otherFiltersCount += count( $q_tax_culinary);
//}
//if( $q_tax_culture ){
//    $otherFiltersCount += count( $q_tax_culture);
//}
if ($q_morefilters) {
    $otherFiltersCount += count($q_morefilters);
}
?>

<div class="l-container tourloop">
    <a class="tourfilter__mobiletrigger" href="#">Filters</a>
    <form class="tourfilters" action="<?php echo get_permalink(); ?>">
        <section class="duvinefilters">

            <a href="#" class="filtercell__closefilters js-close-mobilefilters tour-finder-mobile"></a>
            <div class="tourfilters__container tourfilters__container--pad">
                <h2 class="superheader headerpromo__title tour-finder-mobile">Filters</h2>
                <div class="privatefilter tour-finder-mobile">
                    <label class="tourtype__label" for="tourtype--scheduled"><input type="checkbox" name="tourtype[]" id="tourtype--scheduled" value="scheduled"<?php if( $q_tourtype === 'scheduled' || (is_array($q_tourtype) && in_array('scheduled', $q_tourtype)) ) echo 'checked'; ?>><span>Scheduled Tours</span></label>

                    <label class="tourtype__label" for="tourtype--private"><input type="checkbox" name="tourtype[]" id="tourtype--private" value="private"<?php if( $q_tourtype === 'private' || (is_array($q_tourtype) && in_array('private', $q_tourtype))) echo 'checked'; ?>><span>Private Only</span></label>

                    <label class="tourtype__label" for="tourtype--new"><input type="checkbox" name="tourtype[]" id="tourtype--new" value="new"<?php if( $q_tourtype === 'new' || (is_array($q_tourtype) && in_array('new', $q_tourtype))) echo 'checked'; ?>><span>New Tours</span></label>
                </div>

                <?php if( $locations = duvine_get_location_list() ) : ?>
                    <div class="filtercell filtercell--destinations">
                        <span class="filtercell__label">Destinations</span>
                        
                        <select data-placeholder="Choose country or region" class="js-duvine-chosen destination-desktop" data-search="1" data-noresults="No matches here. Try selecting from the list instead." name="destination[]" multiple>
                            <?php foreach( $locations as $location ) : ?>
                                <?php $selected = $q_destinations && in_array( $location['id'], $q_destinations ) ? ' selected' : ''; ?>
                                <option value="<?php echo $location['id']; ?>" data-markup="<?php echo $location['label']; ?>"<?php echo $selected; ?>><?php echo $location['key']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php echo duvine_get_location_list_mobile();?>
                    </div>
                <?php endif; ?>



                <div class="filtercell filtercell--dates">
                    <span class="filtercell__label">Dates</span>
                    <?php duvine_date_range_input(); ?>
                </div>
                
                <?php $tooltip_activity_level = duvine_get_tooltip( 'd_tooltip_activity_level', 'dark' ); ?>

                <div class="filtercell filtercell--level">
                    <span class="filtercell__label">Level <span class="tourmeta"><?php echo $tooltip_activity_level; ?></span></span>
                    <!--<span class="filtercell__label">Level</span>-->
                    <select data-placeholder="Select difficulty" class="js-duvine-chosen" multiple name="level[]">
                        <option value="1"<?php if( $q_levels && in_array(1, $q_levels) ) echo ' selected'; ?>>Level 1</option>
                        <option value="2"<?php if( $q_levels && in_array(2, $q_levels) ) echo ' selected'; ?>>Level 2</option>
                        <option value="3"<?php if( $q_levels && in_array(3, $q_levels) ) echo ' selected'; ?>>Level 3</option>
                        <option value="4"<?php if( $q_levels && in_array(4, $q_levels) ) echo ' selected'; ?>>Level 4</option>
                        <option value="0"<?php if( $q_levels && in_array(0, $q_levels) ) echo ' selected'; ?>>Non-rider option</option>
                    </select>
                </div>

                <?php
                    $tourCollections = get_posts( array(
                        'post_type'      => 'tour_collection',
                        'posts_per_page' => -1,
                        'post_parent'    => 0,
                        'orderby'        => array( 'menu_order' => 'ASC' )
                    ));
                ?>
                <?php if( $tourCollections) : ?>
                    <div class="filtercell filtercell--collection filtercell-left">
                        <span class="filtercell__label">Tour Collections</span>

                        <select data-placeholder="Select collection" class="js-duvine-chosen" multiple name="collection[]">
                            <?php foreach( $tourCollections as $collection ) : ?>
                                <?php $selected = $q_collections && in_array( $collection->ID, $q_collections ) ? ' selected' : ''; ?>
                                <option value="<?php echo $collection->ID; ?>"<?php echo $selected; ?>><?php echo $collection->post_title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="filtercell filtercell--duration filtercell-right">
                    <span class="filtercell__label">Duration</span>

                    <select data-placeholder="Select length of time" class="js-duvine-chosen" multiple name="duration[]">
                        <option value="4" <?php if( $q_duration && in_array(4, $q_duration) ) echo ' selected'; ?>>4-day tours</option>
                        <option value="6" <?php if( $q_duration && in_array(6, $q_duration) ) echo ' selected'; ?>>6-day+ tours</option>
                    </select>
                </div>

              <div class="filtercell--morefilters tour-finder-mobile">
                <div class="filtercell filtercell--activity filtercell-left">
                  <?php $activityTag = duvine_get_tour_tags('activity', 'Activities'); ?>
                  <span class="filtercell__label">Activities</span>
                  <select data-placeholder="Select activities" class="js-duvine-chosen" multiple name="morefilters[]">
                    <?php foreach ($activityTag as $tag): ?>
                      <option value="<?php echo $tag['value']; ?>"<?php echo $tag['isChecked'] ? 'selected' : ''; ?>><?php echo $tag['label']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="filtercell filtercell--culinary filtercell-right">
                  <?php $culinaryTag = duvine_get_tour_tags('culinary', 'Culinary'); ?>
                  <span class="filtercell__label">Culinary</span>
                  <select data-placeholder="Select culinary" class="js-duvine-chosen" multiple name="morefilters[]">
                    <?php foreach ($culinaryTag as $tag): ?>
                      <option value="<?php echo $tag['value']; ?>"<?php echo $tag['isChecked'] ? 'selected' : ''; ?>><?php echo $tag['label']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="filtercell filtercell--culture filtercell-left">
                  <?php $cultureTag = duvine_get_tour_tags('culture', 'Cultural'); ?>
                  <span class="filtercell__label">Cultural</span>
                  <select data-placeholder="Select cultural" class="js-duvine-chosen" multiple name="morefilters[]">
                    <?php foreach ($cultureTag as $tag): ?>
                      <option value="<?php echo $tag['value']; ?>"<?php echo $tag['isChecked'] ? 'selected' : ''; ?>><?php echo $tag['label']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="filtercell filtercell--landscape filtercell-right">
                  <?php $landscapeTag = duvine_get_tour_tags('landscape', 'Landscape'); ?>
                  <span class="filtercell__label">Landscape</span>
                  <select data-placeholder="Select landscape" class="js-duvine-chosen" multiple name="morefilters[]">
                    <?php foreach ($landscapeTag as $tag): ?>
                      <option value="<?php echo $tag['value']; ?>"<?php echo $tag['isChecked'] ? 'selected' : ''; ?>><?php echo $tag['label']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

                <div class="filtercell filtercell--morefilters js-trigger-advancedfilters">
                    <span class="filtercell__label">More Filters</span>
                        
                    <select data-placeholder="+ More" class="js-duvine-chosen more-filters" data-search="1" data-noresults="" name="morefilters[]" multiple>
                        <?php duvine_list_tour_tags('activity', 'Activities'); ?>
                        <?php duvine_list_tour_tags('culinary', 'Culinary'); ?>
                        <?php duvine_list_tour_tags('culture', 'Cultural'); ?>
                        <?php duvine_list_tour_tags('landscape', 'Landscape'); ?>
                    </select>



                    <!--<span class="tourfilters__filtercount<?php if( $otherFiltersCount > 0 ) echo ' tourfilters__filtercount--visible'; ?>"><?php echo $otherFiltersCount; ?></span>
                    
                    <a href="#" class="tourfilters__seemore js-trigger-advancedfilters">More</a>-->
                </div>

                <div class="tourfilters__apply tour-finder-mobile">
                    <button class="cta" type="submit">Show Results</button>
                    <button class="cta js-clear-filters">Clear Filters</button>
                </div>
                <div class="tourfilters__apply">
                    <a href="#" class="basiclink filtercell__clearfilters js-clear-filters">Clear Filters</a>
                    <a href="#" class="basiclink filtercell__closefilters js-close-mobilefilters">Cancel</a>
                    <button class="cta" type="submit">Apply</button>
                </div>

            </div>

            
            <!--<div class="tourfilters__expandable">
                <div class="tourfilters__expandablepad">
                    <div class="tourfilters__container">
                        <?php //duvine_list_tour_tags('activity', 'Activities'); ?>
                        <?php //duvine_list_tour_tags('culinary', 'Culinary'); ?>
                        <?php //duvine_list_tour_tags('culture', 'Cultural'); ?>
                        <?php //duvine_list_tour_tags('landscape', 'Landscape'); ?>
                    </div>

                </div>
            </div>-->
        </section>

        <section class="tourloop__header">
            <div class="privatefilter">

                <!--<span class="privatefilter__label">View:</span>-->

                <!--<label class="tourtype__label active" for="tourtype--all"><input type="checkbox" name="tourtype[]" id="tourtype--all" value="all"<?php /*if( $q_tourtype !== 'scheduled' && $q_tourtype !== 'private' ) echo 'checked'; */?>><span>All Tours</span></label>-->

                <label class="tourtype__label" for="tourtype--scheduled"><input type="checkbox" name="tourtype[]" id="tourtype--scheduled" value="scheduled"<?php if( $q_tourtype === 'scheduled' || (is_array($q_tourtype) && in_array('scheduled', $q_tourtype)) ) echo 'checked'; ?>><span>Scheduled Tours</span></label>

                <label class="tourtype__label" for="tourtype--private"><input type="checkbox" name="tourtype[]" id="tourtype--private" value="private"<?php if( $q_tourtype === 'private' || (is_array($q_tourtype) && in_array('private', $q_tourtype))) echo 'checked'; ?>><span>Private Only</span></label>

                <label class="tourtype__label" for="tourtype--new"><input type="checkbox" name="tourtype[]" id="tourtype--new" value="new"<?php if( $q_tourtype === 'new' || (is_array($q_tourtype) && in_array('new', $q_tourtype))) echo 'checked'; ?>><span>New Tours</span></label>


<!--                <input type="radio" name="tourtype" id="tourtype--all" value="all"<?php /*if( $q_tourtype !== 'scheduled' && $q_tourtype !== 'private' ) echo 'checked'; */?>><label class="tourtype__label" for="tourtype--all">All Tours</label>

                <input type="radio" name="tourtype" id="tourtype--scheduled" value="scheduled"<?php /*if( $q_tourtype === 'scheduled' ) echo 'checked'; */?>><label class="tourtype__label" for="tourtype--scheduled">Scheduled Tours</label>

                <input type="radio" name="tourtype" id="tourtype--private" value="private"<?php /*if( $q_tourtype === 'private' ) echo 'checked'; */?>><label class="tourtype__label" for="tourtype--private">Private Only</label>

                <input type="radio" name="tourtype" id="tourtype--new" value="new"<?php /*if( $q_tourtype === 'new' ) echo 'checked'; */?>><label class="tourtype__label" for="tourtype--new">New Tours</label>-->
            </div>


            <div class="sortfilter">
                <!--<span>SORT BY</span>-->
                <select data-placeholder="Sort By" class="js-duvine-chosen js-duvine-sortfilters">
                    <option value="title">Tour Name</option>
                    <option value="price">Price (low to high)</option>
                </select>
            </div>
        </section>
    </form>

    <?php
    $current_url = $_SERVER['REQUEST_URI'];
    $is_tour_finder = ( strpos($current_url, 'tour-finder') || strpos($current_url, 'action=filter_tourloop') || strpos($current_url, 'action=lazyload_tours') ) ? true : false;
    ?>
    <section class="tourloop__section mobile-filters">
        <div class="tourloop__holder<?php if( $is_tour_finder ) echo ' tour-finder'; ?>">
            <?php duvine_render_tour_loop( array('duvine_show_count' => true, 'duvine_show_you_may_also_like' => true )); ?>
        </div>
    </section>

</div>
