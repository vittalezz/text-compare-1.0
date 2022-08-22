(function ($) {
	$(document).on(
		{
			mousemove: function (pos) {
				$(this).text($(this).attr('data-orig'));
				$(this).addClass('hover');
				$('#float').show();
				$('#float')
					.css('left', pos.pageX + 10 + 'px')
					.css('top', pos.pageY + 10 + 'px');
			},
			mouseleave: function () {
				$(this).text($(this).attr('data-value'));
				$(this).removeClass('hover');
				$('#float').hide();
			},
		},
		'.changed'
	);

	$(document).on('click', '#btn_compare', function () {
		var textA = $('#textA').val(),
			textB = $('#textB').val(),
			action = 'compare',
			data = { action: action, textA: textA, textB: textB };
		$('#textResult').fadeTo('fast', 0.5, function () {
			$.ajax({
				url: '/ajax.php',
				type: 'post',
				data: data,
				cache: false,
				dataType: 'json',
				success: function (response) {
					$('#textResult').html(response.result_compare);
					$('#textResult').fadeTo('fast', 1);
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(
						thrownError +
							'\r\n' +
							xhr.statusText +
							'\r\n' +
							xhr.responseText
					);
				},
			});
		});
	});
})(jQuery);
