# 📋 Journal de développement — CYNA Backend

> Récapitulatif de toutes les fonctionnalités ajoutées et difficultés rencontrées  
> Projet : BAC+3 CPI — Coordinateur de Projet Informatique (RNCP 38478)  
> Période : Mai 2026

---

## 🔧 Fonctionnalités développées

### 1. Authentification Google SSO (OAuth2)

**Ce qui a été fait :**
- Installation de `knpuniversity/oauth2-client-bundle` + `league/oauth2-google`
- Création de `GoogleAuthenticator.php` — intercepte le callback Google et retourne un JWT Symfony au lieu d'une session
- Création de `GoogleController.php` — routes `/login/google` et `/login/google/check`
- Mise à jour de `security.yaml` pour brancher l'authenticator
- Côté frontend : `GoogleCallbackPage.jsx`, bouton Google dans `LoginPage` et `RegisterPage`, intégration dans `AuthContext`

**Routes exposées :**
```
GET /login/google        → redirige vers Google
GET /login/google/check  → callback OAuth2 → retourne JWT
```

---

### 2. Backoffice EasyAdmin v5

**Ce qui a été fait :**
- Création de `AdminDashboardController.php` avec dashboard KPI (revenus, utilisateurs, ventes)
- 10 CRUD controllers : Utilisateurs, Catégories, Services, Commandes, Factures, Codes promo, Messages contact, Conversations chatbot, Carrousel, Textes dynamiques
- Sécurisation de `/admin` dans `security.yaml` (ROLE_ADMIN uniquement, session-based)
- Commande personnalisée `app:create-admin` pour créer un admin en ligne de commande

**Accès :**
```
https://api-cyna.ubikd.com/admin
```

---

### 3. Factures automatiques + Email de confirmation commande

**Ce qui a été fait :**
- `InvoiceService.php` — création automatique de facture après paiement confirmé (idempotent via `paymentIntentId`)
- `InvoicePdfService.php` — génération PDF A4 avec DomPDF (logo, détail lignes, TVA 20%)
- Endpoint `GET /api/invoices/{id}/download` — téléchargement sécurisé (auth requise)
- Email de confirmation commande envoyé automatiquement via le webhook Stripe (`payment_intent.succeeded`)
- Template email HTML avec récapitulatif commande et numéro de facture

---

### 4. Rate Limiting

**Ce qui a été fait :**
- `symfony/rate-limiter` configuré avec sliding window
- Endpoints protégés : login, reset-password, contact, chatbot
- Réponse `429 Too Many Requests` automatique en cas de dépassement

---

### 5. API Géolocalisation DINUM (BAN + API Géo)

**Ce qui a été fait :**
- `GeoApiService.php` — wrapper HTTP vers deux APIs publiques françaises :
  - **BAN** (`api-adresse.data.gouv.fr`) — autocomplétion adresses complètes
  - **API Géo** (`geo.api.gouv.fr`) — communes, régions, départements
- `GeoController.php` — 7 endpoints GET publics :

| Endpoint | Description |
|----------|-------------|
| `GET /api/geo/address?q=` | Autocomplétion adresse complète (BAN) |
| `GET /api/geo/streets?q=` | Autocomplétion rues uniquement |
| `GET /api/geo/communes?q=` | Recherche commune par nom |
| `GET /api/geo/communes/postal?cp=` | Recherche commune par code postal |
| `GET /api/geo/regions` | Liste des régions françaises |
| `GET /api/geo/departements?region=` | Départements (filtrable par région) |
| `GET /api/geo/departements/{code}/communes` | Communes d'un département |

- Règle `PUBLIC_ACCESS` ajoutée dans `security.yaml` pour `/api/geo`
- Timeout 5s + fallback silencieux `[]` si API indisponible

---

### 6. Autocomplétion d'adresse (Frontend React)

**Ce qui a été fait :**
- `src/api/geoApi.js` — appel à `/api/geo/address` sans JWT (endpoint public)
- `src/hooks/useAddressAutocomplete.js` — hook avec debounce 300ms + annulation de requête
- `src/components/ui/AddressAutocomplete.jsx` — combobox avec :
  - Navigation clavier (↑ ↓ Entrée Échap)
  - Fermeture au clic extérieur
  - Icônes `MapPin` / `Loader2` (lucide-react)
  - Callback `onSelect({ adresse1, zipCode, city, region })`
- Intégration dans `CheckoutPage.jsx` et `AccountPage.jsx`

---

### 7. Fixtures de démonstration

**Ce qui a été fait :**
- `AppFixtures.php` — données complètes :
  - 5 catégories (SOC, EDR, XDR, Réseau, Formation)
  - 15 services SaaS avec descriptions et prix
  - 4 slides carrousel
  - 12 textes dynamiques homepage
  - 4 codes promo
  - 3 comptes utilisateurs (1 admin + 2 clients)
- **Correction** : toutes les images migrées en `.png` (suppression des `.jpg` et `.svg`)

---

### 8. Guide de génération d'images (`PROMPTS_IMAGES_GEMINI.md`)

**Ce qui a été fait :**
- Charte visuelle codifiée : navy `#1E1B4B` + cyan `#22d3ee`
- 5 prompts Gemini pour les images catégories (1280×720 PNG)
- 4 prompts Gemini pour les banners carrousel (1920×600 PNG)
- Checklist EasyAdmin pour mise à jour des chemins après génération

---

### 9. README GitHub (`README.md`)

**Ce qui a été fait :**
- Badges PHP / Symfony / API Platform / PostgreSQL / Stripe / JWT
- Table stack technique complète
- Liste des fonctionnalités par section
- Guide d'installation pas à pas
- Tableau des endpoints principaux
- Arborescence du projet

---

### 10. Déploiement VPS (Production)

**Ce qui a été fait :**
- Deux sous-domaines créés sur OVH (DNS A records) :
  - `cyna.ubikd.com` → frontend React
  - `api-cyna.ubikd.com` → backend Symfony
- Configs Nginx créées pour les deux apps
- Certificats SSL Let's Encrypt via Certbot (HTTPS automatique)
- Base de données PostgreSQL `cyna_db` créée avec utilisateur dédié `cyna_user`
- `.env.local` configuré pour la production
- Clés JWT générées sur le serveur
- Migrations appliquées + fixtures chargées
- Frontend buildé avec Vite (`npm run build`)
- Google OAuth mis à jour avec les URIs de production
- Scripts de déploiement `deploy-backend.sh` et `deploy-frontend.sh`

**URLs de production :**
```
https://cyna.ubikd.com           → Frontend React
https://api-cyna.ubikd.com/api   → API REST
https://api-cyna.ubikd.com/admin → Backoffice EasyAdmin
```

---

## 🐛 Difficultés rencontrées et solutions

### 1. `git commit -m` échoue avec des caractères spéciaux (Windows CMD)
**Problème :** Les guillemets et caractères accentués dans `-m "message"` cassent la commande dans cmd.exe  
**Solution :** Écrire le message dans un fichier texte et utiliser `git commit -F fichier.txt`

---

### 2. Mauvaise compréhension initiale de l'API Géo
**Problème :** Première implémentation couvrait uniquement les noms de villes (API Géo). Le besoin réel était l'autocomplétion d'adresses complètes (rue + ville + code postal).  
**Solution :** Remplacement par l'intégration de la BAN (`api-adresse.data.gouv.fr`) avec normalisation du GeoJSON retourné.

---

### 3. Erreur de diplôme dans le README
**Problème :** Diplôme écrit "BTS SIO" au lieu de "BAC+3 CPI — Coordinateur de Projet Informatique (RNCP 38478)"  
**Solution :** Correction immédiate + commit dédié poussé sur main.

---

### 4. Fichier `.env` manquant sur le VPS
**Problème :** `composer install` échoue avec `Unable to read the ".env" environment file` — seul `.env.local` existait sur le VPS (`.env` non versionné).  
**Solution :** Création d'un `.env` minimal avec `echo "APP_ENV=prod" > .env`

---

### 5. Variable `DEFAULT_URI` manquante
**Problème :** `cache:clear` échoue avec `Environment variable not found: DEFAULT_URI` — le router Symfony a besoin de cette variable pour générer des URLs absolues hors contexte HTTP.  
**Solution :** Ajout de `DEFAULT_URI=https://api-cyna.ubikd.com` dans `.env.local`

---

### 6. `DoctrineFixturesBundle` absent en mode prod
**Problème :** `composer install --no-dev` n'installe pas les dépendances de développement, donc `doctrine:fixtures:load` est indisponible en `--env=prod`.  
**Solution :** Installation temporaire avec `composer require doctrine/doctrine-fixtures-bundle --dev` puis exécution en `--env=dev`

---

### 7. PHP 8.4 au lieu de PHP 8.2
**Problème :** Le VPS tourne sur PHP 8.4-FPM alors que le projet cible PHP 8.2+. Les configs Nginx initiales utilisaient le mauvais socket.  
**Solution :** Remplacement de `php8.2-fpm.sock` par `php8.4-fpm.sock` dans les configs Nginx.

---

### 8. Google OAuth — `redirect_uri_mismatch` (double problème)

**Problème 1 :** Symfony générait `http://` au lieu de `https://` pour le redirect URI, car Nginx ne transmettait pas le flag HTTPS à PHP-FPM.  
**Solution :** Ajout de `fastcgi_param HTTPS on;` dans le bloc `location ~ ^/index\.php` de la config Nginx backend.

**Problème 2 :** Le redirect URI enregistré dans Google Cloud Console était `/connect/google/check` mais la vraie route Symfony est `/login/google/check`.  
**Diagnostic :** `php bin/console debug:router --env=prod | grep google`  
**Solution :** Ajout de `https://api-cyna.ubikd.com/login/google/check` dans les URIs autorisés Google Cloud.

---

## ✅ État final du projet

| Composant | Statut |
|-----------|--------|
| API REST Symfony | ✅ En production |
| Authentification JWT | ✅ Fonctionnel |
| 2FA TOTP + Email | ✅ Fonctionnel |
| Google SSO | ✅ Fonctionnel |
| Paiement Stripe | ✅ Intégré (mode test) |
| Factures PDF | ✅ Fonctionnel |
| Chatbot Gemini | ✅ Fonctionnel |
| API Géolocalisation | ✅ Fonctionnel |
| Autocomplétion adresse | ✅ Fonctionnel |
| Backoffice EasyAdmin | ✅ Accessible |
| Frontend React | ✅ En production |
| HTTPS SSL | ✅ Certifié Let's Encrypt |
| Déploiement VPS | ✅ Opérationnel |
