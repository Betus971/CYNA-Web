# Récapitulatif de développement — CYNA

> Synthèse de tout le travail réalisé sur le projet CYNA (back-end `CYNA-Web` + front-end `CYNA-frontend-react`) au cours de la session, ainsi que les difficultés rencontrées et leurs résolutions.
>
> _Document de suivi — 22/05/2026._

---

## 1. Contexte

CYNA est une plateforme e-commerce de services SaaS de cybersécurité (SOC / EDR / XDR).

- **Back-end** : Symfony 7.4 + API Platform 4.3 + PostgreSQL + LexikJWT + NelmioCors.
- **Front-end** : React 18 + Vite 5.4 (SPA), `react-router-dom` v6, wrapper `fetch` maison (pas d'axios), i18n `react-i18next`.

L'objectif de la session : **terminer le câblage de l'authentification, puis brancher tout le parcours e-commerce du front sur la vraie API**, corriger les incohérences back-end et fiabiliser le tout.

---

## 2. Back-end (`CYNA-Web`) — ajouts et corrections

### 2.1 Entités
- Correction des typos et des types sur les entités existantes.
- **Refonte d'`Order`** avec une entité `OrderItem` dédiée (lignes de commande).
- `Address` : ajout du processor + `Post(security: "is_granted('ROLE_USER')")`. Champs : `firstname`, `lastname`, `adresse1`, `adresse2` (nullable), `city`, `region`, `zipCode`, `country`, `mobilephone`.
- `PaymentMethod` : ajout du processor + `Post` sécurisé. Champs : `provider`, `brand`, `last4` (exactement 4), `expMonth` (1–12), `expYear`, `isDefault`. Le `providerToken` n'est **jamais** exposé en écriture (groupe de sérialisation).
- Création des entités manquantes pour couvrir le périmètre (catalogue, panier, commande, facturation, contact, chatbot, promos, etc.).

### 2.2 Sécurité API Platform
- Ajout d'expressions de sécurité sur les `ApiResource` : lecture/écriture réservées au propriétaire (`object.getUser() == user`) ou à `ROLE_ADMIN`.

### 2.3 State Processors
- **`UserProcessor`** : inscription (hash du mot de passe, rôle par défaut, etc.).
- **`AddressProcessor`** (nouveau) : affecte automatiquement l'utilisateur courant à l'adresse, refuse si non authentifié.
- **`PaymentMethodProcessor`** (nouveau) : affecte l'utilisateur, génère un `providerToken` factice (`mock_…`) et un `provider` par défaut.

### 2.4 Données et divers
- Remplissage des repositories (méthodes de requête utiles).
- Mise à jour de `HomeController` + régénération de la migration correspondante.
- **`AppFixtures`** (nouveau) : 5 catégories, 10 services SaaS, 1 compte admin de test (`admin@cyna.local`).
- Vérification de la configuration : `security.yaml` (firewall `json_login`, `/api/login_check`, `username_path: email`), `nelmio_cors.yaml` (headers `Content-Type` + `Authorization`), `.env`.

---

## 3. Front-end (`CYNA-frontend-react`) — ajouts

### 3.1 Couche API
- **`http.js`** réécrit : wrapper `fetch` avec injection automatique du JWT, déconnexion auto sur 401, parsing JSON-LD/Hydra, `PATCH` en `application/merge-patch+json`, logs `[http]` en mode DEV.
- **`authApi.js`** : `login`, `register`, `fetchCurrentUser` (`/api/me`), vérification e-mail, reset mot de passe, logout.
- **`catalogApi.js`** : home, catégories, produits vedettes, services, recherche (`{ total, items }`).
- Nouveaux helpers : **`cartApi.js`**, **`orderApi.js`**, **`addressApi.js`**, **`paymentMethodApi.js`**, **`contactApi.js`**.

### 3.2 Contextes (état global)
- **`AuthContext.jsx`** : `login` / `register` / `logout` / `refresh`, hydratation via `/api/me`, déconnexion auto sur 401.
- **`CartContext.jsx`** (nouveau) : `useReducer`. Panier invité en `localStorage`, panier connecté synchronisé via l'API, **fusion du panier invité au login**.

### 3.3 Pages et composants
- **Auth** : `LoginPage` (avec 2FA), `RegisterPage`, `VerifyEmailPage`, `ForgotPasswordPage`, `ResetPasswordPage` — toutes branchées sur la vraie API.
- **Catalogue** : `CategoriesPage`, `ProductsPage` (recherche/tri/pagination via l'URL), `ProductDetailPage`.
- **Achat** : `CartPage`, `CheckoutPage` (adresse + récap commande), `OrderConfirmationPage`.
- **Compte** : `AccountPage` avec onglets (Profil / Commandes / Adresses / Moyens de paiement).
- **Contact** : `ContactPage`.
- **Layout** : `Navbar` (utilisateur connecté + logout + badge panier + recherche), `Footer` (lien contact + légales), `AppRouter` réécrit (toutes les routes, routes protégées incluses).

### 3.4 Utilitaires & contenu
- **`authValidation.js`** : `PASSWORD_MIN_LENGTH = 12`, `splitFullName`, mapping d'erreurs API, détection « serveur injoignable ».
- **`siteText.js`** : tous les libellés des nouvelles pages (FR), + champs adresse/paiement.
- `.env.example` : `VITE_API_BASE_URL=http://127.0.0.1:8000`.

---

## 4. Difficultés rencontrées et résolutions

| # | Difficulté | Cause | Résolution |
|---|------------|-------|------------|
| 1 | **Fichiers tronqués à l'écriture** | Les outils `Write`/`Edit` coupaient le contenu des gros fichiers en plein milieu | Écriture via `bash` (heredoc `cat > … <<'EOF'`) puis validation systématique avec `@babel/parser` |
| 2 | **« Impossible de joindre le serveur »** | `symfony serve` tournait en **HTTPS** alors que le front visait `http://` | Lancer le back en `symfony serve --no-tls` (ou aligner `VITE_API_BASE_URL` en `https`) |
| 3 | **Inscription refusée (mot de passe)** | Le front validait 8 caractères, le back en exige **12** (maj/min/chiffre/spécial) | `PASSWORD_MIN_LENGTH = 12` côté front + textes d'aide mis à jour |
| 4 | **`LoginPage` cassée** | Une commande `sed` avait supprimé l'accolade finale + altéré une balise | Correction de la balise + ré-ajout de l'accolade |
| 5 | **Schéma Adresse / Paiement incohérent** | Le front envoyait `{label, line1, postalCode…}` ≠ entités back ; `providerToken` non renseignable | Remappage des formulaires + création des **State Processors** (user auto + token généré) |
| 6 | **Catalogue vide** | Aucune donnée en base | Création de `AppFixtures` (catégories, services, admin) |
| 7 | **Login qui échoue** _(en cours)_ | Non identifié | Diagnostic lancé : route `login_check`, vérif colonne `password`/`is_verified`, lecture du log `[http]` en console — **en attente des retours** |

---

## 5. État actuel et points ouverts

- ✅ Parcours e-commerce front entièrement câblé sur l'API (43 fichiers front validés).
- ✅ Alignement des schémas Adresse/Paiement + affectation auto de l'utilisateur.
- ✅ Données de démo (fixtures) disponibles.
- ⏳ **Login à fiabiliser** avant la démo (diagnostic en attente).
- ℹ️ Pour réinitialiser les données : `php bin/console doctrine:fixtures:load --no-interaction`.

---

## 6. Pour lancer le projet

```bash
# Back-end
cd CYNA-Web
symfony serve --no-tls            # http://127.0.0.1:8000

# Front-end
cd CYNA-frontend-react
npm install
npm run dev                       # http://localhost:5173
```
