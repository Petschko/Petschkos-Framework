<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 19.06.2017
 * Time: 16:30
 *
 * Notes: -
 */

defined('BASE_DIR') or die('Invalid File-Access');

?>

	<!-- Head-File Start -->
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo Config::WEBSITE_CHARSET; ?>" />
		<title><?php Page::printWebsiteTitle(); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php
		Page::printHtmlCanonicalLink();
		Page::printHtmlMetaTitle();
		Page::printHtmlMetaDescription();
		Page::printHtmlMetaKeywords();

		if(Config::WEBSITE_FAVICON) {
			require_once(TEMPLATE_DIR . DS . 'system' . DS . 'favicon.php');
		}

		echo PHP_EOL;

		?>
		<script>
			// Get Language strings
			var sprintf = (str, ...argv) => !argv.length ? str :
				sprintf(str.replace(sprintf.token || "$", argv.shift()), ...argv);
			var lang = new Lang();
		</script>

		<?php
			// Add all CSS/JS Head Files
			Page::printCssFiles(true);
			Page::printJsFiles(true);

			// Add Google-Analytics if enabled
			if(Config::GOOGLE_ANALYTICS_ENABLE) {
				require_once(TEMPLATE_DIR . DS . 'system' . DS . 'googleAnalytics.php');
				echo PHP_EOL;
			}

			echo PHP_EOL;
		?>
	</head>
	<!-- Head-File End -->
