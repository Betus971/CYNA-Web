<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\CarouselSlide;
use App\Entity\Category;
use App\Entity\HomepageText;
use App\Entity\Invoice;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\PromoCode;
use App\Entity\SaasService;
use App\Entity\User;
use App\Enum\OrderStatus;
use App\Enum\SubscriptionStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Données de démo complètes pour CYNA.
 * Usage : php bin/console doctrine:fixtures:load --no-interaction
 *
 * Comptes créés :
 *   admin@cyna.local   / AdminCYNA12!@   (ROLE_ADMIN)
 *   client@cyna.local  / ClientCYNA12!@  (ROLE_USER) — Sophie Martin
 *   demo@cyna.local    / DemoCYNA12!@    (ROLE_USER) — Thomas Durand
 *
 * Sophie Martin  → 2 adresses, 3 commandes payées, 1 commande échouée, 1 commande en attente
 * Thomas Durand  → 1 adresse, 1 commande payée, 1 commande active
 */
final class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        // ── Catalogue ────────────────────────────────────────────────────────────
        $services = $this->loadCatalogue($manager);

        // ── Contenu éditorial ────────────────────────────────────────────────────
        $this->loadCarouselSlides($manager);
        $this->loadHomepageTexts($manager);
        $this->loadPromoCodes($manager);

        // ── Utilisateurs + données transactionnelles ─────────────────────────────
        [$admin, $sophie, $thomas] = $this->loadUsers($manager);
        $this->loadSophieData($manager, $sophie, $services);
        $this->loadThomasData($manager, $thomas, $services);

        $manager->flush();
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Catalogue produits / catégories
    // ─────────────────────────────────────────────────────────────────────────────

    /** @return array<string, SaasService> */
    private function loadCatalogue(ObjectManager $manager): array
    {
        $catalogue = [
            ['SOC Managed', 1, '/images/categories/soc.png', [
                ['SOC 24/7 Essentiel',    'Surveillance continue de votre SI par nos analystes SOC. Detection, triage et escalade des incidents critiques 24h/24, 7j/7.',                       "Collecte et correlation SIEM (Elastic/Splunk)\nQualification des alertes EDR et reseau\nRunbooks d escalade personnalises\nReporting mensuel MTTD/MTTR\nSLA reponse critique : 15 min",       '1499.00', 1, '/images/services/soc-247.png'],
                ['SOC Heures Ouvrees',     'Supervision en heures ouvrees (8h-20h, lun-ven) ideale pour PME et ETI.',                                                                          "Surveillance 8h-20h lun-ven\nComite mensuel de securite\nIndicateurs MTTD/MTTR\nRapport mensuel executif",                                                                                  '799.00',  2, '/images/services/soc-ho.png'],
                ['SOC Incident Readiness', 'Preparez votre organisation a repondre efficacement a une cyberattaque.',                                                                          "Audit de maturite reponse incident\nPlan de reponse a incident (PRI)\nExercice table-top ransomware\nRapport executif et plan de remediation",                                               '1199.00', 3, '/images/services/soc-ir.png'],
            ]],
            ['EDR & Postes', 2, '/images/categories/edr.png', [
                ['EDR Endpoint Pro',          'Protection endpoint nouvelle generation avec detection comportementale et reponse automatisee sur postes, serveurs et mobiles.',                 "Agent leger Windows/macOS/Linux\nIsolation machine en 1 clic\nRollback post-infection\nThreat Hunting\nIndicateurs de compromission IOC/TTPs",                                               '399.00', 4, '/images/services/edr-pro.png'],
                ['EDR Workstation Lite',      'Antimalware nouvelle generation pour postes utilisateurs. Ideal pour TPE et collectivites.',                                                    "Protection comportementale ML\nControle des peripheriques USB\nAlerting centralise\nMises a jour silencieuses\nCompatible VDI",                                                              '149.00', 5, '/images/services/edr-lite.png'],
                ['Patch & Posture Monitoring','Suivi continu de l etat de patching et priorisation des correctifs sur CVE activement exploitees.',                                             "Inventaire agents Windows/Linux/macOS\nScoring CVSS + EPSS\nAlertes email sur CVE critiques\nRapport mensuel de conformite patching",                                                      '249.00', 6, '/images/services/patch.png'],
            ]],
            ['XDR & Cloud', 3, '/images/categories/xdr.png', [
                ['XDR Cloud Pack',           'Visibilite XDR etendue sur SaaS, identite et cloud public AWS/Azure/GCP. Detection des mouvements lateraux.',                                   "Connecteurs AWS/Azure/GCP/M365\nDetection mouvement lateral\nAnalyse comportementale UEBA\nIntegration SIEM/SOAR\nRapport hebdomadaire",                                                    '1899.00', 7, '/images/services/xdr.png'],
                ['Cloud Security Monitoring','Detection des erreurs de configuration cloud et ecarts de conformite sur AWS, Azure ou GCP.',                                                   "Referentiels CIS Benchmarks\nDetection IAM risky grants\nAlertes stockage public non chiffre\nScore de conformite en temps reel",                                                          '599.00',  8, '/images/services/cloud-monitor.png'],
                ['Identity Threat Detection','Detection des compromissions de comptes privilegies Active Directory et Azure AD.',                                                              "Analyse des evenements MFA\nDetection impossible travel\nAlertes connexions suspectes AD/Azure AD\nMonitoring comptes a privileges",                                                       '699.00',  9, '/images/services/identity.png'],
            ]],
            ['Reseau Securise', 4, '/images/categories/network.png', [
                ['Firewall Manage',   'Gestion complete et maintien en condition de securite de votre firewall perimetrique.',                                                                  "Revue mensuelle des regles\nSauvegardes automatiques\nGestion firmware\n5 changements/mois inclus\nRapport de conformite trimestriel",                                                       '349.00',  10, '/images/services/firewall.png'],
                ['Audit Reseau & Pentest','Audit complet securite reseau avec tests d intrusion et restitution direction inclus.',                                                              "Cartographie exposition externe\nTests de penetration boite grise\nPreuves de compromission documentees\nPlan de remediation priorise",                                                     '2499.00', 11, '/images/services/pentest.png'],
                ['Zero Trust Starter','Cadrage et premiere mise en oeuvre Zero Trust pour securiser les acces sensibles.',                                                                    "Atelier definition de la politique\nSegmentation reseau\nMFA renforce\nBastion acces PAM\nFeuille de route 12 mois",                                                                        '1299.00', 12, '/images/services/zerotrust.png'],
            ]],
            ['Formation Cyber', 5, '/images/categories/training.png', [
                ['Sensibilisation Collaborateurs','Programme e-learning pour l ensemble de vos collaborateurs avec suivi de participation.',                                                   "6 modules e-learning (phishing, mots de passe, USB, teletravail)\nQuiz de validation des acquis\nAttestation de formation\nTableau de bord RH\nDisponible FR/EN",                         '299.00', 13, '/images/services/elearning.png'],
                ['Simulation Phishing',          'Campagnes de simulation phishing pour mesurer la vigilance de vos equipes.',                                                               "Templates phishing FR/EN/DE\nCiblage par population\nStatistiques clics/saisie\nRapport pedagogique post-campagne\n4 campagnes/an incluses",                                              '199.00', 14, '/images/services/phishing.png'],
                ['Crise Cyber COMEX',            'Session demi-journee pour dirigeants et COMEX sur la prise de decision en crise cyber.',                                                   "Scenario ransomware immersif\nObligations notification ANSSI/CNIL\nCommunication de crise\nGuide decisionnel post-crise\nJusqu a 12 participants",                                        '899.00', 15, '/images/services/comex.png'],
            ]],
        ];

        $index = [];
        foreach ($catalogue as [$catName, $catOrder, $catImage, $services]) {
            $category = (new Category())
                ->setName($catName)
                ->setDisplayOrder($catOrder)
                ->setImage($catImage);
            $manager->persist($category);

            foreach ($services as [$name, $desc, $specs, $price, $priority, $image]) {
                $svc = (new SaasService())
                    ->setName($name)
                    ->setDescription($desc)
                    ->setTechnicalSpecs($specs)
                    ->setPrice($price)
                    ->setCategory($category)
                    ->setPriority($priority)
                    ->setImage($image)
                    ->setIsAvailable(true);
                $manager->persist($svc);
                $index[$name] = $svc;
            }
        }

        return $index;
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Slides carousel
    // ─────────────────────────────────────────────────────────────────────────────

    private function loadCarouselSlides(ObjectManager $manager): void
    {
        $slides = [
            [1, 'Protegez votre SI 24/7',              'Notre SOC manage surveille vos systemes en permanence. Detection, triage et reponse aux incidents par des experts certifies.',          '/images/carousel/soc-hero.png',      '/catalogue', 'Decouvrir le SOC Manage'],
            [2, 'EDR Nouvelle Generation',              'Stoppez les menaces avancees sur tous vos endpoints — postes, serveurs, mobiles — avec une reponse automatisee en temps reel.',          '/images/carousel/edr-hero.png',      '/catalogue', 'Voir les offres EDR'],
            [3, 'Visibilite XDR sur votre Cloud',       'Correlation multi-sources, detection des mouvements lateraux et conformite cloud sur AWS, Azure et GCP.',                               '/images/carousel/xdr-hero.png',      '/catalogue', 'Explorer XDR & Cloud'],
            [4, 'Formez vos equipes au risque cyber',   'E-learning, simulation phishing et ateliers COMEX pour faire de chaque collaborateur le premier rempart de votre securite.',            '/images/carousel/training-hero.png', '/catalogue', 'Voir les formations'],
        ];

        foreach ($slides as [$order, $title, $sub, $image, $link, $cta]) {
            $slide = (new CarouselSlide())
                ->setTitle($title)
                ->setSubtitle($sub)
                ->setImage($image)
                ->setLinkUrl($link)
                ->setCtaLabel($cta)
                ->setDisplayOrder($order)
                ->setActive(true);
            $manager->persist($slide);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Textes homepage
    // ─────────────────────────────────────────────────────────────────────────────

    private function loadHomepageTexts(ObjectManager $manager): void
    {
        $texts = [
            ['hero-title',      'Titre principal Hero',       'Cybersecurite managee pour entreprises exigeantes'],
            ['hero-subtitle',   'Sous-titre Hero',            'CYNA protege votre systeme d information avec des services SOC, EDR et XDR operes par des experts certifies, disponibles 24h/24.'],
            ['hero-cta',        'Bouton CTA Hero',            'Decouvrir nos offres'],
            ['why-title',       'Pourquoi CYNA - Titre',      'Pourquoi choisir CYNA ?'],
            ['why-arg-1',       'Argument 1',                 'Experts certifies ANSSI, CISA et CEH — analystes SOC senior disponibles 24/7.'],
            ['why-arg-2',       'Argument 2',                 'SLA contractuel 15 min sur incidents critiques avec escalade garantie.'],
            ['why-arg-3',       'Argument 3',                 'Offres modulaires sans engagement annuel obligatoire, adaptees PME, ETI et grands comptes.'],
            ['stats-clients',   'Stat clients',               '+230'],
            ['stats-incidents', 'Stat incidents traites',     '+12 000'],
            ['stats-sla',       'Stat taux SLA',              '99,8 %'],
            ['footer-tagline',  'Tagline footer',             'CYNA — Votre bouclier cyber manage.'],
            ['contact-intro',   'Intro page contact',         'Une question sur nos offres ? Notre equipe vous repond sous 24h ouvrees.'],
        ];

        foreach ($texts as [$slug, $title, $body]) {
            $text = (new HomepageText())
                ->setSlug($slug)
                ->setTitle($title)
                ->setBody($body);
            $manager->persist($text);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Codes promo
    // ─────────────────────────────────────────────────────────────────────────────

    private function loadPromoCodes(ObjectManager $manager): void
    {
        $codes = [
            ['BIENVENUE20', '20.00', '2026-01-01', '2026-12-31', null, true],
            ['CYNA10',      '10.00', '2026-01-01', '2026-06-30', 50,   true],
            ['CYBER2026',   '15.00', '2026-05-01', '2026-09-30', 100,  true],
            ['NOEL25',      '25.00', '2025-12-01', '2025-12-31', 200,  false],
        ];

        foreach ($codes as [$code, $pct, $start, $end, $max, $active]) {
            $promo = (new PromoCode())
                ->setCode($code)
                ->setPercentage($pct)
                ->setStartsAt(new \DateTimeImmutable($start))
                ->setEndsAt(new \DateTimeImmutable($end))
                ->setMaxUsages($max)
                ->setActive($active);
            $manager->persist($promo);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Utilisateurs
    // ─────────────────────────────────────────────────────────────────────────────

    /** @return array{0: User, 1: User, 2: User} */
    private function loadUsers(ObjectManager $manager): array
    {
        $definitions = [
            ['admin@cyna.local',  'AdminCYNA12!@',  'Admin',  'CYNA',   ['ROLE_ADMIN']],
            ['client@cyna.local', 'ClientCYNA12!@', 'Sophie', 'Martin', ['ROLE_USER']],
            ['demo@cyna.local',   'DemoCYNA12!@',   'Thomas', 'Durand', ['ROLE_USER']],
        ];

        $users = [];
        foreach ($definitions as [$email, $password, $first, $last, $roles]) {
            $user = (new User())
                ->setEmail($email)
                ->setFirstname($first)
                ->setLastname($last)
                ->setRoles($roles)
                ->setIsVerified(true);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $manager->persist($user);
            $users[] = $user;
        }

        return [$users[0], $users[1], $users[2]];
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Données Sophie Martin
    // ─────────────────────────────────────────────────────────────────────────────

    /** @param array<string, SaasService> $services */
    private function loadSophieData(ObjectManager $manager, User $sophie, array $services): void
    {
        $addr1 = $this->makeAddress($sophie, 'Sophie', 'Martin',
            '42 Rue de la Paix', null, 'Paris', 'Île-de-France', '75001', 'France', '+33601020304');
        $addr2 = $this->makeAddress($sophie, 'Sophie', 'Martin',
            '8 Avenue des Champs', 'Bât B', 'Lyon', 'Auvergne-Rhône-Alpes', '69001', 'France', '+33601020304');
        $manager->persist($addr1);
        $manager->persist($addr2);

        // ── 2022 : SOC 5 ans ─────────────────────────────────────────────────────
        $o2022 = $this->makeOrder($sophie, $addr1, 'CYNA-2022-000100', '89940.00',
            OrderStatus::PAID, new \DateTimeImmutable('2022-03-15'), 'pi_sophie_2022', 'succeeded');
        $i2022 = $this->makeOrderItem($o2022, $services['SOC 24/7 Essentiel'], 1, 60, '1499.00',
            new \DateTimeImmutable('2022-03-15'), new \DateTimeImmutable('2027-03-15'), SubscriptionStatus::ACTIVE);
        $o2022->addItem($i2022);
        $manager->persist($o2022); $manager->persist($i2022);
        $manager->persist($this->makeInvoice($o2022, 'INV-2022-00000001', '74950.00', '14990.00', new \DateTimeImmutable('2022-03-15')));

        // ── 2023 : EDR 2 ans ──────────────────────────────────────────────────────
        $o2023 = $this->makeOrder($sophie, $addr1, 'CYNA-2023-000200', '9576.00',
            OrderStatus::PAID, new \DateTimeImmutable('2023-06-01'), 'pi_sophie_2023', 'succeeded');
        $i2023a = $this->makeOrderItem($o2023, $services['EDR Endpoint Pro'], 1, 24, '399.00',
            new \DateTimeImmutable('2023-06-01'), new \DateTimeImmutable('2025-06-01'), SubscriptionStatus::EXPIRED);
        $i2023b = $this->makeOrderItem($o2023, $services['EDR Workstation Lite'], 1, 24, '149.00',
            new \DateTimeImmutable('2023-06-01'), new \DateTimeImmutable('2025-06-01'), SubscriptionStatus::EXPIRED);
        $o2023->addItem($i2023a)->addItem($i2023b);
        $manager->persist($o2023); $manager->persist($i2023a); $manager->persist($i2023b);
        $manager->persist($this->makeInvoice($o2023, 'INV-2023-00000200', '7980.00', '1596.00', new \DateTimeImmutable('2023-06-01')));

        // ── 2024 : XDR 2 ans + formation ─────────────────────────────────────────
        $o2024 = $this->makeOrder($sophie, $addr1, 'CYNA-2024-000300', '46275.00',
            OrderStatus::PAID, new \DateTimeImmutable('2024-01-10'), 'pi_sophie_2024', 'succeeded');
        $i2024a = $this->makeOrderItem($o2024, $services['XDR Cloud Pack'], 1, 24, '1899.00',
            new \DateTimeImmutable('2024-01-10'), new \DateTimeImmutable('2026-01-10'), SubscriptionStatus::EXPIRED);
        $i2024b = $this->makeOrderItem($o2024, $services['Crise Cyber COMEX'], 1, 1, '899.00',
            new \DateTimeImmutable('2024-01-10'), new \DateTimeImmutable('2024-02-10'), SubscriptionStatus::EXPIRED);
        $o2024->addItem($i2024a)->addItem($i2024b);
        $manager->persist($o2024); $manager->persist($i2024a); $manager->persist($i2024b);
        $manager->persist($this->makeInvoice($o2024, 'INV-2024-00000300', '38562.50', '7712.50', new \DateTimeImmutable('2024-01-10')));

        // ── 2025 : SOC heures + Patch 12 mois ────────────────────────────────────
        $o2025 = $this->makeOrder($sophie, $addr2, 'CYNA-2025-000400', '12576.00',
            OrderStatus::PAID, new \DateTimeImmutable('2025-04-20'), 'pi_sophie_2025', 'succeeded');
        $i2025a = $this->makeOrderItem($o2025, $services['SOC Heures Ouvrees'], 1, 12, '799.00',
            new \DateTimeImmutable('2025-04-20'), new \DateTimeImmutable('2026-04-20'), SubscriptionStatus::ACTIVE);
        $i2025b = $this->makeOrderItem($o2025, $services['Patch & Posture Monitoring'], 1, 12, '249.00',
            new \DateTimeImmutable('2025-04-20'), new \DateTimeImmutable('2026-04-20'), SubscriptionStatus::ACTIVE);
        $o2025->addItem($i2025a)->addItem($i2025b);
        $manager->persist($o2025); $manager->persist($i2025a); $manager->persist($i2025b);
        $manager->persist($this->makeInvoice($o2025, 'INV-2025-00000400', '10480.00', '2096.00', new \DateTimeImmutable('2025-04-20')));

        // ── 2026 : Réseau 5 ans (active) ─────────────────────────────────────────
        $o2026 = $this->makeOrder($sophie, $addr1, 'CYNA-2026-000500', '20940.00',
            OrderStatus::ACTIVE, new \DateTimeImmutable('2026-01-05'), 'pi_sophie_2026', 'succeeded');
        $i2026 = $this->makeOrderItem($o2026, $services['Firewall Manage'], 1, 60, '349.00',
            new \DateTimeImmutable('2026-01-05'), new \DateTimeImmutable('2031-01-05'), SubscriptionStatus::ACTIVE);
        $o2026->addItem($i2026);
        $manager->persist($o2026); $manager->persist($i2026);
        $manager->persist($this->makeInvoice($o2026, 'INV-2026-00000500', '17450.00', '3490.00', new \DateTimeImmutable('2026-01-05')));

        // ── Commande récente ce mois ──────────────────────────────────────────────
        $oRecent = $this->makeOrder($sophie, $addr2, 'CYNA-2026-000600', '1098.00',
            OrderStatus::PAID, new \DateTimeImmutable('-3 weeks'), 'pi_sophie_recent', 'succeeded');
        $iRa = $this->makeOrderItem($oRecent, $services['Sensibilisation Collaborateurs'], 1, 1, '299.00',
            new \DateTimeImmutable('-3 weeks'), new \DateTimeImmutable('+1 week'), SubscriptionStatus::ACTIVE);
        $iRb = $this->makeOrderItem($oRecent, $services['Simulation Phishing'], 1, 1, '199.00',
            new \DateTimeImmutable('-3 weeks'), new \DateTimeImmutable('+1 week'), SubscriptionStatus::ACTIVE);
        $iRc = $this->makeOrderItem($oRecent, $services['Audit Reseau & Pentest'], 1, 1, '899.00',  // not in fixtures? use closest
            new \DateTimeImmutable('-3 weeks'), new \DateTimeImmutable('+1 week'), SubscriptionStatus::ACTIVE);
        $oRecent->addItem($iRa)->addItem($iRb)->addItem($iRc);
        $manager->persist($oRecent); $manager->persist($iRa); $manager->persist($iRb); $manager->persist($iRc);
        $manager->persist($this->makeInvoice($oRecent, 'INV-2026-00000600', '915.00', '183.00', new \DateTimeImmutable('-3 weeks')));

        // ── Échec de paiement ─────────────────────────────────────────────────────
        $oFail = $this->makeOrder($sophie, $addr2, 'CYNA-2026-000700', '1899.00',
            OrderStatus::FAILED, new \DateTimeImmutable('-2 weeks'), 'pi_sophie_fail', 'payment_failed');
        $oFail->setPaymentFailureReason('Carte refusée. Fonds insuffisants.');
        $iFail = $this->makeOrderItem($oFail, $services['XDR Cloud Pack'], 1, 12, '1899.00');
        $oFail->addItem($iFail);
        $manager->persist($oFail); $manager->persist($iFail);
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Données Thomas Durand
    // ─────────────────────────────────────────────────────────────────────────────

    /** @param array<string, SaasService> $services */
    private function loadThomasData(ObjectManager $manager, User $thomas, array $services): void
    {
        $addr = $this->makeAddress($thomas, 'Thomas', 'Durand',
            '15 Rue de la Liberté', null, 'Bordeaux', 'Nouvelle-Aquitaine', '33000', 'France', '+33607080910');
        $manager->persist($addr);

        // ── 2022 : Zero Trust 2 ans ───────────────────────────────────────────────
        $o2022 = $this->makeOrder($thomas, $addr, 'CYNA-2022-001100', '31176.00',
            OrderStatus::PAID, new \DateTimeImmutable('2022-09-01'), 'pi_thomas_2022', 'succeeded');
        $i2022 = $this->makeOrderItem($o2022, $services['Zero Trust Starter'], 1, 24, '1299.00',
            new \DateTimeImmutable('2022-09-01'), new \DateTimeImmutable('2024-09-01'), SubscriptionStatus::EXPIRED);
        $o2022->addItem($i2022);
        $manager->persist($o2022); $manager->persist($i2022);
        $manager->persist($this->makeInvoice($o2022, 'INV-2022-00001100', '25980.00', '5196.00', new \DateTimeImmutable('2022-09-01')));

        // ── 2023 : Identity Threat 5 ans ──────────────────────────────────────────
        $o2023 = $this->makeOrder($thomas, $addr, 'CYNA-2023-001200', '41940.00',
            OrderStatus::PAID, new \DateTimeImmutable('2023-02-14'), 'pi_thomas_2023', 'succeeded');
        $i2023 = $this->makeOrderItem($o2023, $services['Identity Threat Detection'], 1, 60, '699.00',
            new \DateTimeImmutable('2023-02-14'), new \DateTimeImmutable('2028-02-14'), SubscriptionStatus::ACTIVE);
        $o2023->addItem($i2023);
        $manager->persist($o2023); $manager->persist($i2023);
        $manager->persist($this->makeInvoice($o2023, 'INV-2023-00001200', '34950.00', '6990.00', new \DateTimeImmutable('2023-02-14')));

        // ── 2024 : Cloud Pack 12 mois ─────────────────────────────────────────────
        $o2024 = $this->makeOrder($thomas, $addr, 'CYNA-2024-001300', '22788.00',
            OrderStatus::PAID, new \DateTimeImmutable('2024-07-01'), 'pi_thomas_2024', 'succeeded');
        $i2024 = $this->makeOrderItem($o2024, $services['XDR Cloud Pack'], 1, 12, '1899.00',
            new \DateTimeImmutable('2024-07-01'), new \DateTimeImmutable('2025-07-01'), SubscriptionStatus::EXPIRED);
        $o2024->addItem($i2024);
        $manager->persist($o2024); $manager->persist($i2024);
        $manager->persist($this->makeInvoice($o2024, 'INV-2024-00001300', '18990.00', '3798.00', new \DateTimeImmutable('2024-07-01')));

        // ── 2025 : EDR Pro 2 ans ──────────────────────────────────────────────────
        $o2025 = $this->makeOrder($thomas, $addr, 'CYNA-2025-001400', '9576.00',
            OrderStatus::PAID, new \DateTimeImmutable('2025-11-15'), 'pi_thomas_2025', 'succeeded');
        $i2025 = $this->makeOrderItem($o2025, $services['EDR Endpoint Pro'], 1, 24, '399.00',
            new \DateTimeImmutable('2025-11-15'), new \DateTimeImmutable('2027-11-15'), SubscriptionStatus::ACTIVE);
        $o2025->addItem($i2025);
        $manager->persist($o2025); $manager->persist($i2025);
        $manager->persist($this->makeInvoice($o2025, 'INV-2025-00001400', '7980.00', '1596.00', new \DateTimeImmutable('2025-11-15')));

        // ── 2026 : SOC Incident + formation ce mois ───────────────────────────────
        $o2026a = $this->makeOrder($thomas, $addr, 'CYNA-2026-001500', '2398.00',
            OrderStatus::PAID, new \DateTimeImmutable('-2 months'), 'pi_thomas_2026a', 'succeeded');
        $i2026a = $this->makeOrderItem($o2026a, $services['SOC Incident Readiness'], 1, 1, '1199.00',
            new \DateTimeImmutable('-2 months'), new \DateTimeImmutable('-1 month'), SubscriptionStatus::EXPIRED);
        $i2026b = $this->makeOrderItem($o2026a, $services['Simulation Phishing'], 1, 1, '199.00',
            new \DateTimeImmutable('-2 months'), new \DateTimeImmutable('-1 month'), SubscriptionStatus::EXPIRED);
        $o2026a->addItem($i2026a)->addItem($i2026b);
        $manager->persist($o2026a); $manager->persist($i2026a); $manager->persist($i2026b);
        $manager->persist($this->makeInvoice($o2026a, 'INV-2026-00001500', '1998.33', '399.67', new \DateTimeImmutable('-2 months')));

        // ── 2026 : Pentest + Cloud Security (ce mois) ────────────────────────────
        $o2026b = $this->makeOrder($thomas, $addr, 'CYNA-2026-001600', '3098.00',
            OrderStatus::PAID, new \DateTimeImmutable('-1 week'), 'pi_thomas_2026b', 'succeeded');
        $i2026c = $this->makeOrderItem($o2026b, $services['Audit Reseau & Pentest'], 1, 1, '2499.00',
            new \DateTimeImmutable('-1 week'), new \DateTimeImmutable('+3 weeks'), SubscriptionStatus::ACTIVE);
        $i2026d = $this->makeOrderItem($o2026b, $services['Cloud Security Monitoring'], 1, 1, '599.00',
            new \DateTimeImmutable('-1 week'), new \DateTimeImmutable('+3 weeks'), SubscriptionStatus::ACTIVE);
        $o2026b->addItem($i2026c)->addItem($i2026d);
        $manager->persist($o2026b); $manager->persist($i2026c); $manager->persist($i2026d);
        $manager->persist($this->makeInvoice($o2026b, 'INV-2026-00001600', '2581.67', '516.33', new \DateTimeImmutable('-1 week')));
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────────

    private function makeAddress(
        User $user,
        string $firstname,
        string $lastname,
        string $adresse1,
        ?string $adresse2,
        string $city,
        string $region,
        string $zipCode,
        string $country,
        string $mobilephone,
    ): Address {
        return (new Address())
            ->setUser($user)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setAdresse1($adresse1)
            ->setAdresse2($adresse2)
            ->setCity($city)
            ->setRegion($region)
            ->setZipCode($zipCode)
            ->setCountry($country)
            ->setMobilephone($mobilephone);
    }

    private function makeOrder(
        User $user,
        Address $billingAddress,
        string $reference,
        string $totalPrice,
        OrderStatus $status,
        \DateTimeImmutable $createdAt,
        ?string $stripePaymentIntentId = null,
        ?string $stripePaymentStatus = null,
    ): Order {
        $order = (new Order())
            ->setUser($user)
            ->setBillingAddress($billingAddress)
            ->setReference($reference)
            ->setTotalPrice($totalPrice)
            ->setStatus($status)
            ->setCreatedAt($createdAt);

        if ($stripePaymentIntentId !== null) {
            $order->setStripePaymentIntentId($stripePaymentIntentId);
        }
        if ($stripePaymentStatus !== null) {
            $order->setStripePaymentStatus($stripePaymentStatus);
        }
        if ($status === OrderStatus::PAID || $status === OrderStatus::ACTIVE) {
            $order->setPaidAt($createdAt->modify('+2 minutes'));
        }

        return $order;
    }

    private function makeInvoice(
        Order $order,
        string $number,
        string $totalAmount,
        string $taxAmount,
        \DateTimeImmutable $issuedAt,
    ): Invoice {
        return (new Invoice())
            ->setOrder($order)
            ->setNumber($number)
            ->setTotalAmount($totalAmount)
            ->setTaxAmount($taxAmount)
            ->setIssuedAt($issuedAt);
    }

    private function makeOrderItem(
        Order $order,
        SaasService $service,
        int $quantity,
        int $durationMonths,
        string $unitPrice,
        ?\DateTimeImmutable $startsAt = null,
        ?\DateTimeImmutable $endsAt = null,
        ?SubscriptionStatus $subscriptionStatus = null,
    ): OrderItem {
        return (new OrderItem())
            ->setOrder($order)
            ->setSaasService($service)
            ->setProductNameSnapshot($service->getName())
            ->setUnitPriceSnapshot($unitPrice)
            ->setQuantity($quantity)
            ->setDurationMonths($durationMonths)
            ->setSubscriptionStartsAt($startsAt)
            ->setSubscriptionEndsAt($endsAt)
            ->setSubscriptionStatus($subscriptionStatus);
    }
}
