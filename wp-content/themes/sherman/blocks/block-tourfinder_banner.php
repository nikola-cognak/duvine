<?php 
/**
 * MODULE - Tourfinder banner
 *
 * Banner block with controls to help you find your tour
 *
 * @subfield title (text area)
 *
 */


// sk_the_subfield('copy');

?>

<div class="l-container l-container--mobilefull">
    <form class="tourfinderbanner" action="/tour-finder">
        <?php
            sk_block_field('title', array(
                'before' => '<div class="tourfinderbanner__col tourfinderbanner__col--header"><h3 class="blockheader t-white">',
                'after'  => '</h3></div>'
            ));
        ?>

        <div class="tourfinderbanner__col">
            <span class="tourfinderbanner__select">Destinations</span>


            <div class="tourfinderbanner__dropdown">
                <div class="tourfinderbanner__container">
                    <p class="tourfinderbanner__disclaimer">All selections within each category are optional.</p>

                    <?php $locationlist = duvine_get_hierarchical_location_list(); ?>
                    <?php if( $locationlist ) : ?>
                        <ul class="locationselector baselist">
                            <?php foreach( $locationlist as $index => $continent ) : ?>
                                <li class="locationselector__continent">
                                    <span class="tourfinderbanner__colheader"><?php echo $continent['continent']; ?></span>

                                    <?php if( $continent['countries'] ) : ?>
                                        <ul class="baselist">
                                            <li class="tourfinderbanner__option checkmark--white tourfinderbanner__option--selectall"><input type="checkbox" id="locationselector--<?php echo $continent['slug']; ?>--selectall" class="checkbox--selectall"><label class="tourfinderbanner__label" for="locationselector--<?php echo $continent['slug']; ?>--selectall">Select all</label></li>
                                            <?php foreach( $continent['countries'] as $country ) : ?>
                                                <li class="tourfinderbanner__option checkmark--white"><input type="checkbox" id="locationselector--<?php echo $country->post_name; ?>" name="destination[]" value="<?php echo $country->ID; ?>"><label class="tourfinderbanner__label" for="locationselector--<?php echo $country->post_name; ?>"><?php echo $country->post_title; ?></label></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                </div>
                <button type="submit" class="cta cta--hoverwhite tourfinderbanner__dropdownsubmit">Find A Tour</button>
                <a href="#" class="tourfinderbanner__dropdownclose"></a>
            </div>
        </div>

        <div class="tourfinderbanner__col date-picker-conteiner">
            <input name="tourtype[]" id="date-range-tourtype" type="hidden" autocomplete="off" value=""/>
            <span class="tourfinderbanner__select date-range-select">Dates</span>
            <input name="date" id="date-range" type="hidden" autocomplete="off" value=""/>

            <div class="tourfinderbanner__dropdown">
                <div class="tourfinderbanner__container">
                    <p class="tourfinderbanner__disclaimer tourfinderbanner__disclaimer--levelselector">All selections within each category are optional.</p>
                </div>
                <button type="submit" class="cta cta--hoverwhite tourfinderbanner__dropdownsubmit date-range-button">Find A Tour</button>
                <a href="#" class="tourfinderbanner__dropdownclose"></a>
            </div>
        </div>

        <div class="tourfinderbanner__col">
            <span class="tourfinderbanner__select">Levels</span>

            <div class="tourfinderbanner__dropdown">
                <div class="tourfinderbanner__container">
                    <p class="tourfinderbanner__disclaimer tourfinderbanner__disclaimer--levelselector">All selections within each category are optional.</p>
                    
                    <div class="levelselector">
                        <span class="tourfinderbanner__colheader">Level</span>
                        <ul class="baselist">
                            <li class="tourfinderbanner__option checkmark--white"><input type="checkbox" name="level[]" value="1" id="levelselector--1"><label class="tourfinderbanner__label" for="levelselector--1">Level 1 (leisurely)</label></li>
                            <li class="tourfinderbanner__option checkmark--white"><input type="checkbox" name="level[]" value="2" id="levelselector--2"><label class="tourfinderbanner__label" for="levelselector--2">Level 2</label></li>
                            <li class="tourfinderbanner__option checkmark--white"><input type="checkbox" name="level[]" value="3" id="levelselector--3"><label class="tourfinderbanner__label" for="levelselector--3">Level 3</label></li>
                            <li class="tourfinderbanner__option checkmark--white"><input type="checkbox" name="level[]" value="4" id="levelselector--4"><label class="tourfinderbanner__label" for="levelselector--4">Level 4 (challenging)</label></li>
                            <li class="tourfinderbanner__option checkmark--white"><input type="checkbox" name="level[]" value="0" id="levelselector--nonrider"><label class="tourfinderbanner__label" for="levelselector--nonrider">Non-rider</label></li>
                        </ul>
                    </div>
                </div>
                <button type="submit" class="cta cta--hoverwhite tourfinderbanner__dropdownsubmit">Find A Tour</button>
                <a href="#" class="tourfinderbanner__dropdownclose"></a>
            </div>
        </div>

        <div class="tourfinderbanner__col tourfinderbanner__col--action">
            <button type="submit" class="cta cta--hoverwhite">Find A Tour</button>
        </div>
    </form>
</div>