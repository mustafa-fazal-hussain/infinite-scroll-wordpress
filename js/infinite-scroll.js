jQuery(document).ready(function($) 
{
    var count 			= 2;
	var check_my_loop 	= 1;
	jQuery(window).data('ajaxready', true).scroll(function(e) 
	{
		
		if (jQuery(window).data('ajaxready') == false) return;
		var bodyHeight 	= (jQuery(document).height() - jQuery(window).height()) - 600;
		if(jQuery(window).scrollTop() > bodyHeight)
		{				
			jQuery(window).data('ajaxready', false);
			if( check_my_loop > 0)
			{
				loadArticle(count);
				count++;
			}
		}
	});
   
    function loadArticle(pageNumber) 
	{
        var cat = jQuery('#category_name_val').val();

        jQuery.ajax(
		{
            url		: 	afp_vars.afp_ajax_url,
            type	:	'POST',
            data	: 	"action=infinite-scroll&page_no="+ pageNumber + "&loop_file=loop&val="+ cat +"&afp_nonce="+afp_vars.afp_nonce,
            success	: 	function(html) 
						{
							if(html=="") 
							{
								jQuery('#infinitBtn span').html('All Entries Loaded');
								jQuery('#infinitBtn').addClass('all_load');
								check_my_loop = 0;
							} else 
							{
								jQuery('#infinitBtn span').html('<i class="fa fa-spinner fa-pulse " aria-hidden="true"></i>');
							}
							jQuery("#tb-ajax-content").append(html);
							jQuery(window).data('ajaxready', true);
						}
        });
        return false;
    }

});

	