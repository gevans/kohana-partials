# What are partials?

> Partial templates – usually just called “partials” – are another device for breaking the rendering process into more manageable chunks. With a partial, you can move the code for rendering a particular piece of a response to its own file.

## Basic Usage

Partials let you DRY up your views and split them into smaller pieces. If you're reusing forms on several views, displaying data in similar formats, or facing problems where a simple view turns into a complicated mess, partials can help.

Just as you would use the `View` class, you can use `Partial::factory('path/to/partial')` to render your partials. Partials are very similar to regular views and are stored in the `views/` directory of your application. One difference however, is that these files are prefixed with an underscore. So `Partial::factory('contact/form')` will render `views/contact/_form.php`.

Having an underscore in the filename of your partials makes it easier to assume which views you are reusing and which views are independent. For examples, see the [basic examples](examples/basic) page.

## Collections

This module also includes several shortcuts for rendering lists and collections (for example, a listing of products). Rather than mixing complicated foreach statements into your views:

	// application/views/posts/index.php
	<?php foreach ($posts as $post): ?>
	<div>
		<h2><?= $post->title ?></h2>
		<?= $post->content ?>
	</div>
	<?php endforeach; ?>

Partials will loop over the provided data and eliminate the madness in complex views:

	// application/views/posts/index.php
	<?= Partial::factory('posts/post')->collection($posts) ?>

	// application/views/posts/_post.php
	<div>
		<h2><?= $post->title ?></h2>
		<?= $post->content ?>
	</div>

For more examples, see the [collection examples](examples/collections) page.
