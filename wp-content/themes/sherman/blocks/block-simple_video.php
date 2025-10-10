<?php 
/**
 * MODULE - Simple Video
 *
 * Just a video...mp4 expected
 *
 * @subfield video (file - mp4)
 * @subfield video_poster (file - image)
 *
 */
?>

<div class="l-container l-container--small">
    <div class="simplevideo">
        <?php if(get_sub_field('video')) : ?>
            <div class="simplevideo__video">
                <video data="<?php echo get_sub_field('video'); ?>" playsinline loop controls id="vid1"  onended="this.play();" poster="<?php the_sub_field('video_poster'); ?>">
                    <source src="<?php echo get_sub_field('video'); ?>" type="video/mp4">
                 </video>
            </div>
        <?php endif; ?>
    </div>
</div>