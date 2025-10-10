jQuery(document).ready(function( $ ) {
	var $datefield = $(".SelectDate"),
		$tourDates = $("#tourDates"),
		ajaxLoadTourDates,
		prevDate = new Date($datefield.val());

	if ( $datefield.length ) {
		$datefield.on({
			change: function( event ) {
				var nextDate = new Date($datefield.val()),
					isSameMonth = ( nextDate.getMonth() === prevDate.getMonth() && nextDate.getYear() === prevDate.getYear() ) ? true : false;

				//Load Calendar Dates if it's a different month/year
				if (! isSameMonth ) {
					if ( ajaxLoadTourDates && ajaxLoadTourDates.readyState !== 4 ) {
						ajaxLoadTourDates.abort();
					}

					//Load New Month with tours
					ajaxLoadTourDates = $.ajax({
						url: "/tours/days",
						dataType: 'html',
						data: {
							date: $datefield.val()
						}
					}).done(function( data, textStatus, jqXHR ) {
						$tourDates.empty().append(data);
					});
				}

				//Save the selected date for next comparison.
				prevDate = nextDate;
			}
		});
	}

});