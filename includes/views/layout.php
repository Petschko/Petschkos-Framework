<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 15.04.2016
 * Time: 21:50
 * Update: -
 * Version: 0.0.1
 *
 * Notes: -
 */

// Build HTML-Page
?>
<!DOCTYPE html>

<html lang="<?php echo Language::get()->getHTMLLang(); ?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo Language::get()->getPageTitle(); ?></title>
		<link href="css/style.css" rel="stylesheet" type="text/css" />
		<!--<link rel="shortcut icon" type="image/x-icon" href="images/fav.ico">-->
	</head>
	<body>
		<?php
		// HTML-Body
		// HTML-Body section top (Overall header)

		// HTML-Body Middle
		require_once(CONTROLLER_DIR . DS . 'Router.php');

		// HTML-Head footer

		echo PHP_EOL;
		?>
	</body>
</html>
