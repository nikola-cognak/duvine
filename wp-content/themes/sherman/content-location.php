<?php
/**
 * @package sherman
 */

// get the ancestors of this location
$ancestors = get_ancestors( get_the_ID(), 'location', 'post_type');

// get children locations
$children = get_posts( array(
    'post_type'      => 'location',
    'posts_per_page' => -1,
    'post_parent'    => get_the_ID(),
    'orderby'       => 'title',
    'order'         => 'ASC'
));

$meta_query_countries = array(
    'relation' => 'OR',
    array(
        'key'     => 'd_tour_location',
        'value'   => '"' . get_the_ID() . '"',
        'compare' => 'LIKE'
    )
);

if( $children ){
    foreach ($children as $child) {
        array_push($meta_query_countries, array(
            'key'     => 'd_tour_location',
            'value'   => '"' . $child->ID . '"',
            'compare' => 'LIKE'
        ));
    }
}

// set up arguments to query the tours for this location
$tourArgs = array(
    'posts_per_page'       => -1,
    'meta_query'           => array( $meta_query_countries ),
    'duvine_show_loadmore' => false,
);

$svgMap = get_template_directory() . '/svg-regions/map-' . $post->post_name . '.php';

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <?php if( file_exists($svgMap) ) : ?>
        
        <div class="location__hero interactive-location">
            <div class="location__herosvg"><?php include($svgMap); ?></div>
            <?php if( $children ) : 
                $i = 0; 
                ?>
                <div class="sublocations">
               
                    <h5 class="sublocations__title"><?php echo $post->post_name === 'europe' ? 'Regions' : 'Regions'; ?> in <?php the_title(); ?></h5>
                    <div class="locationkids-wrapper">
                    <?php if (count($children) > 9) { //echo '<div class="locationkids-wrapper">'; 
                       $half = round(count($children) / 2) - 1;
                    } ?>
                    <ul class="blockmenu locationkids">
                        <?php foreach( $children as $kid ) : ?>
                            <?php $permalink = get_the_permalink( $kid->ID ); ?>
                            <li class="locationkids__kid"><a class="locationkids__kidanchor js-locmap-trigger" href="<?php echo $permalink; ?>" data-region="<?php echo $kid->post_name; ?>"><?php echo $kid->post_title; ?></a></li>
                        <?php 
                        if (count($children) > 9 && $i == $half) {
                            echo '</ul><ul class="blockmenu locationkids">';
                        }
                        $i++;
                    endforeach; ?>
                    </ul>
                    <?php // if (count($children) > 9) { echo '</div>'; } ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php elseif($herovideo = get_field('d_hero_video')) : ?>
		<div class="location__hero">
			<div style="padding:56.25% 0 0 0;position:relative;width:100%"><iframe src="https://player.vimeo.com/video/<?php echo $herovideo; ?>?h=0fb282a0ab&title=0&byline=0&portrait=0&background=1&muted=1&controls=0&autoplay=1&loop=1" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div>
        </div>
    <?php elseif ($hero = duvine_get_url_from_object(get_field('d_hero_image'), 'banner_hero_page')) : ?>
		<div class="location__hero">
            <div class="location__heroimage" style="background-image: url(<?php echo $hero; ?>)"></div>
        </div>
	<?php endif; ?>


    <div class="l-container l-container--small location__main" id="<?php echo str_replace(' ', '-', get_the_title()); ?>">
        <header class="location__header">
            <div class="breadcrumbs__pad">
                <?php duvine_region_breadcrumbs(); ?>
            </div>
            <?php the_title( '<h1 class="superheader">', ' Bike Tours</h1>' ); ?>
            
        </header>
        
        <?php if( $post->post_content ) : ?>
            <div class="content__lead">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>

        <?php $loc_drink  = get_field('d_location_drink'); ?>
        <?php $loc_eat    = get_field('d_location_eat'); ?>
        <?php $loc_locals = get_field('d_location_locals_love'); ?>

        <?php if( $loc_drink || $loc_eat || $loc_locals ) : ?>
            <section class="content__secondary d-column-container">

                <?php if( $loc_locals ) { ?>
                
                    
                    <div class="location__entry d-col d-col--1-2">

                        <?php if( $loc_eat ) : ?>
                            <h3 class="blockheader">Eat</h3>
                            <div class="d-content d-content--padtop">
                                <?php echo $loc_eat; ?>
                            </div>
                        <?php endif; ?>


                        <?php if( $loc_drink ) : ?>
                            <h3 class="blockheader <?php if($loc_eat) { echo 'd-content--padtop'; } ?>">Drink </h3>
                            <div class="d-content d-content--padtop">
                                <?php echo $loc_drink; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="location__entry d-col d-col--1-2">
                        <h3 class="blockheader">What Locals Love</h3>
                        <div class="d-content d-content--padtop">
                            <?php echo $loc_locals; ?>
                        </div>
                    </div>

                <?php } else { ?>
                    
                    <?php if( $loc_eat ) : ?>
                        <div class="location__entry d-col d-col--1-2">
                            <h3 class="blockheader">Eat</h3>
                            <div class="d-content d-content--padtop">
                                <?php echo $loc_eat; ?>
                            </div>
                        </div>
                    <?php endif; ?>


                    <?php if( $loc_drink ) : ?>
                        <div class="location__entry d-col d-col--1-2">
                            <h3 class="blockheader">Drink</h3>
                            <div class="d-content d-content--padtop">
                                <?php echo $loc_drink; ?>
                            </div>
                        </div>
                    <?php endif; ?>


                <?php } ?>
            
            </section>
        <?php endif; ?>
		<hr class="wp-block-separator has-text-color has-alpha-channel-opacity has-background is-style-wide" style="background-color:#dddddd;color:#dddddd;margin-bottom: 0">
    </div>
	

    <div class="l-container">
        <section class="tourloop__section tourloop">
            <?php if( $children ) : ?>
                <?php duvine_render_name_grouped_tourloop($tourArgs, count($ancestors) + 2); ?>
            <?php else : ?>
                <?php duvine_render_name_grouped_tourloop($tourArgs); ?>
            <?php endif; ?>
            <?php if( $tourfinder_page = get_field('d_tourfinder_page', 'option') ) : ?>
                <div class="tourloop__footersection t-center"><a href="<?php echo $tourfinder_page['url']; ?>" class="cta">See All DuVine Tours</a></div>
            <?php endif; ?>
        </section>
    </div>

</article><!-- #post-## -->
