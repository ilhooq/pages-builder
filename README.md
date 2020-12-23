# Piko page builder

Piko page builder is a minimal CMS to manage and build web pages directly in the brower using [GrapeJs](https://github.com/artf/grapesjs).

## Installation

1 - Install the project via composer:

```bash
composer create-project ilhooq/page-builder yourproject
```

2 - Rename config.exemple.php to config.php and edit the configuration in this file.

3 - Copy the project to the root of your web directory or lunch the PHP built-in web server (php -S localhost:8080).

4 - Go to your browser to the `/install` page (ex : http://localhost:8080/install) then it's ready.

## TIPS

## Development mode

To activate development mode in order to have more debug info, create a file `.env` at the root of your project and edit the file with this content :

```
PIKO_DEBUG        = 1
PIKO_ENV          = dev
```

### Contact form

To integrate a contact form in your web page, you can insert this code : `{{CONTACT_FORM}}` when you edit your page.

