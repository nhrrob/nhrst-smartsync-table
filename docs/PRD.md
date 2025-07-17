# NHR SmartSync Table - Product Requirements Document (PRD)

A production-ready WordPress plugin that transforms remote API data into dynamic, interactive tables and Gutenberg blocks for modern web applications.

## Table of Contents

- [Product Overview](#product-overview)
- [Technology Stack](#technology-stack)
- [Development Setup](#development-setup)
- [Build System](#build-system)
- [Code Quality & Standards](#code-quality--standards)
- [Distribution & Deployment](#distribution--deployment)
- [Development Workflow](#development-workflow)
- [Quick Reference](#quick-reference)
- [Troubleshooting](#troubleshooting)

## Product Overview

### Core Functionality
NHR SmartSync Table fetches data from remote APIs and displays it through:
- **Admin Dashboard Tables**: Sortable, paginated data management interface
- **Gutenberg Blocks**: Frontend display blocks for posts/pages
- **Intelligent Caching**: TTL-controlled caching system for performance
- **Real-time Updates**: AJAX refresh functionality
- **CLI Integration**: WP-CLI support for automation

### Target Use Cases
- **E-commerce**: Product catalogs, inventory displays, pricing tables
- **Business Intelligence**: KPI dashboards, analytics displays, reporting
- **Content Management**: News feeds, blog aggregation, social media feeds
- **SaaS Applications**: User data, subscription tables, usage metrics

### Competitive Advantages
- Zero-configuration API integration
- WordPress-native user experience
- Performance-optimized caching
- Developer-friendly extensibility
- Enterprise-grade code quality

## Technology Stack

### Core Technologies
- **Backend**: PHP 7.4+ with WordPress 6.0+ compatibility
- **Frontend**: JavaScript/React for Gutenberg blocks
- **Build Tools**: npm/@wordpress/scripts for modern JS development
- **Dependency Management**: Composer for PHP, npm for JavaScript
- **Code Quality**: PHPCS with WordPress Coding Standards

### Prerequisites
- **PHP 7.4+** with Composer installed
- **Node.js 16+** with npm
- **WP-CLI** for archive creation and automation
- **WordPress 6.0+** environment

## Development Setup

### Initial Setup
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
cd includes/blocks/table-block/
npm install
```

### Verify Prerequisites
```bash
npm run check:deps
```

## Build System

### Quick Start Commands

#### Production Build
```bash
npm run build
```
Creates a production-ready ZIP file for distribution.

#### Development Build
```bash
npm run build:dev
```
Same as production but keeps development dependencies.

### Available Build Commands

#### Core Commands
| Command | Description |
|---------|-------------|
| `npm run build` | Complete production build with archive |
| `npm run build:dev` | Development build (no linting, keeps dev deps) |
| `npm run dev` | Start file watcher for development |
| `npm run test` | Run all linting (JavaScript + PHP) |
| `npm run clean` | Clean all build artifacts |

#### Individual Commands
| Command | Description |
|---------|-------------|
| `npm run build:js` | Build JavaScript assets only |
| `npm run lint:js` | Lint JavaScript |
| `npm run lint:js:fix` | Lint and fix JavaScript |
| `npm run phpcs` | Run PHP CodeSniffer |
| `npm run phpcs:fix` | Fix PHP code style |
| `npm run check:deps` | Validate prerequisites |

### Build Output
- **JavaScript Assets**: `includes/blocks/table-block/build/`
- **Distribution Archive**: `../nhrst-smartsync-table.1.0.0.zip`

## Code Quality & Standards

### PHP Code Standards
```bash
# Check coding standards
./vendor/bin/phpcs

# Auto-fix issues
./vendor/bin/phpcbf
```

**Current Status**: 0 errors, 0 warnings (100% WordPress Coding Standards compliant)

### JavaScript Standards
```bash
# Lint JavaScript
npm run lint:js

# Auto-fix JavaScript issues
npm run lint:js:fix
```

### Quality Assurance
- WordPress Coding Standards enforcement
- ESLint configuration for JavaScript
- Automated code formatting
- Pre-commit validation hooks

## Distribution & Deployment

### Production Build Process
```bash
# Install production dependencies
composer install --no-dev --optimize-autoloader

# Build optimized assets
cd includes/blocks/table-block/
npm run build

# Create distribution archive
wp dist-archive .
```

### Distribution Exclusions
Development files automatically excluded from distribution:
- Source files (`src/`, `node_modules/`)
- Build configuration (`package.json`, `webpack.config.js`)
- Documentation files (`docs/`, `README.md`)
- Development tools (`.eslintrc`, `phpcs.xml`)

See `.distignore` for complete exclusion list.

## Development Workflow

### 1. Initial Setup
```bash
npm run dev
```
This command:
- Installs all dependencies
- Starts file watching for automatic rebuilds
- Keeps development dependencies available

### 2. Active Development
The `npm run dev` command automatically:
- Watches `includes/blocks/table-block/src/` directory
- Rebuilds assets when files change
- Maintains development environment

### 3. Pre-commit Validation
```bash
npm run test
```
Runs comprehensive linting for both JavaScript and PHP code.

### 4. Release Preparation
```bash
npm run build
```
Creates production-ready distribution package.

## Quick Reference

### Essential Commands
```bash
# Initial setup
composer install
cd includes/blocks/table-block/ && npm install

# Development workflow
npm run dev                    # Start development with file watching
npm run build                  # Create production build
npm run test                   # Run all quality checks

# Code quality
./vendor/bin/phpcs            # Check PHP standards
./vendor/bin/phpcbf           # Auto-fix PHP issues
npm run lint:js:fix           # Auto-fix JavaScript issues

# Distribution
npm run build                 # Complete production build with archive
```

### File Structure
```
wp-content/plugins/nhrst-smartsync-table/
├── includes/
│   ├── blocks/table-block/
│   │   ├── src/              # Source files
│   │   ├── build/            # Built assets
│   │   └── package.json      # JS dependencies
│   ├── Admin/                # Admin interface
│   ├── Frontend/             # Frontend functionality
│   └── functions.php         # Core functions
├── assets/                   # Static assets
├── docs/                     # Documentation
├── composer.json             # PHP dependencies
└── nhrst-smartsync-table.php # Main plugin file
```

## Troubleshooting

### Common Issues & Solutions

| Issue | Symptoms | Solution |
|-------|----------|----------|
| **Missing Dependencies** | Build fails, commands not found | `npm run check:deps` |
| **Build Failures** | Compilation errors, missing files | `npm run clean && npm run dev` |
| **Code Quality Issues** | Linting errors, style violations | `npm run lint:js:fix && npm run phpcs:fix` |
| **File Watcher Problems** | Changes not detected | Stop `npm run dev` and restart |
| **Block Not Loading** | Gutenberg block missing | Verify `npm run build` completed successfully |
| **Cache Issues** | Stale data displayed | Use `wp nhrst-table-api refresh` |
| **PHPCS Violations** | PHP coding standard errors | Run `./vendor/bin/phpcbf` |
| **Archive Creation Fails** | Distribution build errors | Ensure WP-CLI is installed and accessible |

### Debug Commands
```bash
# Validate environment
npm run check:deps

# Clean and rebuild
npm run clean
npm run dev

# Check all code quality
npm run test

# Manual PHP standards check
./vendor/bin/phpcs --standard=WordPress includes/

# Manual JavaScript linting
npm run lint:js
```

### Performance Optimization
- Use production builds for live sites
- Enable WordPress object caching
- Configure appropriate TTL values
- Monitor API response times
- Implement rate limiting for API calls

---

## Support & Contribution

### Getting Help
- Check this PRD for common solutions
- Review code comments for implementation details
- Use WordPress debugging tools for runtime issues

### Development Standards
- Follow WordPress Coding Standards (PHP)
- Use ESLint configuration (JavaScript)
- Write meaningful commit messages
- Test thoroughly before submitting changes

### Quality Gates
All code must pass:
- ✅ PHPCS validation (0 errors, 0 warnings)
- ✅ ESLint validation
- ✅ Build process completion
- ✅ Manual testing verification
