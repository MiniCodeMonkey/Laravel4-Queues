$(function() {
	$('a.btn').click(function () {
		// Start background job
		$.get('/start-geocode', function (response) {
			if (response.success) {
				$("#status-section").show();
				// Start update timer
				setTimeout(updateStatus, 100);
			}
		});

		return false;
	});
});

function updateStatus() {
	$.get('/status', function (response) {
		// Update progress bar & message
		$("#status-section .progress .bar").css('width', response.progress + '%');
		$("#status-section .alert").html(response.message);

		// Update again if we're not done
		if (response.progress < 100.0) {
			setTimeout(updateStatus, 100);
		}
	});
}