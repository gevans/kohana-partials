# Basic Examples

These are basic examples of how partials can be used to DRY up views for reuse in other views.

## Layouts

Your application's look and feel is important and being able to maintain it will save you a ton of development time. In this example, we'll outline a basic template that has been split into several smaller partials.

### Main Template

In this case, we're using the default `Controller_Template` class to render our layout with the default `template.php` view. All of the bulk of its content and markup is separated into smaller, easier to maintain files and at the same time, we're able to get an overview of the layout.

	<!-- application/views/template.php -->
	<html>
	<head>
		<?= Partial::factory('template/head') ?>
	</head>
	<body>

		<div id="header">
			<?= Partial::factory('template/header') ?>
		</div>

		<div id="main">
			<?= $content ?>
		</div>

		<div id="footer">
			<?= Partial::factory('template/footer') ?>
		</div>

	</body>
	</html>

### Head

This partial contains the markup we want in the `<head>` of the template. This can range anywhere from stylesheets to javascripts and various meta tags. In the case where multiple templates are being used in an application, you can reuse this partial in other layouts.

	<!-- application/views/template/_head.php -->
	<meta http-equiv="Content-Type" content="text/html; charset=<?= Kohana::$charset ?>" />

	<title><?= $title ?></title>

	<meta name="keywords" content="" />
	<meta name="description" content="" />

	<!-- scripts and javascripts -->

### Template Header

Our header contains our site's title, tagline, and main navigation menu.

	<!-- application/views/template/_header.php -->
	<h1><?= $title ?></h1>
	<h2><?= $tagline ?></h2>

	<ul id="top-nav">
		<?= Partial::factory('template/navigation')->collection($navigation, 'item') ?>
	</ul>

### Navigation

One line for a partial may seem like overkill, but it allows us to keep things simple.

	<!-- application/views/template/_navigation.php -->
	<li><?= HTML::anchor($item['url'], $item['title']) ?></li>

In this case, when the partial is rendered, it will loop through the specified navigation items and produce markup similar to this:

	<li><a href="/">Home</a></li>
	<li><a href="/about">About Us</a></li>
	<li><a href="/contact">Contact Us<</li>

### Template Footer

We're able to reuse the same navigation in our footer:

	<!-- application/views/template/_footer.php -->
	&copy; 2012 Some Company

	<div id="bottom-nav">
		<?= Partial::factory('template/navigation')->collection($navigation, 'item')->spacer(' | ') ?>
	</div>
