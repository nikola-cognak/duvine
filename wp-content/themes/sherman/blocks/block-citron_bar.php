<?php 
/**
 * MODULE - Bold Statement
 *
 * Block with large text and a call to action
 *
 * @subfield statement (text area)
 * @subfield call_to_action (clone)
 *
 */
?>


<div class="home-award">
	<?php
		$award_text = sk_get_subfield('content', array(
			'before' => '<div class="boldstatement__statement">',
			'after'  => '</div>'
		));

		$award_image = sk_get_subfield('logo', array(
			'before' => '<div class="">',
			'after'  => '</div>',
			'filter' => 'sk_img_markup'
		));

		if( $award_text || $award_image ){
			echo '<div class="content">';
			echo $award_image;
			echo $award_text;
			echo '</div>';
		}
	?>
</div>