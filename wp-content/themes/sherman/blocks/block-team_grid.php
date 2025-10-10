<?php
//
// MODULE - Team Grid
//
// Renders team members in a grid, depending on the role that has been
//  selected, if any.
//

$roles = get_sub_field('role');

$teamArgs = array(
    'post_type'   => 'person',
    'orderby'     => 'title',
    'order'       => 'ASC',
    'numberposts' => -1,
);

if( $roles ){
    $teamArgs['tax_query'] = array(
        array(
            'taxonomy' => 'role',
            'field'    => 'term_id',
            'terms'    => $roles
        ),
    );
}


$people = get_posts( $teamArgs );

?>

<?php if( $people ) : ?>

    <div class="tourguides l-container">

        <div class="tourguides__grid d-column-container">
    
            <?php foreach( $people as $person ) : ?>
                <div class="tourguide d-col d-col--1-4">
                    <a href="<?php echo get_permalink($person); ?>">
                        <div class="tourguide__headshot">
                            <?php 
                                sk_the_field('duvine_headshot', array(
                                    'id'          => $person->ID,
                                    'default'     => '<div class="tourguide__headshotplaceholder"></div>',
                                    'filter'      => 'sk_img_markup',
                                    'filter_args' => array(
                                        'img_size' => 'gridcell_image'
                                    )
                                ));
                            ?>
                        </div>

                        <h3 class="tourguide__name tertiaryheader"><?php echo $person->post_title; ?></h3>
                        
                        <?php 
                            if( !in_array(58, $roles) ){
                                sk_the_field('duvine_person_title', array(
                                    'id'     => $person->ID,
                                    'before' => '<p class="person__jobtitle">',
                                    'after'  => '</p>'
                                ));
                            }
                        ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
