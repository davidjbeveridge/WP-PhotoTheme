(function($){
	
	var defaultBackground;
	var siteUrl = window.location.protocol + "//" + window.location.host;
		
	function backgroundSetup(){
		var postThumbnailLinks = $('div.postThumbnails a');
		$('div.postThumbnails').appendTo('body');
		var backgroundImage = $('#backgroundImage');
		
		if(postThumbnailLinks.length < 1 && backgroundImage.attr('src') != defaultBackground)	{
			backgroundImage.fadeOut('slow',function(){
				backgroundImage.attr('src',defaultBackground).fadeIn('slow');
			});
			return;
		}
		postThumbnailLinks.live('click',function(e){
			e.preventDefault();
			var newSrc = $(this).attr('href');
			var currentThumbnail = $(this);
			if (backgroundImage.attr('src') != newSrc) {
				var tempImage = new Image();
				tempImage.onload = function(){
					backgroundImage.fadeOut('slow', function(){
						//postThumbnailLinks.each(function() {$(this).removeClass('active');});
						//currentThumbnail.addClass('active');
						backgroundImage.attr('src', newSrc).fadeIn('slow');
					});
				};
				tempImage.src = newSrc;
			}
			return false;
		});
		var initialImage = new Image;
		initialImage.onload = function()	{
			backgroundImage.fadeOut('slow',function(){
				backgroundImage.attr('src',postThumbnailLinks.eq(0).attr('href')).fadeIn();
			});
		};
		initialImage.src = postThumbnailLinks.eq(0).attr('href');
		//postThumbnailLinks.eq(0).toggleClass('active');
	}
	
	function ajaxPostLoad(href,callback)	{
		if(!href || href == '#')	{
			href = window.location.href.replace('#','');
		}
		else	{
			href = siteUrl+href;
		}
		var $main = $('#main');
		$main.slideUp('slow',function(){
			$.post(href,{ajax:1},function(data,status,xhr){
				$('.postThumbnails').remove();
				$main.replaceWith(data).slideDown('slow');
				if (typeof(callback) === 'function') {
					callback();
				}
			});		
		});
	}
	
	function contentLinkHandler(e)	{
		if(this.hostname === window.location.hostname)	{
			e.preventDefault();
			window.location.hash = this.href.replace(siteUrl,'');
		}
	}
	
	function hashChangeHandler(e)	{
		var newurl = window.location.hash.substr(1,window.location.hash.length-1);
		ajaxPostLoad(newurl,backgroundSetup);
	}
	
	function docLoad()	{
		defaultBackground = $('#backgroundImage').attr('src');
		$(window).bind('hashchange',hashChangeHandler);
		if(window.location.hash)	{
			$(window).trigger('hashchange');
		}

		backgroundSetup();
		$('a.ajax').live('click',contentLinkHandler);
	}
	
	$(window).load(docLoad);
})(jQuery);
