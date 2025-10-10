<?php 
/**
 * MODULE - Private Planning Form
 *
 * Displays private planning form
 *
 * @subfield headline, content, form
 *
 */


?>

    <div class="l-container l-container--small">
        <div class="private-planning-form duvine-accordion d-content">
            <header class="accordion__header js-duvine-accordion-trigger">
                <div class="level-accordion-header">
                    <div class="accordion-title">
                        <h3><?php echo get_sub_field('headline'); ?></h3>
                    </div>
                </div>
            </header>
            <div class="accordion__content">
                <div class="private-planning-form__intro">
                    <?php echo get_sub_field('content'); ?>
                </div>
                <div class="private-planning-form__form">
                    <?php echo get_sub_field('form'); ?>
                </div>
            </div>
        </div>
    </div>