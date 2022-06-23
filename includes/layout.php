<?php
/**
 * Author: Peter Dragicevic [peter@petschko.org]
 * Authors-Website: http://petschko.org/
 * Date: 15.04.2016
 * Time: 21:50
 *
 * Notes: -
 */

defined('BASE_DIR') or die('Invalid File-Access');

// Build HTML-Page
?>
<!DOCTYPE HTML>
<html lang="<?php echo Language::out()->getHTMLLang();?>">
	<?php require_once(TEMPLATE_DIR . DS . 'system' . DS . 'html_head.php'); ?>

	<body>
		<!-- Add overall content -->
		<?php require_once(TEMPLATE_DIR . DS . 'system' . DS . 'header.php'); ?>

		<!-- Add content -->
		<div id="content" class="full">
			<?php
				if(file_exists(TEMPLATE_DIR . DS . Page::getViewFile())) {
					require_once(TEMPLATE_DIR . DS . Page::getViewFile());
				} else {
					require_once(TEMPLATE_DIR . DS . 'system' . DS . '404.php');
				}
				echo PHP_EOL;
			?>
		</div>
		<!-- Content END -->

		<!-- Add overall footer -->
		<?php require_once(TEMPLATE_DIR . DS . 'system' . DS . 'footer.php'); ?>

		<!-- Created with Petschko's Framework <?php echo Config::VERSION; ?> - Mail: peter@petschko.org -->
		<?php
			echo PHP_EOL;
			Page::printCssFiles(false);
			Page::printJsFiles(false);
			echo PHP_EOL;
		?>
	</body>
</html>
