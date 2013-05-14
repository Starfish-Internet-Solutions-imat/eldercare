$(window).load(function(){
	
	//all this for PAGINATION!
	var application_id = $('body').attr('id');
	var active_tab = $('#table_tabs > div.active').attr('id');
	var page = $('.pagination.'+active_tab+' span.page').text();
	var pages = $('.pagination.'+active_tab+' span.pages').text();
	
	$('#table_tabs > div').click(function(){
		active_tab = $(this).attr('id');
		$('.table_container .pagination').hide();
		$('.table_container .pagination.'+active_tab).show();
		page = $('.pagination.'+active_tab+' span.page').text();
		pages = $('.pagination.'+active_tab+' span.pages').text();
	});
	
	$('.pagination span.next').click(function(){
		if(page < pages)
		{
			page = Number(page) + 1;
			updatePagination(active_tab, page);
		}
	});
	
	$('.pagination span.prev').click(function(){
		if(page > 1)
		{
			page = Number(page) - 1;
			updatePagination(active_tab, page);
		}
	});
	
//-------------------------------------------------------------------------------------------------

	function updatePagination()
	{
		callAjaxPagination(application_id, active_tab, page);
		
		$('.pagination.'+active_tab+' span.page').text(page);

		if(page == pages)
			$('.pagination.'+active_tab+' span.next').hide();
		else
			$('.pagination.'+active_tab+' span.next').show();
		
		if(page == 1)
			$('.pagination.'+active_tab+' span.prev').hide();
		else
			$('.pagination.'+active_tab+' span.prev').show();
	}
});