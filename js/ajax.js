/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 31.05.2017
 * Time: 20:12
 */

/**
 * Posts an AJAX request
 *
 * @param {String} url - URL of the AJAX request
 * @param {Object|String} data - Data of the AJAX request
 * @param {function} success - response
 * @returns {*}
 */
function postAjax(url, data, success) {
	var params = typeof data === 'string' ? data : Object.keys(data).map(
		function(k){ return encodeURIComponent(k) + '=' + encodeURIComponent(data[k]) }
	).join('&');

	var xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	xhr.open('POST', url);
	xhr.onreadystatechange = function() {
		if (xhr.readyState > 3 && xhr.status === 200) { success(xhr.responseText); }
	};
	xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.send(params);

	return xhr;
}

/**
 * Get the Math-Question via AJAX
 *
 * @param {string} ajaxUrl - URL of the AJAX-Method
 * @param {string} formId - Id of the submit Button
 * @param {string} mathLabelId - ID of the Entry Container
 */
function getMathQuestion(ajaxUrl, formId, mathLabelId) {
	let data = {
		formId: formId
	};

	let mathLabelEl = document.getElementById(mathLabelId);

	postAjax(ajaxUrl, data, function(response) {
		let responseObj = {
			success: false,
			message: null,
			extraInfo: null
		};

		try {
			responseObj = JSON.parse(response);
		} catch(exception) {
			responseObj.message = 'Technischer Fehler: ' + exception.toString() + ' -> ' + response.replace('"', '\"');
		}

		if(responseObj.success) {
			mathLabelEl.innerHTML = responseObj.extraInfo;
		} else {
			mathLabelEl.innerHTML += '<b>Konnte keine neue Anti-Spam Aufgabe laden, drücken Sie F5 um die Seite neu zu laden!</b> <i>(Kopieren Sie vorher zur Sicherheit Ihren Text!)</i>';
		}
	});
}
