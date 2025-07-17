# NHR SmartSync Table

WordPress plugin that fetches data from remote APIs and displays it in tables and Gutenberg blocks.

## Table of Contents

- [Overview](#overview)
- [Technology Stack](#technology-stack)
- [Development Setup](#development-setup)
- [Code Quality](#code-quality)
- [Distribution](#distribution)
- [Quick Reference](#quick-reference)

## Overview

Fetches data from remote APIs and displays it in WordPress through:
- Admin dashboard tables (sortable, paginated)
- Gutenberg blocks for posts/pages
- Caching system with TTL control
- AJAX refresh functionality
- WP-CLI support

**Use Cases**: E-commerce catalogs, business dashboards, content feeds, SaaS data display

## Technology Stack

- **PHP 7.4+** / **WordPress 6.0+**
- **JavaScript/React** (Gutenberg blocks)
- **Composer** (PHP dependencies)
- **npm/@wordpress/scripts** (JS build tools)
- **PHPCS** (WordPress Coding Standards)

## Development Setup

### Install Dependencies
```bash
# PHP dependencies
composer install

# JavaScript dependencies
cd includes/blocks/table-block/
npm install
```

### Development Commands
```bash
# Watch for changes (development)
npm run dev

# Production build
npm run build
```

## Code Quality

### PHPCS Commands
```bash
# Check coding standards
./vendor/bin/phpcs

# Auto-fix issues
./vendor/bin/phpcbf
```

**Status**: 0 errors, 0 warnings (100% WordPress Coding Standards compliant)

## Distribution

### Create Production Build
```bash
# Install production dependencies
composer install --no-dev --optimize-autoloader

# Build assets
cd includes/blocks/table-block/
npm run build

# Create distribution archive
wp dist-archive .
```

## Quick Reference

```bash
# Setup
composer install
cd includes/blocks/table-block/ && npm install

# Development
npm run dev                    # Watch for changes
npm run build                  # Production build

# Code quality
./vendor/bin/phpcs            # Check standards
./vendor/bin/phpcbf           # Auto-fix issues

# Distribution
composer install --no-dev
npm run build
wp dist-archive .
```

### Troubleshooting
- **PHPCS Issues**: Run `./vendor/bin/phpcbf`
- **Block Not Loading**: Check `npm run build` completed
- **Cache Issues**: Use `wp nhrst-table-api refresh`
