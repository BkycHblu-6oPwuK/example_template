<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Itb\Assets\Vite;
$vite = Vite::getInstance(getenv('VITE_BASE_PATH'), MANIFEST_PATH, IS_PRODUCTION, getenv('VITE_PORT'));
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