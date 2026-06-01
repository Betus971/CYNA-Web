# 🚀 Guide de déploiement CYNA — VPS

> Deux sous-domaines à créer dans ton panneau DNS :
> - `api.cyna.tondomaine.fr` → backend Symfony
> - `app.cyna.tondomaine.fr` → frontend React
>
> Remplace **`tondomaine.fr`** par ton vrai domaine partout dans ce guide.

---

## 📋 Prérequis (déjà installés sur ton VPS)

- ✅ Nginx
- ✅ PHP 8.2+ avec php-fpm
- ✅ PostgreSQL
- ✅ Node.js + npm

Vérifie les versions :
```bash
php -v
node -v
npm -v
psql --version
nginx -v
```

---

## 1️⃣ Créer les enregistrements DNS

Dans ton panneau de registrar (OVH, Gandi, Cloudflare, etc.) :

| Type | Nom | Valeur |
|------|-----|--------|
| A | `api.cyna` | IP de ton VPS |
| A | `app.cyna` | IP de ton VPS |

> Attends 5–15 min que la propagation DNS soit faite avant de tester.

---

## 2️⃣ Configurer le backend Symfony (CYNA-Web)

```bash
cd /var/www/Cyna/CYNA-Web
```

### Installer les dépendances Composer (mode production)
```bash
composer install --no-dev --optimize-autoloader
```

### Créer le fichier de configuration local
```bash
cp .env .env.local
nano .env.local
```

Remplir dans `.env.local` :
```dotenv
APP_ENV=prod
APP_SECRET=CHANGE_MOI_AVEC_UNE_VRAIE_VALEUR_ALEATOIRE

DATABASE_URL="postgresql://cyna_user:MOT_DE_PASSE@127.0.0.1:5432/cyna_db"

# JWT
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=ta_passphrase_jwt

# Mailer (Brevo)
MAILER_DSN=smtp://ton_user:ton_pass@smtp-relay.brevo.com:587
MAIL_FROM=no-reply@tondomaine.fr

# URL du front (pour les liens dans les emails)
FRONTEND_URL=https://app.cyna.tondomaine.fr

# Stripe
STRIPE_SECRET_KEY=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Google OAuth2
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...

# Gemini IA
GEMINI_API_KEY=...

# CORS — autorise uniquement le frontend
CORS_ALLOW_ORIGIN=^https://app\.cyna\.tondomaine\.fr$
```

### Générer les clés JWT
```bash
php bin/console lexik:jwt:generate-keypair
```

### Créer la base de données PostgreSQL
```bash
# Se connecter en tant que postgres
sudo -u postgres psql

# Dans psql :
CREATE USER cyna_user WITH PASSWORD 'MOT_DE_PASSE';
CREATE DATABASE cyna_db OWNER cyna_user;
GRANT ALL PRIVILEGES ON DATABASE cyna_db TO cyna_user;
\q
```

### Appliquer les migrations
```bash
php bin/console doctrine:migrations:migrate --no-interaction --env=prod
```

### Charger les fixtures (données de démo)
```bash
php bin/console doctrine:fixtures:load --no-interaction --env=prod
```

### Vider et préchauffer le cache
```bash
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod
```

### Permissions sur les dossiers
```bash
# www-data doit pouvoir écrire dans var/ et public/
chown -R www-data:www-data /var/www/Cyna/CYNA-Web/var
chown -R www-data:www-data /var/www/Cyna/CYNA-Web/public
chmod -R 755 /var/www/Cyna/CYNA-Web
chmod -R 775 /var/www/Cyna/CYNA-Web/var
```

---

## 3️⃣ Configurer le frontend React (CYNA-frontend-react)

```bash
cd /var/www/Cyna/CYNA-frontend-react
```

### Créer le fichier d'environnement production
```bash
nano .env.production
```

Contenu :
```dotenv
VITE_API_BASE_URL=https://api.cyna.tondomaine.fr
```

### Installer les dépendances et builder
```bash
npm install
npm run build
```

Le dossier `dist/` est créé — c'est lui que Nginx va servir.

---

## 4️⃣ Configurer Nginx

### Backend — `api.cyna.tondomaine.fr`

```bash
nano /etc/nginx/sites-available/cyna-api
```

Coller la config du fichier `nginx/cyna-api.conf` (voir section 5).

```bash
ln -s /etc/nginx/sites-available/cyna-api /etc/nginx/sites-enabled/
```

### Frontend — `app.cyna.tondomaine.fr`

```bash
nano /etc/nginx/sites-available/cyna-app
```

Coller la config du fichier `nginx/cyna-app.conf` (voir section 5).

```bash
ln -s /etc/nginx/sites-available/cyna-app /etc/nginx/sites-enabled/
```

### Tester et recharger Nginx
```bash
nginx -t
systemctl reload nginx
```

---

## 5️⃣ Certificats SSL (Let's Encrypt)

```bash
# Installer certbot si pas déjà fait
apt install certbot python3-certbot-nginx -y

# Générer les certificats pour les deux sous-domaines
certbot --nginx -d api.cyna.tondomaine.fr -d app.cyna.tondomaine.fr
```

Certbot modifie automatiquement les configs Nginx pour ajouter HTTPS + redirection HTTP→HTTPS.

> Renouvellement automatique : déjà configuré par certbot via un timer systemd. Vérifie avec `certbot renew --dry-run`.

---

## 6️⃣ Workflow de mise à jour (après un git push)

### Mettre à jour le backend
```bash
cd /var/www/Cyna/CYNA-Web
bash deploy-backend.sh
```

### Mettre à jour le frontend
```bash
cd /var/www/Cyna/CYNA-frontend-react
bash deploy-frontend.sh
```

Les deux scripts sont dans leurs dossiers respectifs (voir section suivante).

---

## 7️⃣ Vérifications finales

```bash
# API accessible ?
curl https://api.cyna.tondomaine.fr/api/saas_services

# Frontend chargé ?
curl -I https://app.cyna.tondomaine.fr

# Logs Nginx si problème
tail -f /var/log/nginx/cyna-api-error.log
tail -f /var/log/nginx/cyna-app-error.log

# Logs PHP-FPM
tail -f /var/log/php8.2-fpm.log
```

---

## 🔒 Sécurité en production

- Ne jamais versionner `.env.local` (déjà dans `.gitignore`)
- Ne jamais versionner `config/jwt/private.pem`
- `APP_ENV=prod` désactive le profiler Symfony et les messages d'erreur détaillés
- Stripe : utiliser les clés `sk_live_` en prod (pas `sk_test_`)
- Restreindre l'accès à `/admin` si besoin par IP dans Nginx

---

## 📦 Structure finale sur le VPS

```
/var/www/Cyna/
├── CYNA-Web/                    ← Backend Symfony (API)
│   ├── .env.local               ← Config prod (jamais versionné)
│   ├── config/jwt/              ← Clés JWT (jamais versionnées)
│   ├── var/                     ← Cache + logs Symfony
│   ├── public/                  ← Point d'entrée Nginx
│   └── deploy-backend.sh        ← Script de mise à jour
│
└── CYNA-frontend-react/         ← Frontend React
    ├── .env.production          ← URL API prod
    ├── dist/                    ← Build Vite (servi par Nginx)
    └── deploy-frontend.sh       ← Script de mise à jour
```
