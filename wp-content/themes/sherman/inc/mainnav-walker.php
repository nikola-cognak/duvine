<?php
/* ========================================================
 *
 * Mainnav walker
 *
 * ======================================================== */



if( !function_exists('duvine_main_menu_start_el')) :
/**
 * Adds markup to the anchor tag in the menu
 *
 * @param string  $output  The default markup
 * @param object  $item    Menu object from WP
 * @param int?    $depth
 * @param array?  $args
 * @return string
 */
function duvine_main_menu_start_el( $output, $item, $depth, $args ){

    // If we're on most of the nav links, just return the output
    if( !isset( $args->duvine_location ) || $args->duvine_location !== 'header' || $item->attr_title !== 'browse-tours' ){
        return $output;
    }

    $locationlist = duvine_get_hierarchical_location_list();

    if( ! $locationlist ){
        return false;
    }

    $private_nav = duvine_get_browsetours_subnav($item->ID, $args->menu->term_id);

    $dropdown = '';

    ob_start();
?>
    <div class="sub-menu tournav__dropdown">
        <div class="l-container ">
            <div class="d-column-container">
                <div class="d-col d-col--60 tournav__column tournav__column--pad-right">
                    <header><h4 class="tournav__colheader">Destinations</h4></header>
                    
                    <ul class="tournav__menu">
                        <?php foreach( $locationlist as $index => $continent ) : ?>
                            <li class="tourmenu__hoverable tourmenu__continent tourmenu__continent--<?php echo $continent['slug']; ?><?php if( $index === 0 ) echo ' tourmenu__hoverable--active'; ?>">
                                <span class="tourmenu__continentlabel"><a href="<?php echo get_the_permalink($continent['id']); ?>"><?php echo $continent['continent']; ?></a></span>
                                <?php if( $continent['countries'] ) : ?>
                                    <div class="tourmenu__countries"><div class="tourmenu__countrywrapper l-cf" style="display: flex; flex-direction: column;">

                                        <?php if( $continent['slug'] === 'europe' ) : ?>
                                            <div class="tourmenu__europelist" style="width: 100%; padding-right: 0px;" >
                                    <ul class="tourmenu__countrylist blockmenu"
                                        style="display: grid; grid-template-columns: 1.5fr 1fr; grid-template-rows: repeat(2, 1fr);grid-auto-flow: column; border-bottom: 1px solid #666;">
                                                    <?php foreach( $continent['countries'] as $country_index => $country ) : ?>
                                                        <?php
                                                             $permalink = get_the_permalink($country->ID);
                                                             $countryCount = ceil(count($continent['countries']) / 2) - 2 ?? 0;
                                                         ?>
                                                        <li><a href="<?php echo $permalink; ?>"><?php echo $country->post_title; ?></a></li>
                                                        
                                                        <?php if( $country_index === 3 ) : ?>
                                                            </ul>
                                                            
                                                            
                                                            </div>

                                                            <div class="tourmenu__europelist tourmenu__europelist--2ndcolumn" style="width: 100%;padding-left: 0px; margin-top: 10px; padding-right: 0px;">
                                    <ul class="tourmenu__countrylist blockmenu"
                                        style="display: grid; grid-template-columns: 1.5fr 1fr; grid-template-rows: repeat(<?= $countryCount ?>, 1fr); grid-auto-flow: column; border-bottom: 1px solid #666;">

                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                <div class="tourmenu__countrylandingpagecta" style="padding-right: 0px; border-top: none;">
                                
                                        <a class="tourmenu__countrylandingpage" href="<?php echo get_the_permalink($continent['id']); ?>">View all of <?php echo $continent['continent']; ?></a>
                                    </div>
                                        <?php else : ?>
                                            <ul class="tourmenu__countrylist blockmenu">
                                                <?php foreach( $continent['countries'] as $country ) : ?>
                                                    <?php $permalink = get_the_permalink($country->ID); ?>
                                                    <li><a href="<?php echo $permalink; ?>"><?php echo $country->post_title; ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                            
                                            <?php if ( strtolower($continent['continent']) !== 'new zealand' ) : ?>
                                            <div class="tourmenu__countrylandingpagecta">
                                                <a class="tourmenu__countrylandingpage" href="<?php echo get_the_permalink($continent['id']); ?>">View all of <?php echo $continent['continent']; ?></a>
                                            </div>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div></div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="d-col d-col--40 tournav__column">
                    <header><h4 class="tournav__colheader">Collections</h4></header>
                    <?php
                        $collections = get_posts( array(
                            'post_type'      => 'tour_collection',
                            'posts_per_page' => -1,
							's' => '-Villas',
                            'orderby'        => 'menu_order',
                            'post_parent'    => 0
                        ));
                    ?>
                    <?php if( $collections ) : ?>
                        <ul class="tournav__menu collection__nav">
                            <?php foreach( $collections as $coll ) : ?>
                                <li>
                                    <a class="tourmenu__collection" href="<?php echo get_the_permalink( $coll->ID ); ?>">
                                        <?php
                                            echo '<span class="tourmenu__collectionhover">' . $coll->post_title . '</span>';

                                            sk_the_field('d_tour_collection_excerpt', array(
                                                'id'     => $coll->ID,
                                                'before' => '<span class="tourcollection__excerpt">',
                                                'after'  => '</span>'
                                            ));
                                        ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
						<ul class="menu">
							
					</ul>
						<header><h4 class="tournav__colheader">Private + Custom</h4></header>
						<ul class="tournav__menu collection__nav" style="display:block;">
							<li> <a class="cta cta--hoverwhite" href="/private-tours/">Go Private</a></li>
							<li><a class="tourmenu__collection" href="/collection/villas/"> <span class="tourmenu__collectionhover">Villas</span><span class="tourcollection__excerpt">Full-service group travel, simplified</span> </a></li>
						</ul>
                </div>

                
            </div>
        </div>
    </div><!-- .tournav__dropdown -->
<?php

    $dropdown = ob_get_clean();

    return $output . $dropdown;
}
add_filter('walker_nav_menu_start_el', 'duvine_main_menu_start_el', 10, 4);
endif; // duvine_main_menu_start_el






if(!function_exists('duvine_get_browsetours_subnav')) :
/**
 * Gets the subnav items
 */
function duvine_get_browsetours_subnav($id, $menu){

    //delete_transient('duvine_browsetours_subnav');
    $subnav = get_transient('duvine_browsetours_subnav');

    if($subnav !== false){
        return $subnav;
    }

    $subnav = array();

    $menu_items = wp_get_nav_menu_items($menu);

    if($menu_items){
        foreach($menu_items as $item){
            if($item->menu_item_parent == $id){
                array_push($subnav, $item);
            }
        }
    }

    set_transient('duvine_browsetours_subnav', $subnav, 60*60*2);
    return $subnav;
}
endif; // duvine_get_browsetours_subnav