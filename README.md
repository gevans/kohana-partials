# Kohana Partials

> Partial templates – usually just called “partials” – are another device for breaking the rendering process into more manageable chunks. With a partial, you can move the code for rendering a particular piece of a response to its own file.

## Basic Usage

You can render a partial in your views with `Partial::factory()` like you would normally do with the `View` class. The big difference is that the filename you specify will be prefixed with an underscore.

For example, `Partial::factory('contact/form')` will render a partial named `contact/_form.php` in your application's views directory.

## Collections

A lot of the time, we have an array of objects we need to iterate through to display. For example, in a blog, we want to list a few posts. In your view you might be using something like this:

```php
<?php foreach ($posts as $post): ?>
<div>
    <h3><?php echo $post->title; ?></h3>
    <?php echo $post->content; ?>
</div>
<?php endforeach; ?>
```

Using partials, you can simplify your views a bit by using the `collection()` method:

```php
<?php echo Partial::factory('posts/post')->collection($posts); ?>
```

This one-liner will render the partial named `posts/_post.php` for every item in `$posts`. Each item is accessible in your partial as a variable named after the partial. In this case, the variable is `$post`:

```php
<div>
    <h3><?php echo $post->title; ?></h3>
    <?php echo $post->content; ?>
</div>
```

## Installation

Clone the Git repository into your modules directory:

    $ git clone git://github.com/gevans/kohana-partials.git modules/partials

*Or*, clone the repository as a submodule:

    $ git submodule add git://github.com/gevans/kohana-partials.git modules/partials

You can now enable the module in your application's `bootstrap.php`:

```php
<?php
Kohana::modules(array(
    // ...
    'partials' => MODPATH.'partials', // Partial templates
    // ...
));
```
