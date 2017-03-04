(function($) {

	$(document).ready(function() {
		instagramJudoInit();
	});
	$(window).load(function() {
		themeInit();
	});

	function instagramJudoInit()
	{

	    $('.instagram-judo').each(function(i, v)
	    {
	        var userId      = $(v).find('input[name=ij_instagram_user_id]').val(),
	            accessToken = $(v).find('input[name=ij_instagram_access_token]').val(),
	            count       = $(v).find('input[name=ij_default_image_count]').val(),
	            theme       = $(v).find('input[name=ij_sc_theme]').val(),
	            columns     = $(v).find('input[name=ij_feed_columns]').val();
	 
	        $(v).find('.instagram-judo-feed').on('didLoadInstagram', function(event, response) {
	 
	            if(response.data !== undefined)
	            {
	            	console.log(response.data);
	                var total = response.data.length > count ? count : response.data.length,
	                	images = [];
	 				

	                for(var c = 0; c < total; c++)
	                {
	                	switch(theme)
	                	{
	        				default:
			                    images.push([
			                        '<figure class="feed-item"><a href="' + response.data[c].link + '" target="_blank" title="View this post on Instagram" data-columns='+ columns +'>',
			                            '<img alt="' + response.data[c].user.username + ' via Instagram" src="' + response.data[c].images.standard_resolution.url + '" width="' + response.data[c].images.standard_resolution.width + '" height="' + response.data[c].images.standard_resolution.height + '"/>',
		                        		'<figcaption class="meta">',
		                        			'<div class="tags">' + response.data[c].tags.join(', ') + '</div>',
		                        			'<div class="likes"><span>Likes:</span> <span>' + response.data[c].likes.count + '</span></div>',
		                        			'<div class="comments"><span>Comments:</span> <span>' + response.data[c].comments.count + '</span></div>',
		                        		'</figcaption>',
			                        '</a></figure>'
			                    ].join(''));
		                    break;
	                	}
	                }
	 
	                $(this).append(images.join(''));
	                $(this).addClass('theme-' + theme);
	            }
	        });
	 
	        $(v).find('.instagram-judo-feed').instagram({
	            userId     : userId,
	            accessToken: accessToken,
	            count      : count
	        });

	    });
	}

	function themeInit()
	{
		$(".instagram-judo-feed.theme-default").each(function() {

			var $feed = $(this).masonry({
		        itemSelector: ".feed-item",
		        columnWidth: '.feed-column-width',
		        gutter: 10
		    });

		    $feed.one( 'layoutComplete', function() {
		    	$feed.addClass('masonry-loaded');
		    });

		    $feed.masonry();

		});
	}

})(jQuery);