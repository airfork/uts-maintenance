var url = document.getElementById('url').value;

function issue(row, id) {
	if (confirm('Are you sure you want to resolve this issue?')) {
		var request = new XMLHttpRequest();
		request.open('PATCH', `${url}resolve/${id}`, true);
		request.onload = function () {
			if (request.status >= 200 && request.status < 400) {
				var data = JSON.parse(request.responseText);
				if (data.valid) {
					M.toast({
						html: 'Issue successfully resolved.'
					});
					row.remove();
				} else {
					M.toast({
						html: data.error
					});
				}
			} else {
				// We reached our target server, but it returned an error
				M.toast({
					html: 'There was a problem processing your request, please refresh the page and try again.'
				});
			}
		};

		request.onerror = function () {
			// There was a connection error of some sort
			console.log("There was an error of some type, please try again");
			M.toast({
				html: 'There was a problem processing your request, please refresh the page and try again.'
			});
		};

		request.send();
	}
}


