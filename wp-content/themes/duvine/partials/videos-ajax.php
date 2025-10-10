<article class="Video Detail">
        <div class="Clear">
            <?php
            $youtube_url = get_post_meta(get_the_ID(), 'duvine_video_youtube', true);
            if( !empty( $youtube_url ) )  { ?>

                <div id="youtube-player" data-url="<?php echo esc_url( $youtube_url ); ?>"></div>

            <?php } else { ?>


                <div id="video" style="width: 720px; height: 405px;"><div id="videoContainer"></div></div>
                <script>
                var flashVars = {
                    url : "<?php echo get_post_meta( get_the_ID(), 'duvine_video_asset_url', true ); ?>",
                    playImage : "<?php echo get_template_directory_uri(); ?>/assets/images/icons/play_large.png",
                    completeDelegate : "onVideoComplete",
                    autoPlay : "true"
                };
                var params = {
                    wmode : "direct",
                    bgcolor : "#000000",
                    allowFullScreen : "true"
                };
                swfobject.embedSWF("<?php echo get_template_directory_uri(); ?>/assets/flash/MediaPlayer.swf", "videoContainer", "720", "405", "10.0.18", "false", flashVars, params, "false");
                </script>

            <?php } ?>

        </div><!--/.Clear-->

    </article><!--/.Video Detail-->
