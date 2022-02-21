# comp5531-w22-proj

## Requirements
- PHP 8.0
- MySQL

## How to install? 

`config.php` should not be committed to the VCS, it should contain values that are specific to your local machine.
```bash
cp config.php.example config.php
```

## How to run?

```bash
php -S localhost:4000
```

Point your web browser to a page defined in the `public` directory, e.g. http://localhost:4000/public/index.php

During the initial run, run the `install.php` file to setup the database by navigating to http://localhost:4000/install.php

