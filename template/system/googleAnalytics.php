<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 19.06.2017
 * Time: 17:02
 *
 * Notes: Contains the snipped to include Google-Analytics
 */

defined('BASE_DIR') or die('Invalid File-Access');
?>

		<!-- Include GA -->
		<script>
			var gaProperty = '<?php echo Config::gaProperty; ?>';
			var anonymizeIP = <?php echo Config::gaAnonymizeIp; ?>;
			var disableString = 'ga-disable-' + gaProperty;
			var gaEnable = true;

			// Check if user has disabled GA
			if(document.cookie.indexOf(disableString + '=true') > -1) {
				gaEnable = false;
			}

			/**
			 * Disable Google-Analytics for this Page
			 */
			function gaOutput() {
				// Exit function if user has already disabled GA
				if(! gaEnable) {
					alert(lang.googleAnalyticsAlreadOff);

					return;
				}

				document.cookie = disableString + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';

				// Show User-Info
				alert(lang.googleAnalyticsDisabled);
			}

			// Only load GA if allowed
			if(gaEnable && navigator.userAgent.indexOf("Speed Insights") === -1) {
				(function (i, s, o, g, r, a, m) {
					i['GoogleAnalyticsObject'] = r;
					i[r] = i[r] || function () {
							(i[r].q = i[r].q || []).push(arguments)
						}, i[r].l = 1 * new Date();
					a = s.createElement(o),
						m = s.getElementsByTagName(o)[0];
					a.async = 1;
					a.src = g;
					m.parentNode.insertBefore(a, m)
				})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

				ga('create', gaProperty, 'auto');

				if(anonymizeIP) {
					ga('set', 'anonymizeIp', true); // Just send first 3 IP-Blocks
				}

				ga('send', 'pageview');
			}
		</script>
		<!-- END GA -->

