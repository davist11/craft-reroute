# Reroute Craft Plugin

Manage 301/302 redirects in the control panel.

![Reroute screenshot](http://cl.ly/image/3m471U0O0Z10/Screen%20Shot%202013-12-10%20at%208.04.19%20AM.png)

## Planned Features

* Regex matching
* Ajax import
* Ajax validation and logging of import
* Include plugins resources on plugin page only

## Import Support

The following file formats are supported .csv .txt

## Import Configuration

Delimiter defaults to ';'
Custom delimiter can be configured:

```php
return array(
	'delimiter' => ';'
);
```