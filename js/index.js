'use strict';
M.AutoInit();

document.addEventListener('DOMContentLoaded', function () {
    var elems = document.querySelectorAll('.fixed-action-btn');
    var instances = M.FloatingActionButton.init(elems);

    elems[0].onclick = getDescriptions;
});

const url = document.getElementById('url').value.replace(' ;', '');
const site_url = document.getElementById('site_url').value.replace(' ;', '');
var csrf = getCookie('csrf_cookie');

function getDescriptions() {
    const headers = document.querySelectorAll('.collapsible-header');
    const descriptions = document.querySelectorAll('textarea');
    const name = document.getElementById('name').value;
    if (name.trim() === '') {
        M.toast({
            html: 'Please input your name.'
        });
        return
    }
    var issues = new FormData();
    issues.set('csrf_token', csrf);
    issues.set('name', name);
    headers.forEach(function (header) {
        const category = header.innerHTML;
        issues.set(category, header.nextElementSibling.firstChild.nextSibling.firstChild.nextSibling.firstChild.nextSibling.value);
    });
    sendIssues(issues);
}

function sendIssues(issues) {
    var request = new XMLHttpRequest();
    request.open('POST', url, true);
    request.onload = function () {
        console.log(request.responseText);
        if (request.status >= 200 && request.status < 400) {
            var data = JSON.parse(request.responseText);
            csrf = data.csrf_token;
            if (data.valid) {
                M.toast({
                    html: 'Bus successfully completed! Redirecting...'
                });
                setTimeout(function(){
                    window.location.replace(site_url);
                }, (2 * 1000));
            } else {
                M.toast({
                    html: 'There was a problem processing your request, please try again.'
                });
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

    request.send(issues);
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