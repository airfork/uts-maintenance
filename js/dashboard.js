'use strict';
M.AutoInit();

const url = document.getElementById('url').value.replace(' ;', '');
var csrf = getCookie('csrf_cookie');

document.getElementById('bus-list').onclick = function() {
    if (confirm('Are you sure you want to reset the bus list, this will remove the ability to properly generate the master list?')) {
        sendResetRequest();
    }
};

function sendResetRequest() {
    var request = new XMLHttpRequest();
    request.open('POST', url+'/buses/reset', true);

    request.onload = function () {
        if (request.status >= 200 && request.status < 400) {
            var data = JSON.parse(request.responseText);
            csrf = data.csrf_token;
            if (data.valid) {
                M.toast({
                    html: 'Bus list successfully reset.'
                });
            } else {
                if (data.not_signed_in) {
                    window.location.replace(url+'/login');
                } else {
                    M.toast({
                        html: 'There was a problem processing your request, please try again.'
                    });
                }
            }
        } else {
            // We reached our target server, but it returned an error
            M.toast({
                html: 'There was a problem processing your request, please try again.'
            });
        }
    };

    request.onerror = function () {
        // There was a connection error of some sort
        console.log("There was an error of some type, please try again");
        M.toast({
            html: 'There was a problem processing your request, please try again.'
        });
    };


    var data = new FormData();
    data.set('csrf_token', csrf);
    request.send(data);
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