<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Itb\Assets\Vite;

$basePath = getenv('VITE_BASE_PATH');
$manifestPath = $_SERVER['DOCUMENT_ROOT'] . $basePath . '.vite/manifest.json';
$vite = new Vite($basePath, $manifestPath, IS_PRODUCTION, getenv('VITE_PORT'));

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