/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 05.05.2017
 * Time: 14:11
 */

/**
 * Creates a Spoiler
 *
 * @param that
 * @param id
 * @param originalText
 */
function spoiler(that, id, originalText) {
	var element = document.getElementById(id);

	if(element === null || element === undefined)
		return;

	if(! element.style.display) {
		element.style.display = 'block';
		that.innerHTML = originalText + ' (Verstecken)';
	} else {
		element.style.display = '';
		that.innerHTML = originalText + ' (Anzeigen)';
	}
}
