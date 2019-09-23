var url = document.getElementById('url').value;
var csrf = getCookie('csrf_cookie');

function addContact() {
	if (confirm('Are you sure you want to add this user to the contact list?')) {
		var name = document.getElementById('name').value;
		var email = document.getElementById('email').value;
		var contact = new FormData();
		contact.set('csrf_token', csrf);
		contact.set('name', name);
		contact.set('email', email);

		var request = new XMLHttpRequest();
		request.open('POST', url + 'contacts', true);
		request.onload = function () {
			if (request.status >= 200 && request.status < 400) {
				var data = JSON.parse(request.responseText);
				csrf = data.csrf_token;
				if (data.valid) {
					clearInputs();
					var table = document.getElementById('contact-table');
					var row = table.insertRow(-1);
					var cell1 = row.insertCell(0);
					var cell2 = row.insertCell(1);
					cell1.innerHTML = name;
					cell2.innerHTML = email;
					M.toast({
						html: 'Contact sucessfully added'
					});
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

		request.send(contact);
	}
}

function deleteContact(row, id) {
	if (confirm('Are you sure you want to delete this contact?')) {
		var request = new XMLHttpRequest();
		request.open('DELETE', `${url}contacts/${id}`, true);
		request.onload = function () {
			if (request.status >= 200 && request.status < 400) {
				var data = JSON.parse(request.responseText);
				if (data.valid) {
					row.remove();
					M.toast({
						html: 'Contact sucessfully deleted'
					});
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

function clearInputs() {
	// Clear out text inputs
	const inputList = document.querySelectorAll('.input');
	for (var i = 0; i < inputList.length; i++) {
		inputList[i].value = "";
	}

	// Remove active class from labels
	const labelList = document.querySelectorAll('label');
	for (var i = 0; i < labelList.length; i++) {
		labelList[i].classList.remove('active');
	}
}

function getCookie(cname) {
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for(var i = 0; i <ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) === ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) === 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}
