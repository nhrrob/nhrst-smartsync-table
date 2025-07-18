# NHR SmartSync Table

WordPress plugin that transforms remote API data into dynamic tables and Gutenberg blocks.

**Demo API**: `https://miusage.com/v1/challenge/1/`

## Quick Start

```bash
npm run dev  # Install everything and start developing
```

## Core Features
- Admin dashboard tables (sortable, paginated) - fetches data via `wp_remote_get()` with transient caching
- Gutenberg block "NHR SmartSync Table" (`nhrst-smartsync-table/table-block`) for frontend display
- Intelligent caching with TTL
- Real-time AJAX updates
- WP-CLI integration (`wp nhrst-table-api refresh` - clears API cache)

## Requirements
- PHP 7.4+ / WordPress 6.0+
- Node.js 16+ / Composer

## Commands

```bash
npm run dev      # Install deps + start development
npm run build    # Production build
npm run test     # Run tests
npm run dist     # Create distribution package
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
