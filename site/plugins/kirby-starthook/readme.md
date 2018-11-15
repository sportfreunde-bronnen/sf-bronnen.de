# Kirby Starthook

*Version 0.2*

- You will have access to `$page` directly from your `config.php` file.
- You can pass global data to your templates and snippets.
- You can do redirects on certain conditions.

**[How to install Kirby Starthook](docs/install.md)**

## Usage

Put your starthook code into the `config.php` file or in a plugin.

### Example 1

**Pass global data to templates and snippets**

The data that is returned here can later be used in all your templates and snippets:

```php
c::set('starthook', function($page) {
  return array(
    'foo' => 'my first template variable ' . $page->title(),
    'bar' => 'my second template variable'
  );
});
```

**Note:** This plugin only provides access to the `$page` object, whereas `$site` and `$pages` may be accessed with `site()` and `site()->children()`.

### Example 2

**Redirect on condition**

On a condition, redirect to another page.

```php
c::set('starthook', function($page) {
  if($page->title() == 'Project A') {
    go(page('projects'), 301);
  }
});
```

## Changelog

**0.2**

- Changed from using hooks to using config instead.

**0.1**

- Initial release

## Requirements

- [**Kirby**](https://getkirby.com/) 2.4.1+

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/jenstornell/kirby-starthook/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia, animal abuse, violence or any other form of hate speech.

## Credits

- [Jens Törnell](https://github.com/jenstornell)
- [Lukas Bestle](https://github.com/lukasbestle) - For this [solution](https://forum.getkirby.com/t/kirby-starthook-pass-global-data-to-your-templates-and-snippets/6710/4)
