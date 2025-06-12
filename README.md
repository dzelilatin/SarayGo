# SarayGo
Repository for a Web application project for the purposes of Introduction to Web Programming course.

# SarayGo API Documentation

## Swagger UI Setup

If you're having issues with Swagger UI after cloning the repository and switching to the milestone3 branch, follow these steps:

1. Navigate to the backend directory:
```bash
cd backend
```

2. Install swagger-php:
```bash
composer require zircote/swagger-php
```

3. Edit your `composer.json` file to include these dependencies:
```json
{
  "require": {
    "flightphp/core": "^3.15",
    "zircote/swagger-php": "^3.3"
  }
}
```

4. Run composer install:
```bash
composer install
```

### Troubleshooting

If you see any version conflicts in the terminal, run:
```bash
composer update
```

This happens because you edited composer.json manually, but the composer.lock file still references older versions.

### Verify Installation

To verify that swagger-php is installed correctly, run:
```bash
composer show zircote/swagger-php
```

You should see something like:
```
versions : * 3.3.7
```

If you don't see the correct version, force a specific version by running:
```bash
composer require zircote/swagger-php:^3.3 --no-cache
```

