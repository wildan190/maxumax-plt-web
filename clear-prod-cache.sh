#!/bin/bash
# Production cache clearing script
# Run: bash clear-prod-cache.sh

echo "🔄 Clearing production caches..."
php artisan cache:clear
echo "✅ Application cache cleared"

php artisan view:clear
echo "✅ View cache cleared"

php artisan config:clear
echo "✅ Config cache cleared"

php artisan route:clear
echo "✅ Route cache cleared"

echo "✨ All caches cleared successfully!"
