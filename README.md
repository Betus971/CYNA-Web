<div align="center">

# 🛡️ CYNA — Backend API

**Plateforme e-commerce B2B de cybersécurité SaaS**  
*SOC Managed · EDR · XDR · Réseau Sécurisé · Formation Cyber*

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-7.4-000000?style=flat-square&logo=symfony&logoColor=white)
![API Platform](https://img.shields.io/badge/API_Platform-4.3-38B2AC?style=flat-square)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-17-336791?style=flat-square&logo=postgresql&logoColor=white)
![Stripe](https://img.shields.io/badge/Stripe-intégré-635BFF?style=flat-square&logo=stripe&logoColor=white)
![JWT](https://img.shields.io/badge/Auth-JWT_+_2FA-F7B731?style=flat-square)

</div>

---

## 📖 Présentation

CYNA est une plateforme self-service permettant aux entreprises d'acheter et gérer des abonnements à des services de cybersécurité (SOC, EDR, XDR, réseau, formation). Ce dépôt contient l'**API REST backend** construite avec Symfony 7.4 et API Platform 4.

Le frontend React consommant cette API est disponible sur [CYNA-frontend-react](https://github.com/nairo91/CYNA-frontend-react).

---

## ⚡ Stack technique

| Composant | Technologie |
|-----------|-------------|
| Langage | PHP 8.2+ |
| Framework | Symfony 7.4 |
| API | API Platform 4.3 — REST JSON-LD + OpenAPI/Swagger |
| Base de données | PostgreSQL 17 + Doctrine ORM 3 |
| Authentification | JWT stateless (LexikJWT) + 2FA TOTP + 2FA email |
| OAuth2 | Google SSO (KnpU + league/oauth2-google) |
| Paiement | Stripe PHP SDK v20 (PaymentIntent + Webhooks) |
| Emails | Symfony Mailer + Brevo (SMTP transactionnel) |
| PDF | DomPDF v3.1 (génération factures A4) |
| Chatbot IA | Mistral IA API |
| Géolocalisation | API Adresse BAN + API Géo (DINUM, sans token) |
| Rate Limiting | symfony/rate-limiter (sliding window) |
| CORS | NelmioCorsBundle |
| Backoffice | EasyAdmin v5 |
| Tests | PHPUnit 12.5 |

---

## 🗂️ Fonctionnalités

### 🔐 Authentification & Sécurité
- Inscription avec vérification email (token expirant)
- Connexion JWT stateless
- **2FA TOTP** (Google Authenticator) + **2FA par email** (code 6 chiffres, 10 min)
- Notification email à chaque nouvelle connexion (IP, navigateur, OS)
- Réinitialisation de mot de passe par email
- **Google SSO** (OAuth2 — crée ou retrouve le compte automatiquement)
- Rate limiting sur les endpoints sensibles (login, reset, contact, chatbot)

### 🛍️ Catalogue & Commandes
- CRUD produits (SaasService) avec catégories, prix, disponibilité, priorité
- Recherche avancée avec facettes (texte, prix, catégorie, tri, pagination)
- Panier anonyme par token (fusion possible après connexion)
- Tunnel de commande : panier → adresse → PaymentIntent Stripe → confirmation
- Webhook Stripe (`payment_intent.succeeded` / `payment_intent.payment_failed`)
- Activation automatique des abonnements après paiement

### 🧾 Factures
- Création automatique de la facture après paiement confirmé (idempotent)
- Génération PDF A4 (DomPDF) avec logo, détail des lignes, TVA 20%
- Endpoint de téléchargement `GET /api/invoices/{id}/download`

### 📧 Emails transactionnels
- Vérification d'adresse email
- Réinitialisation de mot de passe
- Code 2FA email
- Notification de connexion (IP + navigateur)
- **Confirmation de commande** avec récapitulatif et numéro de facture

### 🗺️ Géolocalisation (API DINUM)
- `GET /api/geo/address?q=` — autocomplétion d'adresses complètes (BAN)
- `GET /api/geo/communes?q=` — recherche de communes
- `GET /api/geo/regions` — liste des régions
- `GET /api/geo/departements` — liste des départements
- Endpoints publics, sans token, fallback silencieux si indisponible

### 🤖 Chatbot IA
- Intégration Google Gemini (multi-tours, FR/EN)
- Escalade automatique et manuelle vers agent humain
- Historique des conversations stocké en base

### 🖥️ Backoffice EasyAdmin
- Accessible sur `/admin` (session-based, ROLE_ADMIN)
- CRUD complet : Utilisateurs, Catégories, Services, Commandes, Factures, Codes promo, Messages contact, Conversations chatbot, Carrousel, Textes dynamiques
- Dashboard KPI : revenus, nouveaux utilisateurs, ventes par jour et par catégorie

---

## 🚀 Installation

### Prérequis
- PHP 8.2+
- Composer
- PostgreSQL 17
- Node.js (optionnel, pour le frontend)

### 1. Cloner et installer les dépendances

```bash
git clone https://github.com/Betus971/CYNA-Web.git
cd CYNA-Web
composer install
```

### 2. Configurer l'environnement

```bash
cp .env .env.local
```

Renseigner dans `.env.local` :

```dotenv
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/cyna_db"
APP_SECRET=your_secret_here

# JWT
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=your_passphrase

# Mailer (Brevo)
MAILER_DSN=smtp://...
MAIL_FROM=no-reply@cyna.fr
FRONTEND_URL=http://localhost:5173

# Stripe
STRIPE_SECRET_KEY=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Google OAuth2
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...

# Gemini IA
GEMINI_API_KEY=...
```

### 3. Générer les clés JWT

```bash
php bin/console lexik:jwt:generate-keypair
```

### 4. Créer la base de données et appliquer les migrations

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 5. (Optionnel) Charger les fixtures de démonstration

```bash
php bin/console doctrine:fixtures:load
```

> Crée 5 catégories, 15 services SaaS, des slides carrousel et des textes dynamiques.

### 6. Créer un compte administrateur

```bash
php bin/console app:create-admin admin@cyna.fr motdepasse
```

### 7. Lancer le serveur

```bash
symfony server:start
# ou
php -S localhost:8000 -t public/
```

L'API est disponible sur `http://localhost:8000/api`  
La documentation Swagger : `http://localhost:8000/api/docs`  
Le backoffice EasyAdmin : `http://localhost:8000/admin`

---

## 📡 Endpoints principaux

| Méthode | Endpoint | Accès | Description |
|---------|----------|-------|-------------|
| `POST` | `/api/login_check` | Public | Connexion JWT |
| `POST` | `/api/users` | Public | Inscription |
| `GET` | `/api/saas_services` | Public | Liste des services |
| `GET` | `/api/catalog/search` | Public | Recherche avancée |
| `GET` | `/api/home` | Public | Données homepage |
| `POST` | `/api/geo/address?q=` | Public | Autocomplétion adresse |
| `POST` | `/api/checkout/payment-intent` | Auth | Créer un PaymentIntent |
| `POST` | `/api/stripe/webhook` | Public | Webhook Stripe |
| `GET` | `/api/invoices/{id}/download` | Auth | Télécharger une facture PDF |
| `GET` | `/api/me` | Auth | Profil utilisateur |
| `GET` | `/api/orders` | Auth | Historique des commandes |
| `POST` | `/api/chatbot/message` | Public | Chatbot Gemini |

> Documentation complète auto-générée : **`/api/docs`** (OpenAPI 3.1 / Swagger UI)

---

## 🧪 Tests

```bash
php bin/phpunit
```

> PHPUnit 12.5 est configuré. Les tests unitaires et fonctionnels sont à venir.

---

## 📁 Structure du projet

```
src/
├── Controller/          # Contrôleurs Symfony (hors API Platform)
│   ├── CheckoutController.php
│   ├── GeoController.php
│   ├── InvoiceDownloadController.php
│   └── StripeWebhookController.php
├── Entity/              # Entités Doctrine (User, Order, Invoice, ...)
├── EventListener/       # Listeners (RateLimiter, Auth 2FA, ...)
├── Repository/          # Repositories Doctrine
├── Security/            # Voters, Authenticators (Google SSO)
├── Service/             # Services métier
│   ├── EmailVerifier.php
│   ├── GeoApiService.php
│   ├── InvoiceService.php
│   ├── InvoicePdfService.php
│   └── Chatbot/
config/
├── packages/            # Config Symfony (security, rate_limiter, ...)
├── routes/              # Déclaration des routes
└── services.yaml
templates/
├── emails/              # Templates emails transactionnels
└── invoices/            # Template PDF facture (DomPDF)
public/
└── images/              # Assets statiques (catégories, carrousel)
```

---

## 🤝 Contribution

1. Créer une branche depuis `main` : `git checkout -b feat/ma-feature`
2. Committer selon la convention : `feat(scope): description`
3. Ouvrir une Pull Request vers `main`

---

## 📄 Licence

Projet réalisé dans le cadre du **BAC+3 CPI — Coordinateur de Projet Informatique**.  
© 2026 CYNA — Tous droits réservés.
