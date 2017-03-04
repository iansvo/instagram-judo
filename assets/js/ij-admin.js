(function($) {
	$(document).ready(function() {
		// generateUserId();
	});

	function generateUserId()
	{
		$('#getUserId').click(function(e) {
			var url = 'https://www.instagram.com/'+ $('#ij_instagram_username').val() + '/?__a=1',
				$s;

			$.get(url, function(data) {
				console.log(data);
			});

		});
	}

})(jQuery);