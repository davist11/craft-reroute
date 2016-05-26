/**
 * Reroute js plugin functions
 */

(function($){

	new Craft.AdminTable({
		tableSelector: '#reroutes',
		noObjectsSelector: '#no-reroutes',
		deleteAction: 'reroute/delete'
	});

	// upload reroutes
	var $form = $('.upload-modal__form');
	var $input = $form.find(".upload-modal__file");

	$input.on('change', function() {
		$form.submit();
	});

})(jQuery);