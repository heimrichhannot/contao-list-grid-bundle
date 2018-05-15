# List Grid Bundle

Contao bundle to create highly customizable list templates that may use specific column sets, news templates and image sizes for specific positions in a list. 

This bundle is an extension to [Contao List Bundle](https://github.com/heimrichhannot/contao-list-bundle).

## Features

* create a grid template with content elements for your list from contao backend
* activate from module (comes with build in support for list module, other modules with list support can be used by updated the palette, see Developers section.)

## Requirements

* PHP 7.1 or higher
* Contao 4.4 or higher
* [Contao List Bundle](https://github.com/heimrichhannot/contao-list-bundle) 1.0.0-beta41 or higher

## Usage

1. Create a new list grid (Backend -> System -> List grid)
2. Add content elements as you like. Add placeholder content element where you want list items to display and set the template they should use.
3. Active List Grid in your module config and select the list grid you configured.

## Developers

### Templates

You can use every list item template. Please see the [Contao List Bundle Documentation](https://github.com/heimrichhannot/contao-list-bundle) how to add templates.

### Add grid to your frontend module

Add `addListGrid` to your module palette.

Example:
```
str_replace('listConfig;', 'listConfig,addListGrid;', $dc['palettes']['your_module']);
```