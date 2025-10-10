<?php
$type = get_the_title();

if( $type == 'Private' || $type == 'Couture' || $type == 'Family' ) {
    $action_text = "Start Planning";
    
    $action_url = get_post_meta( $post->ID, 'duvine_tour_type_link_url', true );
    if( !$action_url ){
        $action_url = '/tours/' . strtolower($type) . '/planning';//TODO: add URL
    }

    // the link on the first arrow
    $first_arrow_cta = '/tours/' . strtolower($type) . '/planning';

    if( $type == 'Family' ){
        $first_arrow_cta = '/tours/private/planning';
    }


    $browse_text = "Schedule a Call";
    $browse_url = "/schedule-a-call";

    $overlay_link = true;
} else {
    $action_text = "$type Tour Dates";
    
    $action_url = get_post_meta( $post->ID, 'duvine_tour_type_link_url', true );

    if( !$action_url ){
        $action_url = "/tours?type={$post->ID}";
    }

    $first_arrow_cta = "/tours?type={$post->ID}";



    $browse_text = "Browse By Region";
    $browse_url = "/region?tourType=" . strtolower($type);

    $overlay_link = false;
}
?>

<div class="Column">
    <div>
        <a href="<?php echo $action_url?>">
            <?php the_post_thumbnail(); ?>
            <span class="Tour-Name Bg-Tour-<?php echo $type ?>"><?php echo $type ?></span>
        </a>
    </div>
    <p class="Abstract"><?php echo get_post_meta($post->ID, 'duvine_tour_type_overview_image_text', true); ?></p>
    <a href="<?php echo $first_arrow_cta?>" class="Button Tour-<?php echo $type ?> Discover"><span><?php echo $action_text ?></span></a><br><br>
    <a href="<?php echo $browse_url?>" class="Button Tour-<?php echo $type ?> Discover<?php if( $overlay_link ) {echo ' Overlay-Link';}?>"<?php if( $overlay_link ) {echo ' rel=".Overlay"';}?>><span><?php echo $browse_text ?></span></a>
</div>