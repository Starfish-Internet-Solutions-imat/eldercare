$(window).load(
		function() {

			var original_content = $('tbody').html();

			$(".recommend").click(
					function() {
						$(".recommendHCP").fadeIn();
						$("#hcp_name").text($(this).attr("id"));
						$("#hcp_id").val(
								$(this).parent().children('input[type=hidden]')
										.val());
					});

			$(".recommendSubmit").click(function() {
				$(".recommendHCP").fadeOut();
			});

			$('input[name=seeker]').keyup(function() {
				var query = $(this).val();
				// if (query.length >= 3){
				$.php('/ajax/seekers/viewSeekers', {
					query : query
				});
				// }

				php.complete = function() {
					if ($('#suggestion_list ul').children().length != 0)
						$('#suggestion_list').slideDown(10);
				}
			});

			$('#suggestion_list ul li').live('click', function() {
				var query = $(this).text();
				var id = $(this).attr('id');

				$('input[name=seeker]').val(query);
				$('input[name=seeker_id]').val(id);
				$('#suggestion_list').slideUp(100);
			});

			// $(document).ready(function()
			{
				// $("#hcp_table").tablesorter( {sortList: [[1,0]]} );
			}
			// );

			$(document).ready(function() {
				$('#hcp_name_search').keyup(function() {
					searchColumn($(this).val(), 1);
				});

				$('#hcp_state_search').keyup(function() {
					searchColumn($(this).val(), 4);
				});

				$('#hcp_zipcode_search').keyup(function() {
					searchColumn($(this).val(), 5);
				});

			});

			// ################################## Search AJAX
			// #################################//

			$('input, select').bind('change keyup', function() {
				var name = $('input[name=name]').val();
				var state = $('input[name=state]').val();
				var zipcode = $('input[name=zipcode]').val();
				var max_price = Number($('select[name=max_price]').val());
				var min_price = Number($('select[name=min_price]').val());

				if ($('select[name=max_price]').val() != "") {

					max_price = (max_price + 1) * 1000;
				}

				if ($('select[name=min_price]').val() != "") {
					min_price = (min_price + 1) * 1000;
				}

				$.php('/ajax/hcps/search', {
					name : name,
					state : state,
					zipcode : zipcode,
					price_from : min_price,
					price_to : max_price,
					prev_content : original_content
				});
				
				php.error = function()
				{
					
				};

			});

		});
