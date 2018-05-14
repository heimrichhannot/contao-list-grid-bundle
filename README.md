# List Grid Bundle

Contao bundle to create highly customizable list templater that may use specific column sets, news templates and image sizes for specific positions in a list.

> Currently in development

## Developers

### Add grid to your frontend module

Add `addListGrid` to your module palette.

Example:
```
str_replace('listConfig;', 'listConfig,addListGrid;', $dc['palettes']['your_module']);
```