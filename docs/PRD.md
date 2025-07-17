# NHR SmartSync Table

WordPress plugin that transforms remote API data into dynamic tables and Gutenberg blocks.

## Quick Start

```bash
npm run dev  # Install everything and start developing
```

## Core Features
- Admin dashboard tables (sortable, paginated)
- Gutenberg blocks for frontend display
- Intelligent caching with TTL
- Real-time AJAX updates
- WP-CLI integration

## Requirements
- PHP 7.4+ / WordPress 6.0+
- Node.js 16+ / Composer

## Commands

```bash
npm run dev      # Develop (installs deps + file watching)
npm run build    # Production build + ZIP
npm run test     # Code quality checks
npm run clean    # Clean build artifacts
```

## File Structure
```
nhrst-smartsync-table/
├── includes/blocks/table-block/src/    # Source files
├── includes/Admin/                     # Admin interface
├── includes/Frontend/                  # Frontend functionality
└── nhrst-smartsync-table.php          # Main plugin file
```

## Troubleshooting
```bash
npm run clean && npm run dev    # Fix most issues
npm run test                    # Check code quality
```
