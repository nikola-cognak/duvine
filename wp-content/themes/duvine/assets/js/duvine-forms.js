jQuery(document).ready(function( $ ) {
	var $selectBoxes = $(".SelectBox"),
		$datepicker = $(".SelectDate"),
		$radioGroup = $(".Radio-Group"),
		$checkboxGroup = $(".Checkbox-Group");


	//Select Box Styling
	if ( $selectBoxes.length ) {
		$selectBoxes.selectBox();
	}

	//Date Calendar Selector
	if ( $datepicker.length ) {
		$datepicker.datepicker({
			inline: true,
			showOtherMonths: true,
			minDate: 0,
			dateFormat: "MM dd, yy"
		});
	}

	//Radio Button Styling Click event
	if ( $radioGroup.length ) {
		$radioGroup.gfRadiobuttons();
	}

	//Radio Button Styling Click event
	if ( $checkboxGroup.length ) {
		$checkboxGroup.gfCheckboxes();
	}
});