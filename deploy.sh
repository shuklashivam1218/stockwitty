#!/bin/bash
# ============================================================
#  StockWitty — Hostinger Deploy Script
#  Place this file inside the stockwitty/ folder on the server
#  Run from inside stockwitty/:  bash deploy.sh
#  Migrations always run automatically (safe — Laravel skips
#  migrations that already ran).
# ============================================================

set -e

# Auto-detect paths from script location
# deploy.sh lives inside stockwitty/, so:
#   APP_DIR    = the stockwitty/ folder itself
#   PUBLIC_HTML = ../public_html  (sibling of stockwitty/)
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="$SCRIPT_DIR"
PUBLIC_HTML="$(dirname "$SCRIPT_DIR")/public_html"

# ── Colour helpers ────────────────────────────────────────────
GREEN='\033[0;32m'; YELLOW='\033[1;33m'; RED='\033[0;31m'; NC='\033[0m'
ok()   { echo -e "${GREEN}✔ $1${NC}"; }
info() { echo -e "${YELLOW}→ $1${NC}"; }
fail() { echo -e "${RED}✘ $1${NC}"; exit 1; }

# ── Find best available PHP binary ───────────────────────────
PHP_BIN=""
# Check known CloudLinux/Hostinger paths first (8.3 → 8.2 → fallback)
for fullpath in \
    /opt/alt/php83/usr/bin/php \
    /opt/alt/php82/usr/bin/php \
    /opt/alt/php84/usr/bin/php \
    /opt/alt/php85/usr/bin/php; do
    if [[ -x "$fullpath" ]]; then
        VER=$("$fullpath" -r 'echo PHP_VERSION_ID;' 2>/dev/null || echo 0)
        if [[ "$VER" -ge 80200 ]]; then
            PHP_BIN="$fullpath"
            break
        fi
    fi
done
# Fallback to PATH-based detection
if [[ -z "$PHP_BIN" ]]; then
    for bin in php83 php82 php; do
        if command -v "$bin" &>/dev/null; then
            PHP_BIN="$bin"
            break
        fi
    done
fi
[[ -z "$PHP_BIN" ]] && fail "No PHP binary found."
info "Using PHP binary: $PHP_BIN ($($PHP_BIN -r 'echo phpversion();'))"

# ── Composer install ─────────────────────────────────────────
# Run composer itself (and its @php post-install scripts, like
# package:discover) under $PHP_BIN — the plain `composer` command
# otherwise resolves to whatever old system PHP is on PATH.
info "Running composer install ..."
cd "$APP_DIR"
COMPOSER_BIN="$(command -v composer || command -v composer.phar || true)"
if [[ -n "$COMPOSER_BIN" ]]; then
    "$PHP_BIN" "$COMPOSER_BIN" install --no-dev --optimize-autoloader --ignore-platform-reqs --no-interaction || true
else
    composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-interaction || true
fi
ok "Composer install done"

# ── Disable Composer platform version check ───────────────────
# hPanel web PHP (8.3) ≠ SSH CLI PHP — bypass the version gate
# so artisan commands run fine on whichever CLI PHP is available.
if [[ -f "$APP_DIR/vendor/composer/platform_check.php" ]]; then
    echo '<?php // platform check disabled for CLI deploy' \
        > "$APP_DIR/vendor/composer/platform_check.php"
    ok "Composer platform check disabled"
fi

echo ""
echo "============================================"
echo "   StockWitty Deployment"
echo "============================================"
echo ""

# ── Patch .env for production ────────────────────────────────
APP_DOMAIN="stockswitty.com"

info "Patching .env for production ..."
ENV_FILE="$APP_DIR/.env"

# Helper: set or replace a key in .env
env_set() {
    local key="$1" val="$2"
    if grep -q "^${key}=" "$ENV_FILE"; then
        sed -i "s|^${key}=.*|${key}=${val}|" "$ENV_FILE"
    else
        echo "${key}=${val}" >> "$ENV_FILE"
    fi
}

env_set APP_ENV          production
env_set APP_DEBUG        false
env_set APP_URL          "https://${APP_DOMAIN}"
env_set APP_TIMEZONE     Asia/Kolkata
env_set CACHE_STORE      file
env_set QUEUE_CONNECTION sync
env_set SESSION_DRIVER   file


# Auto-quote DB_PASSWORD if it contains # (# without quotes = comment in .env)
if grep -qP '^DB_PASSWORD=[^"].*#' "$ENV_FILE"; then
    sed -i 's|^DB_PASSWORD=\(.*\)$|DB_PASSWORD="\1"|' "$ENV_FILE"
    ok "DB_PASSWORD auto-quoted (contained # character)"
fi

ok ".env patched for production"

# ── Sanity checks ─────────────────────────────────────────────
[[ -d "$APP_DIR" ]]         || fail "App dir not found: $APP_DIR"
[[ -d "$PUBLIC_HTML" ]]     || fail "public_html not found: $PUBLIC_HTML"
[[ -f "$APP_DIR/artisan" ]] || fail "artisan not found — did you upload the full Laravel project to stockwitty/?"
[[ -f "$APP_DIR/.env" ]]    || fail ".env not found in $APP_DIR — upload your .env file first."
[[ -d "$APP_DIR/public" ]]  || fail "stockwitty/public/ folder missing — upload the full project including the public/ folder."

# ── Clean up any stray directories in public_html first ───────
info "Cleaning public_html ..."
[[ -d "$PUBLIC_HTML/public" ]] && rm -rf "$PUBLIC_HTML/public"
ok "public_html cleaned"

# ── Sync public/ → public_html/ ───────────────────────────────
info "Syncing public assets to public_html ..."
rsync -a --delete --force \
    --exclude="index.php" \
    --exclude="index.production.php" \
    "$APP_DIR/public/" "$PUBLIC_HTML/"
ok "Public assets synced"

# ── Write production index.php directly ──────────────────────
info "Writing production index.php ..."
cat > "$PUBLIC_HTML/index.php" <<'INDEXEOF'
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../stockwitty/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../stockwitty/vendor/autoload.php';

(require_once __DIR__.'/../stockwitty/bootstrap/app.php')
    ->handleRequest(Request::capture());
INDEXEOF
ok "index.php written to public_html"

# ── Storage symlink ───────────────────────────────────────────
info "Setting up storage symlink ..."
STORAGE_LINK="$PUBLIC_HTML/storage"
if [[ -L "$STORAGE_LINK" ]]; then
    rm "$STORAGE_LINK"
fi
ln -sfn "$APP_DIR/storage/app/public" "$STORAGE_LINK"
ok "Storage symlink: public_html/storage → stockwitty/storage/app/public"

# ── Permissions ───────────────────────────────────────────────
info "Setting permissions ..."
chmod -R 775 "$APP_DIR/storage"
chmod -R 775 "$APP_DIR/bootstrap/cache"
ok "Permissions set"

# ── Laravel cache ─────────────────────────────────────────────
info "Clearing Laravel caches ... (using $PHP_BIN)"
cd "$APP_DIR"
$PHP_BIN artisan config:clear 2>/dev/null || true
$PHP_BIN artisan cache:clear 2>/dev/null || true
$PHP_BIN artisan view:clear 2>/dev/null || true
$PHP_BIN artisan route:clear 2>/dev/null || true
ok "Caches cleared"

# ── Migrations (always run — Laravel skips ones already applied) ─
info "Running migrations ..."
$PHP_BIN artisan migrate --force
ok "Migrations done"

# ── Warm caches ───────────────────────────────────────────────
info "Warming production caches ..."
$PHP_BIN artisan config:cache 2>/dev/null || true
$PHP_BIN artisan route:cache 2>/dev/null || true
$PHP_BIN artisan view:cache 2>/dev/null || true
ok "Caches warmed"

# ── Convenience wrapper for manual artisan commands ───────────
# Regenerated every deploy so it always points at the correct PHP
# binary — use this instead of plain `php artisan ...` by hand,
# since the default `php` on this server is too old for Laravel.
cat > "$APP_DIR/artisan-cli.sh" <<EOF
#!/bin/bash
$PHP_BIN artisan "\$@"
EOF
chmod +x "$APP_DIR/artisan-cli.sh"
ok "Wrote artisan-cli.sh (use: bash artisan-cli.sh migrate)"

echo ""
echo "============================================"
echo -e "${GREEN}   Deployment complete!${NC}"
echo "============================================"
echo -e "${YELLOW}   PHP binary in use: $PHP_BIN${NC}"
echo -e "${YELLOW}   For manual artisan commands, run: bash artisan-cli.sh <command>${NC}"
echo "============================================"
echo ""
