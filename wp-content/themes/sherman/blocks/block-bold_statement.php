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


<div class="boldstatement l-container">
    <?php
        // Check if we're on the homepage
        $is_homepage = is_front_page() || is_home();
        
        // Set the appropriate tag based on homepage status
        $statement_tag = $is_homepage ? 'h1' : 'div';
        $statement_class = $is_homepage ? 'boldstatement__statement boldstatement__statement--h1' : 'boldstatement__statement';
        
        if ($is_homepage) {
            // For homepage (h1), get raw content and strip paragraph tags
            $statement_content = sk_get_subfield('statement');
            $statement_content = strip_tags($statement_content, '<br><strong><em><span>'); // Allow only safe inline tags
            
            echo '<' . $statement_tag . ' class="' . $statement_class . '">';
            echo '<strong>DuVine Cycling + Adventure Co.</strong></br>'.$statement_content;
            echo '</' . $statement_tag . '>';
        } else {
            // For other pages (div), use original method
            sk_block_field('statement', array(
                'before' => '<' . $statement_tag . ' class="' . $statement_class . '">',
                'after'  => '</' . $statement_tag . '>',
                'filter' => 'smart_type'
            ));
        }

        duvine_render_cta();
    ?>
</div>

<div class="home-award">
	<?php
		$award_text = sk_get_subfield('award_text', array(
			'before' => '<div class="boldstatement__statement">',
			'after'  => '</div>'
		));

		$award_image = sk_get_subfield('award_logo', array(
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

<style>
	body.home .block--bold_statement .boldstatement.l-container .boldstatement__statement--h1 {
		font-size: 2.0em;
		padding-top: 18px;
	}
	@media (min-width: 320px) and (max-width: 768px) {
		body.home .block--bold_statement .boldstatement.l-container .boldstatement__statement--h1 {
			font-size: 24px;
			padding-top: 18px;
			padding-inline: 10px;
		}
	}
</style>	