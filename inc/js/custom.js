jQuery(document).ready(function($) {

	  // bind selectbox in the form
	  var $filterType = $('form#filter option[name="type"]');

	  // get the previous and current client collections
	  var $currentClient = $('ul#currentClients');
	  var $previousClient = $('ul#previousClients');
	  //console.log($currentClient); console.log($previousClient);
	  	  
	  // clone first and second client collections
	  var $currentClientData = $currentClient.clone();
	  var $previousClientData = $previousClient.clone();
	  
	  // attempt to call Quicksand on every form change
	  $("form#filter").change(function(e) {
		if ($($filterType+':selected').val() == 'all') {
		  var $filteredCurrentData = $currentClientData.find('li');
		  var $filteredPreviousData = $previousClientData.find('li');
		} else {
		  var $filteredCurrentData = $currentClientData.find('li[data-type=' + $($filterType+":selected").val() + ']');
		  var $filteredPreviousData = $previousClientData.find('li[data-type=' + $($filterType+":selected").val() + ']');
		}

		$currentClient.quicksand($filteredCurrentData, {
		  duration: 800,
		  easing: 'easeInOutQuad'
		});
		$previousClient.quicksand($filteredPreviousData, {
		  duration: 800,
		  easing: 'easeInOutQuad'
		});
	
	  });
	  
	$("div#clientsGrid a.clientLink").live("mouseover mouseout", function(event) {
		if ( event.type == "mouseover" ) {
			$(this).find(".pinkOverlay").css({opacity:0.0}).animate({"opacity":0.45},300);
		} else {
			$(this).find(".pinkOverlay").stop().animate({"opacity":0},200);
		}
	});
});