#!/bin/sh
set -e

PORT="${PORT:-80}"

# Update Apache port binding to Render dynamic $PORT
sed -i "s/80/${PORT}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

echo "Starting Apache on port ${PORT}..."
exec apache2-foreground
