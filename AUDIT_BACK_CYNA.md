# AUDIT BACKEND — CYNA-Web

> **Date de l'audit :** 2026-05-18 — **Mis à jour :** 2026-05-19  
> **Auditeur :** Claude Code (analyse automatisée)  
> **Branche analysée :** `claude/audit-cyna-backend-Y354y`

---

## Résumé

### Avancement backend estimé : **82 %** *(était 78 % au 2026-05-18)*

Le socle est solide et bien structuré. La majorité des entités, des routes CRUD, du système d'authentification et du backoffice sont en place. **Google SSO (OAuth2) est désormais opérationnel** côté backend et frontend. Les parties encore manquantes concernent principalement l'intégration réelle de la passerelle de paiement, la génération de PDF pour les factures, les emails transactionnels (confirmation commande…), et le 2FA admin.

### Stack technique détectée

| Composant | Technologie |
|-----------|-------------|
| **Langage** | PHP 8.2+ |
| **Framework** | Symfony 7.4 |
| **API** | API Platform 4.3 (REST JSON-LD + OpenAPI/Swagger auto-généré) |
| **Base de données** | PostgreSQL |
| **ORM** | Doctrine ORM 3.6 + Doctrine Migrations |
| **Authentification** | JWT stateless (LexikJWTAuthenticationBundle 3.2) + **Google SSO (KnpU OAuth2 + league/oauth2-google)** |
| **Emails** | Symfony Mailer 7.4 |
| **Chatbot IA** | Google Gemini API |
| **CORS** | NelmioCorsBundle 2.6 |
| **Tests** | PHPUnit 12.5 (configuré, pas encore de tests écrits) |
| **Serveur Web** | Compatible Nginx/Apache + FrankenPHP (Docker) |

---

## État des endpoints et modules

### 1. Authentification et gestion des utilisateurs

#### Inscription et vérification email
| Endpoint | Méthode | Statut | Détail |
|----------|---------|--------|--------|
| `POST /api/users` | Inscription | ✅ Fait | Hachage du mot de passe, token de vérification généré et email envoyé automatiquement |
| `POST /api/verify-email` | Vérification email | ✅ Fait | Token expirant validé, utilisateur marqué `isVerified=true` |

**Ce qui manque :**
- 🟡 La durée de validité du token email est enregistrée (`emailVerificationSentAt`) mais **aucune vérification des 24h n'est implémentée** dans le contrôleur de vérification.
- ❌ Pas de endpoint pour **renvoyer le mail de vérification** si le lien a expiré.

#### Connexion et sessions
| Endpoint | Méthode | Statut | Détail |
|----------|---------|--------|--------|
| `POST /api/login` | Connexion JWT | ✅ Fait | Retourne un token JWT, architecture stateless |
| `GET /login/google` | Redirection OAuth2 | ✅ Fait | Lance le flux Google SSO via KnpU OAuth2 |
| `GET /login/google/check` | Callback OAuth2 | ✅ Fait | Reçoit le code Google, crée/retrouve l'utilisateur, émet un JWT et redirige vers le frontend |
| `POST /api/login` (2FA) | Vérification TOTP | ✅ Fait | Flux 2FA complet côté frontend (vérification du code TOTP après login) |

**Ce qui manque :**
- ❌ Option **"se souvenir de moi"** (durée de vie du token configurable selon le choix de l'utilisateur — ex. 7 jours vs 1 heure)
- ❌ Pas de **refresh token** pour renouveler la session sans redemander les identifiants

#### Réinitialisation du mot de passe
| Endpoint | Méthode | Statut | Détail |
|----------|---------|--------|--------|
| `POST /api/password/forgot` | Demande reset | ✅ Fait | Token généré, email envoyé |
| `POST /api/password/reset` | Reset effectif | ✅ Fait | Token validé, nouveau mot de passe hashé |

**Ce qui manque :**
- 🟡 La **vérification de l'expiration à 24h** du token de reset est enregistrée (`passwordResetExpiresAt`) mais **pas vérifiée dans le contrôleur**. Actuellement le token ne expire jamais côté serveur.

#### Protection des routes (middleware)
| Élément | Statut | Détail |
|---------|--------|--------|
| Routes privées protégées par JWT | ✅ Fait | Configuration `security.yaml` avec `access_control` |
| Hiérarchie des rôles `ROLE_USER < ROLE_ADMIN` | ✅ Fait | Configurée dans `security.yaml` |
| Extension Doctrine filtre les données par utilisateur | ✅ Fait | `CurrentUserOrderExtension` sur Orders, Addresses |
| Voter personnalisé pour User | ✅ Fait | `UserVoter` (USER_VIEW, USER_EDIT, USER_DELETE) |
| **Redirection vers login si non authentifié** | ❌ Manquant | API retourne un 401 JSON mais pas de redirection HTTP — normal pour une API REST, mais à gérer côté frontend |

#### Authentification admin avec 2FA
| Élément | Statut | Détail |
|---------|--------|--------|
| Champs TOTP en base (`totpSecret`, `totpEnabled`) | ✅ Fait | Champs présents dans l'entité User |
| Flux 2FA côté frontend | ✅ Fait | `LoginPage` détecte `requires2fa`, affiche le formulaire de code, appelle `verify2fa` dans `AuthContext` |
| **Génération du QR code TOTP** | ❌ Manquant | Aucun service ni endpoint pour activer le 2FA depuis le compte |
| **Vérification du code TOTP à la connexion (backend)** | ❌ Manquant | Le login JWT doit retourner `requires2fa: true` et exposer un endpoint `POST /api/login/2fa` |

#### Hashage et sécurité des mots de passe
| Élément | Statut | Détail |
|---------|--------|--------|
| Hashage bcrypt/argon2 via Symfony | ✅ Fait | Auto-configuré par Symfony |
| Validation complexité (12 cars, maj, min, chiffre, spécial) | ✅ Fait | Contraintes Symfony sur `plainPassword` |
| `plainPassword` jamais persisté en base | ✅ Fait | Champ transient, effacé après hachage |

---

### 2. API Produits et catalogue

#### CRUD produits (SaasService)
| Endpoint | Statut | Détail |
|----------|--------|--------|
| `GET /api/saas_services` | ✅ Fait | Lecture publique avec pagination |
| `GET /api/saas_services/{id}` | ✅ Fait | Lecture publique |
| `POST /api/saas_services` | ✅ Fait | Création admin uniquement |
| `PATCH /api/saas_services/{id}` | ✅ Fait | Mise à jour admin uniquement |
| `DELETE /api/saas_services/{id}` | ✅ Fait | Suppression admin uniquement |

**Champs disponibles :** nom, description, specs techniques, prix, disponibilité, priorité, image, catégorie.

**Ce qui manque :**
- ❌ **Gestion des illustrations multiples** (actuellement un seul champ `image` VARCHAR — pas de galerie)
- ❌ **Upload d'image** directement via l'API (actuellement le champ stocke une URL/chemin, sans endpoint dédié)

#### Gestion des catégories
| Endpoint | Statut | Détail |
|----------|--------|--------|
| `GET /api/categories` | ✅ Fait | Lecture publique, triable par `displayOrder` |
| `GET /api/categories/{id}` | ✅ Fait | Lecture publique |
| `POST /api/categories` | ✅ Fait | Création admin |
| `PATCH /api/categories/{id}` | ✅ Fait | Mise à jour admin (dont `displayOrder`) |
| `DELETE /api/categories/{id}` | ✅ Fait | Suppression admin |

#### Recherche avancée avec facettes
| Endpoint | Statut | Détail |
|----------|--------|--------|
| `GET /api/catalog/search` | ✅ Fait | Recherche texte + filtres prix/catégorie/dispo + tri + pagination |

Paramètres supportés : `q`, `category`, `minPrice`, `maxPrice`, `availableOnly`, `sort` (priority/name/price), `direction` (asc/desc), `limit`, `offset`.

**Ce qui manque :**
- ❌ **Recherche dans les caractéristiques techniques** (`technicalSpecs`) — actuellement non indexée dans la recherche custom
- 🟡 Le filtre API Platform sur `/api/saas_services` couvre `name` et `description` mais pas `technicalSpecs`

#### Top produits et carrousel homepage
| Endpoint | Statut | Détail |
|----------|--------|--------|
| `GET /api/home` | ✅ Fait | Retourne carousel, catégories, top produits, textes dynamiques |
| `GET /api/carousel_slides` (+ CRUD admin) | ✅ Fait | Carrousel entièrement gérable depuis le backoffice |
| `GET /api/homepage_texts` (+ CRUD admin) | ✅ Fait | Textes dynamiques de la page d'accueil |
| Top produits via champ `priority` | ✅ Fait | Triés par `priority` ASC, filtrés `isAvailable=true` |

#### Pagination
| Élément | Statut | Détail |
|---------|--------|--------|
| Pagination API Platform sur tous les endpoints liste | ✅ Fait | 10 items/page par défaut, configurable |
| Pagination custom dans `/api/catalog/search` | ✅ Fait | Paramètres `limit` et `offset` |

---

### 3. Panier et commandes

#### Gestion du panier
| Endpoint | Statut | Détail |
|----------|--------|--------|
| `POST /api/carts` | ✅ Fait | Crée un panier, retourne un `token` unique |
| `GET /api/carts/{id}` | ✅ Fait | Lecture publique (token comme identifiant) |
| `PATCH /api/carts/{id}` | ✅ Fait | Mise à jour (items, promo code) |
| `DELETE /api/carts/{id}` | ✅ Fait | Suppression |
| `POST /api/cart_items` | ✅ Fait | Ajout article |
| `PATCH /api/cart_items/{id}` | ✅ Fait | Modification quantité/durée |
| `DELETE /api/cart_items/{id}` | ✅ Fait | Suppression article |
| Panier sans connexion (token) | ✅ Fait | Accessible anonymement via le `token` |
| Association au compte à la connexion | ❌ Manquant | Pas de mécanisme de fusion du panier anonyme avec le compte utilisateur après login |

#### Création de commande
| Endpoint | Statut | Détail |
|----------|--------|--------|
| `POST /api/orders` | ✅ Fait | Crée la commande avec items et adresse de facturation |
| Étapes checkout (adresse → paiement → confirmation) | ❌ Manquant | Un seul appel POST crée tout d'un coup — pas de workflow en étapes |
| **Email de confirmation de commande** | ❌ Manquant | Aucun email envoyé après création de commande |

#### Historique et consultation des commandes
| Endpoint | Statut | Détail |
|----------|--------|--------|
| `GET /api/orders` | ✅ Fait | Liste filtrée automatiquement par utilisateur (Extension Doctrine) |
| `GET /api/orders/{id}` | ✅ Fait | Détail commande |
| Filtre par année et type | ❌ Manquant | Aucun filtre date ou statut sur la collection orders utilisateur |

#### Factures PDF
| Élément | Statut | Détail |
|---------|--------|--------|
| Entité `Invoice` avec champ `pdfPath` | ✅ Fait | Structure en base prête |
| `GET /api/invoices` | ✅ Fait | Liste des factures de l'utilisateur |
| `GET /api/invoices/{id}` | ✅ Fait | Consultation facture |
| **Génération PDF** | ❌ Manquant | Aucun service de génération PDF (type DomPDF/TCPDF) |
| **Téléchargement PDF** | ❌ Manquant | Pas d'endpoint `GET /api/invoices/{id}/download` |
| **Création automatique de la facture à la commande** | ❌ Manquant | Aucun listener/processor ne crée la Invoice après paiement |

---

### 4. Abonnements

| Élément | Statut | Détail |
|---------|--------|--------|
| Champs abonnement sur `OrderItem` (`subscriptionStartsAt`, `subscriptionEndsAt`, `subscriptionStatus`) | ✅ Fait | Données stockées sur chaque ligne de commande |
| Renouvellement automatique | ❌ Manquant | Aucune tâche planifiée (Symfony Messenger/Scheduler) |
| Endpoint mise à jour statut abonnement | 🟡 Partiel | Possible via `PATCH /api/orders/{id}` (admin) mais pas d'endpoint dédié utilisateur |
| Résiliation depuis le compte | ❌ Manquant | Pas d'endpoint `POST /api/subscriptions/{id}/cancel` |
| Email de renouvellement / expiration | ❌ Manquant | Aucun email automatique |
| Gestion depuis le compte utilisateur | ❌ Manquant | Pas d'endpoint `/api/me/subscriptions` |

---

### 5. Paiement

| Élément | Statut | Détail |
|---------|--------|--------|
| Entité `PaymentMethod` (token PSP, brand, last4, expiry, isDefault) | ✅ Fait | Jamais de données carte en clair |
| CRUD méthodes de paiement | ✅ Fait | Endpoints protégés par rôle |
| **Intégration Stripe/PayPal réelle** | ❌ Manquant | Aucun appel SDK Stripe, aucun webhook de paiement |
| **Webhook Stripe** (`POST /api/webhooks/stripe`) | ❌ Manquant | Nécessaire pour confirmer le paiement et mettre à jour le statut de la commande |
| **Intent de paiement** (`POST /api/orders/{id}/pay`) | ❌ Manquant | Aucun endpoint pour initialiser le paiement côté Stripe |
| Chiffrement des données sensibles | ✅ Fait | `providerToken` est un opaque ID PSP, pas de données carte |
| Statuts commande (`pending`, `paid`, `failed`, etc.) | ✅ Fait | ENUM complet sur l'entité Order |

---

### 6. Compte utilisateur

| Endpoint | Statut | Détail |
|----------|--------|--------|
| `GET /api/me` | ✅ Fait | Retourne les infos de l'utilisateur connecté |
| `PATCH /api/users/{id}` (modifier nom/email/mot de passe) | 🟡 Partiel | L'endpoint existe via API Platform mais **pas de validation email unique** ni de **re-vérification email** si l'email change |
| Carnet d'adresses CRUD (`/api/addresses`) | ✅ Fait | Protégé, filtré par utilisateur |
| Gestion des abonnements depuis le compte | ❌ Manquant | Voir section Abonnements |
| **Validation serveur si email modifié** | ❌ Manquant | Si un utilisateur change son email, il faudrait renvoyer un lien de vérification |

---

### 7. Contact et chatbot

#### Formulaire de contact
| Endpoint | Statut | Détail |
|----------|--------|--------|
| `POST /api/contact_messages` | ✅ Fait | Accessible publiquement, validations sur tous les champs |
| Consultation admin (`GET`, `PATCH handled`, `DELETE`) | ✅ Fait | Gestion depuis le backoffice |
| **Notification email à l'admin à la réception** | ❌ Manquant | Aucun email envoyé lors d'un nouveau message contact |

#### Chatbot IA
| Endpoint | Statut | Détail |
|----------|--------|--------|
| `POST /api/chatbot/message` | ✅ Fait | Intégration Google Gemini, historique multi-tours, locale fr/en |
| Stockage des conversations | ✅ Fait | Entité `ChatbotConversation` avec tous les champs |
| Escalade vers agent humain | ✅ Fait | Détection automatique + manuelle, crée un `ContactMessage` |
| **Notification backoffice lors d'escalade** | ❌ Manquant | Pas d'email/notification envoyé à l'admin quand `escalated=true` |
| Consultation admin des conversations | ✅ Fait | Endpoints protégés ROLE_ADMIN |

---

### 8. Backoffice

| Fonctionnalité | Statut | Détail |
|---------------|--------|--------|
| Tous les CRUD protégés ROLE_ADMIN | ✅ Fait | Via `security.yaml` et attributs API Platform |
| Gestion carrousel homepage | ✅ Fait | CRUD complet `CarouselSlide` |
| Gestion textes dynamiques | ✅ Fait | CRUD complet `HomepageText` |
| Gestion ordre catégories | ✅ Fait | Champ `displayOrder` sur `Category` |
| Gestion produits mis en avant | ✅ Fait | Champ `priority` sur `SaasService` |
| Dashboard KPI (revenus, nouveaux users) | ✅ Fait | `GET /api/admin/dashboard/kpi` |
| Ventes par jour | ✅ Fait | `GET /api/admin/dashboard/sales-by-day` |
| Répartition ventes par catégorie | ✅ Fait | `GET /api/admin/dashboard/sales-by-category` |
| **Panier moyen par catégorie** | ❌ Manquant | Absent du dashboard actuel |
| Gestion commandes admin | ✅ Fait | `GET/PATCH /api/orders` (admin voit tout) |
| Gestion utilisateurs admin | 🟡 Partiel | `GET /api/users` existe mais pas de endpoint admin dédié avec filtres avancés |
| Gestion messages de contact | ✅ Fait | CRUD + flag `handled` |
| Gestion conversations chatbot | ✅ Fait | CRUD + flag `handled` |
| Codes promo | ✅ Fait | CRUD complet avec validations (dates, usage max) |

---

### 9. Exigences non-fonctionnelles

| Exigence | Statut | Détail |
|----------|--------|--------|
| Protection injection SQL | ✅ Fait | Doctrine ORM avec paramètres préparés |
| Protection XSS | 🟡 Partiel | API JSON uniquement (pas de rendu HTML utilisateur), mais pas de sanitisation explicite sur les champs texte |
| Protection CSRF | ✅ Fait | JWT stateless = pas de vulnérabilité CSRF (pas de cookie de session) |
| Validation des données côté serveur | ✅ Fait | Contraintes Symfony sur toutes les entités |
| Gestion des erreurs cohérente | ✅ Fait | API Platform génère des erreurs RFC 7807 (Problem+JSON) avec codes HTTP corrects |
| Documentation API (Swagger/OpenAPI) | ✅ Fait | Auto-générée par API Platform, accessible via `/api/docs` |
| CORS configuré | ✅ Fait | NelmioCorsBundle avec variables d'environnement |
| Chiffrement données sensibles paiement | ✅ Fait | Jamais de données carte stockées, tokens PSP uniquement |
| **Rate limiting** | ❌ Manquant | Pas de limitation de débit sur les endpoints publics |
| **Certificat SSL** | ⚠️ Infrastructure | À configurer sur le serveur/reverse proxy, pas du code |
| API consommable par app mobile | ✅ Fait | JSON pur + JWT = compatible toutes plateformes |
| **Tests automatisés** | ❌ Manquant | PHPUnit configuré mais aucun test écrit |
| **Refresh token JWT** | ❌ Manquant | Pas de renouvellement de session |

---

## Backlog backend restant

| Ticket # | Intitulé | Difficulté | Statut | Priorité suggérée |
|----------|----------|-----------|--------|-------------------|
| 6 | Validation expiration tokens email/reset (24h) | ⭐ Facile | 🟡 Partiel | 🔴 Haute |
| 9 | Option "se souvenir de moi" (durée JWT configurable) | ⭐⭐ Moyen | ❌ Manquant | 🟠 Moyenne |
| 10 | Refresh token JWT | ⭐⭐ Moyen | ❌ Manquant | 🟠 Moyenne |
| 12 | 2FA admin (TOTP QR code + vérification) | ⭐⭐⭐ Difficile | ❌ Manquant | 🔴 Haute |
| 14 | Renvoi email de vérification si expiré | ⭐ Facile | ❌ Manquant | 🔴 Haute |
| 17 | Fusion panier anonyme → compte après login | ⭐⭐ Moyen | ❌ Manquant | 🟠 Moyenne |
| 23 | Email confirmation de commande | ⭐ Facile | ❌ Manquant | 🔴 Haute |
| 26 | Intégration Stripe (PaymentIntent + webhook) | ⭐⭐⭐ Difficile | ❌ Manquant | 🔴 Haute |
| 27 | Webhook Stripe pour confirmer paiement | ⭐⭐⭐ Difficile | ❌ Manquant | 🔴 Haute |
| 28 | Endpoint initier paiement (`POST /api/orders/{id}/pay`) | ⭐⭐ Moyen | ❌ Manquant | 🔴 Haute |
| 29 | Génération PDF facture (DomPDF) | ⭐⭐ Moyen | ❌ Manquant | 🟠 Moyenne |
| 30 | Endpoint téléchargement facture PDF | ⭐ Facile | ❌ Manquant | 🟠 Moyenne |
| 32 | Création automatique facture après paiement | ⭐⭐ Moyen | ❌ Manquant | 🔴 Haute |
| 33 | Filtre commandes par année/statut (historique user) | ⭐ Facile | ❌ Manquant | 🟡 Basse |
| 34 | Endpoint résiliation abonnement utilisateur | ⭐⭐ Moyen | ❌ Manquant | 🟠 Moyenne |
| 36 | Vue abonnements actifs depuis le compte | ⭐ Facile | ❌ Manquant | 🟠 Moyenne |
| 37 | Tâche planifiée renouvellement abonnements | ⭐⭐⭐ Difficile | ❌ Manquant | 🟡 Basse |
| 38 | Email expiration/renouvellement abonnement | ⭐ Facile | ❌ Manquant | 🟡 Basse |
| 40 | Notification email admin — nouveau message contact | ⭐ Facile | ❌ Manquant | 🟠 Moyenne |
| 41 | Notification admin lors d'escalade chatbot | ⭐ Facile | ❌ Manquant | 🟠 Moyenne |
| 43 | Recherche dans `technicalSpecs` (catalog/search) | ⭐ Facile | ❌ Manquant | 🟡 Basse |
| 44 | Dashboard : panier moyen par catégorie | ⭐⭐ Moyen | ❌ Manquant | 🟡 Basse |
| 46 | Validation email unique à la modification profil | ⭐ Facile | ❌ Manquant | 🟠 Moyenne |
| 47 | Re-vérification email si l'utilisateur change son email | ⭐⭐ Moyen | ❌ Manquant | 🟠 Moyenne |
| 48 | Rate limiting sur endpoints publics | ⭐⭐ Moyen | ❌ Manquant | 🟠 Moyenne |
| 52 | Upload image produit/catégorie via API | ⭐⭐ Moyen | ❌ Manquant | 🟡 Basse |
| 53 | Galerie d'illustrations produit | ⭐⭐ Moyen | ❌ Manquant | 🟡 Basse |
| 54 | Tests PHPUnit (endpoints critiques) | ⭐⭐⭐ Difficile | ❌ Manquant | 🟠 Moyenne |
| 55 | Filtres avancés gestion utilisateurs (admin) | ⭐ Facile | ❌ Manquant | 🟡 Basse |
| 57 | Workflow checkout en étapes (adresse → paiement → confirmation) | ⭐⭐⭐ Difficile | ❌ Manquant | 🔴 Haute |

---

## Schéma base de données détecté

### Tables existantes (15 tables)

```
┌─────────────────────────┬──────────────────────────────────────────────────────────────┐
│ Table                   │ Champs principaux                                            │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ user                    │ id, email*, password, firstname, lastname, roles (JSON),     │
│                         │ is_verified, email_verification_token, totp_secret,          │
│                         │ totp_enabled, created_at                                     │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ address                 │ id, firstname, lastname, adresse1, adresse2, city, region,   │
│                         │ zip_code, country, mobilephone, user_id (FK)                │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ category                │ id, name, image, display_order                               │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ saas_service            │ id, name, description, technical_specs, price, is_available, │
│                         │ priority, image, category_id (FK)                            │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ cart                    │ id, token*, user_id (FK nullable), promo_code_id (FK         │
│                         │ nullable), created_at, updated_at                            │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ cart_item               │ id, cart_id (FK), saas_service_id (FK), quantity,            │
│                         │ duration_months                                               │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ order                   │ id, reference*, total_price, status (ENUM), created_at,      │
│                         │ paid_at, user_id (FK), billing_address_id (FK)              │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ order_item              │ id, order_id (FK), saas_service_id (FK),                     │
│                         │ product_name_snapshot, unit_price_snapshot, quantity,        │
│                         │ duration_months, subscription_starts_at,                    │
│                         │ subscription_ends_at, subscription_status (ENUM)            │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ invoice                 │ id, number*, order_id (FK OneToOne), total_amount,           │
│                         │ tax_amount, pdf_path, issued_at                              │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ payment_method          │ id, user_id (FK), provider_token, provider, brand, last4,   │
│                         │ exp_month, exp_year, is_default                              │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ promo_code              │ id, code* (uppercase), percentage, starts_at, ends_at,       │
│                         │ active, max_usages, usage_count                              │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ contact_message         │ id, full_name, email, subject, message, handled, created_at  │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ chatbot_conversation    │ id, full_name, email, subject, question, answer, transcript, │
│                         │ locale, escalated, handled, created_at                       │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ carousel_slide          │ id, title, subtitle, image, link_url, cta_label,            │
│                         │ display_order, active                                        │
├─────────────────────────┼──────────────────────────────────────────────────────────────┤
│ homepage_text           │ id, slug*, title, body                                       │
└─────────────────────────┴──────────────────────────────────────────────────────────────┘
* = unique
```

### Tables manquantes (à créer)

| Table | Pourquoi nécessaire |
|-------|---------------------|
| `stripe_event` (ou `webhook_event`) | Stocker les webhooks Stripe reçus pour éviter les doublons (idempotence) |
| `service_image` (galerie) | Si on ajoute le support de plusieurs images par produit (ticket 53) |
| `subscription` (dédiée) | Si on veut un vrai système d'abonnements indépendant des OrderItems (ticket 37) |

---

## Plan d'action backend recommandé

Voici les actions dans l'ordre du plus bloquant au moins urgent, avec une explication simple pour chaque étape.

---

### 🔴 Priorité 1 — Bloquant pour la démo / fonctionnement minimal

**1. Corriger la vérification de l'expiration des tokens (24h)**  
*Tickets 6 — Facile (1h)*  
Dans `AccountController`, les méthodes `verifyEmail` et `resetPassword` lisent les tokens mais ne vérifient pas si `emailVerificationSentAt` / `passwordResetExpiresAt` est dépassé. Ajouter une comparaison `DateTimeImmutable::now() > expiredAt` et retourner une erreur 400 si expiré.

**2. Ajouter le renvoi du mail de vérification**  
*Ticket 14 — Facile (1h)*  
Créer `POST /api/resend-verification-email` : si l'utilisateur n'est pas vérifié, génère un nouveau token, met à jour la date, renvoie l'email.

**3. Intégrer Stripe — PaymentIntent**  
*Tickets 26, 28 — Difficile (1–2 jours)*  
Installer le SDK Stripe PHP (`composer require stripe/stripe-php`). Créer un endpoint `POST /api/orders/{id}/pay` qui crée un `PaymentIntent` Stripe et retourne le `client_secret` au frontend. Le frontend (ex. React) utilise ce secret pour afficher le formulaire Stripe Elements et confirmer le paiement.

**4. Webhook Stripe pour confirmer le paiement**  
*Ticket 27 — Difficile (quelques heures)*  
Créer `POST /api/webhooks/stripe`. Ce endpoint reçoit les événements Stripe (`payment_intent.succeeded`, `payment_intent.payment_failed`), vérifie la signature Stripe, puis met à jour le statut de la commande (`paid` ou `failed`) et déclenche la création de la facture.

**5. Création automatique de la facture après paiement**  
*Ticket 32 — Moyen (2–3h)*  
Dans le webhook Stripe (ou via un EventSubscriber Doctrine), quand le statut de la commande passe à `paid`, créer automatiquement l'entité `Invoice` avec un numéro unique (ex. `FACT-2026-00001`).

**6. Email de confirmation de commande**  
*Ticket 23 — Facile (1–2h)*  
Dans le même listener/webhook, utiliser Symfony Mailer pour envoyer un email récapitulatif (liste des services commandés, total, numéro de commande).

**7. Activer le 2FA admin (TOTP)**  
*Ticket 12 — Difficile (1 jour)*  
Le frontend 2FA est déjà en place (détection `requires2fa`, saisie du code TOTP, appel `verify2fa`). Côté backend : le login doit retourner `{ requires2fa: true }` quand `totpEnabled=true`, et exposer un endpoint `POST /api/login/2fa` qui valide le code TOTP via `scheb/2fa-google-authenticator` (déjà installé) et émet le JWT final. Ajouter aussi `POST /api/me/2fa/enable` pour générer le QR code d'activation.

---

### 🟠 Priorité 2 — Important pour l'expérience utilisateur

**8. Génération et téléchargement de facture PDF**  
*Tickets 29, 30 — Moyen (3–4h)*  
Installer DomPDF (`composer require dompdf/dompdf`). Créer un service `InvoicePdfGenerator` qui génère un PDF à partir d'un template Twig. Ajouter un endpoint `GET /api/invoices/{id}/download` qui retourne le PDF avec les bons headers (`Content-Type: application/pdf`).

**9. Fusion du panier anonyme après connexion**  
*Ticket 17 — Moyen (2–3h)*  
Modifier le processus de connexion (JSON login success handler) : si le body contient un `cartToken`, récupérer ce panier et l'associer à l'utilisateur nouvellement connecté (ou fusionner les items avec son panier existant).

**10. Rate limiting sur les endpoints publics**  
*Ticket 48 — Moyen (2h)*  
Utiliser le composant Symfony RateLimiter (`composer require symfony/rate-limiter`). Appliquer des limiteurs sur `/api/login`, `/api/password/forgot`, `/api/contact_messages`, `/api/chatbot/message` pour éviter le spam et le brute-force.

**11. Validation email unique à la modification du profil**  
*Tickets 46, 47 — Moyen (2–3h)*  
Dans le processor PATCH de l'entité User, si l'email change : vérifier que le nouvel email n'est pas déjà utilisé, passer `isVerified=false`, générer un nouveau token de vérification et envoyer un email de re-confirmation.

**12. Endpoint abonnements de l'utilisateur**  
*Tickets 34, 36 — Moyen (2–3h)*  
Créer `GET /api/me/subscriptions` qui liste les `OrderItem` actifs de l'utilisateur (filtrés sur `subscriptionStatus = active`). Créer `POST /api/subscriptions/{id}/cancel` qui passe le statut à `cancelled`.

**13. Notifications email admin (contact + chatbot)**  
*Tickets 40, 41 — Facile (1h)*  
Dans le listener de création de `ContactMessage` et dans `ChatbotController` quand `escalated=true`, envoyer un email simple à l'adresse admin (variable d'environnement `ADMIN_EMAIL`).

---

### 🟡 Priorité 3 — Améliorations et finitions

**14. Filtre commandes par date et statut**  
*Ticket 33 — Facile (1h)*  
Ajouter des `DateFilter` et `SearchFilter` sur l'entité `Order` pour permettre de filtrer par année et par statut depuis l'interface utilisateur.

**15. Option "se souvenir de moi" + refresh token**  
*Tickets 9, 10 — Moyen (3–4h)*  
Modifier le JSON login handler pour lire un paramètre `rememberMe=true` et émettre un JWT de longue durée (7 jours) au lieu d'une session courte (1h). Ou mieux, installer `gesdinet/jwt-refresh-token-bundle` pour un vrai système de refresh token.

**16. Dashboard — panier moyen par catégorie**  
*Ticket 44 — Moyen (2h)*  
Ajouter un endpoint `GET /api/admin/dashboard/average-cart-by-category` qui fait une requête Doctrine groupée sur les `OrderItem` de commandes `paid`.

**17. Recherche dans les specs techniques**  
*Ticket 43 — Facile (30 min)*  
Ajouter `technicalSpecs` dans la clause `LIKE` de la requête dans `CatalogController::search`.

**18. Tests PHPUnit**  
*Ticket 54 — Difficile (2–3 jours)*  
Écrire des tests fonctionnels pour les endpoints critiques : inscription, vérification email, login, création commande, accès admin. Utiliser le `WebTestCase` de Symfony avec une base de données de test.

**19. Upload d'images**  
*Tickets 52, 53 — Moyen (2–3h)*  
Utiliser VichUploaderBundle ou une solution simple avec `$request->files`. Créer un endpoint `POST /api/upload` qui accepte un fichier image, le stocke dans `public/uploads/`, et retourne l'URL.

---

### Résumé visuel de l'avancement

```
Authentification        █████████████░  92%  (Google SSO ✅ + 2FA frontend ✅ — manque backend 2FA + "se souvenir de moi")
Produits / Catalogue    ████████████░░  90%  (manque upload images, recherche specs)
Panier                  ████████████░░  85%  (manque fusion anonyme → compte)
Commandes               █████████░░░░░  65%  (manque paiement, email confirm, workflow étapes)
Paiement                ████░░░░░░░░░░  25%  (structure ok, intégration Stripe manquante)
Abonnements             ████░░░░░░░░░░  30%  (données ok, logique métier manquante)
Factures PDF            ████░░░░░░░░░░  30%  (entité ok, génération PDF manquante)
Compte utilisateur      ████████████░░  85%  (manque validation email changement)
Contact / Chatbot       ██████████████  95%  (manque juste notif email admin)
Backoffice              ████████████░░  85%  (manque panier moyen, filtres avancés)
Non-fonctionnel         ████████░░░░░░  55%  (manque rate limit, tests, refresh token)

GLOBAL                  █████████████░  82%  (+4 % depuis 2026-05-18 — Google SSO implémenté)
```
