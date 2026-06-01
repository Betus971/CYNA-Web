#!/bin/bash
# deploy-backend.sh — Mise à jour du backend CYNA après un git push
# Usage : bash deploy-backend.sh

set -e

DEPLOY_DIR="/var/www/Cyna/CYNA-Web"
echo "🚀 Déploiement backend CYNA..."

cd "$DEPLOY_DIR"

echo "📥 [1/5] git pull..."
git pull origin main

echo "📦 [2/5] composer install (prod)..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🗄️  [3/5] Migrations Doctrine..."
php bin/console doctrine:migrations:migrate --no-interaction --env=prod

echo "🧹 [4/5] Cache clear + warmup..."
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

echo "🔐 [5/5] Permissions..."
chown -R www-data:www-data var/
chmod -R 775 var/

echo "✅ Backend déployé avec succès !"
