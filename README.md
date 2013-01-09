# Vafpress Theme Options Framework

Wordpress theme options framework you'll love.

## Available Field Types

* TextBox
* TextArea
* CheckBox
* CheckImage
* RadioButton
* RadioImage
* Select
* MultiSelect
* Slider
* Toggle
* Upload
* DatePicker
* ColorPicker

## Available Validation

* alphabet
* alphanumeric
* numeric
* email
* url
* maxlength
* minlength
* maxselected
* minselected
* required

## Using it in your theme

Include the bootstrap.php into your functions.php, configure everything under vafpress/config directory.

## Configuring XML or Array

For now please checkout our options.xml example, we tried to put everything into the example.
The framework actually uses options.php to build the options panel, but to make it lot easier, you can build the options
on options.xml, and then under vafpress directory, you'll find `vp`, a CLI helper tool to convert `options.xml` to options.php
just by running `php vp convert` in your console.

## Contact Us

And you can contact us at contact [@] vafour.com
