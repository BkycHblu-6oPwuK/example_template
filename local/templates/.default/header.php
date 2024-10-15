<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Itb\Core\Assets\Vite;
$vite = Vite::getInstance();
?>
<!DOCTYPE html>
<html>
<head>
	<?
	$vite->includeAssets([
		'src/common/js/header.js',
		'src/common/js/main.js',
		'src/common/js/footer.js',
	]);
	?>
</head>