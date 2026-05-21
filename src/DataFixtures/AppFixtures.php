<?php

namespace App\DataFixtures;

use App\Entity\CarouselSlide;
use App\Entity\Category;
use App\Entity\HomepageText;
use App\Entity\PromoCode;
use App\Entity\SaasService;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Données de démo complètes pour CYNA.
 * Usage : php bin/console doctrine:fixtures:load --no-interaction
 *
 * Comptes :
 *   admin@cyna.local   / AdminCYNA12!@   (ROLE_ADMIN)
 *   client@cyna.local  / ClientCYNA12!@  (ROLE_USER)
 *   demo@cyna.local    / DemoCYNA12!@    (ROLE_USER)
 */
final class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function load(ObjectManager $manager): void
    {
        $this->loadCatalogue($manager);
        $this->loadCarouselSlides($manager);
        $this->loadHomepageTexts($manager);
        $this->loadPromoCodes($manager);
        $this->loadUsers($manager);
        $manager->flush();
    }

    private function loadCatalogue(ObjectManager $manager): void
    {
        $catalogue = [
            ['SOC Managed', 1, '/images/categories/soc.svg', [
                ['SOC 24/7 Essentiel', 'Surveillance continue de votre SI par nos analystes SOC. Detection, triage et escalade des incidents critiques 24h/24, 7j/7.', "Collecte et correlation SIEM (Elastic/Splunk)\nQualification des alertes EDR et reseau\nRunbooks d escalade personnalises\nReporting mensuel MTTD/MTTR\nSLA reponse critique : 15 min", '1499.00', 1],
                ['SOC Heures Ouvrees', 'Supervision en heures ouvrees (8h-20h, lun-ven) ideale pour PME et ETI.', "Surveillance 8h-20h lun-ven\nComite mensuel de securite\nIndicateurs MTTD/MTTR\nRapport mensuel executif", '799.00', 2],
                ['SOC Incident Readiness', 'Preparez votre organisation a repondre efficacement a une cyberattaque.', "Audit de maturite reponse incident\nPlan de reponse a incident (PRI)\nExercice table-top ransomware\nRapport executif et plan de remediation", '1199.00', 3],
            ]],
            ['EDR & Postes', 2, '/images/categories/edr.svg', [
                ['EDR Endpoint Pro', 'Protection endpoint nouvelle generation avec detection comportementale et reponse automatisee sur postes, serveurs et mobiles.', "Agent leger Windows/macOS/Linux\nIsolation machine en 1 clic\nRollback post-infection\nThreat Hunting\nIndicateurs de compromission IOC/TTPs", '399.00', 4],
                ['EDR Workstation Lite', 'Antimalware nouvelle generation pour postes utilisateurs. Ideal pour TPE et collectivites.', "Protection comportementale ML\nControle des peripheriques USB\nAlerting centralise\nMises a jour silencieuses\nCompatible VDI", '149.00', 5],
                ['Patch & Posture Monitoring', 'Suivi continu de l etat de patching et priorisation des correctifs sur CVE activement exploitees.', "Inventaire agents Windows/Linux/macOS\nScoring CVSS + EPSS\nAlertes email sur CVE critiques\nRapport mensuel de conformite patching", '249.00', 6],
            ]],
            ['XDR & Cloud', 3, '/images/categories/xdr.svg', [
                ['XDR Cloud Pack', 'Visibilite XDR etendue sur SaaS, identite et cloud public AWS/Azure/GCP. Detection des mouvements lateraux.', "Connecteurs AWS/Azure/GCP/M365\nDetection mouvement lateral\nAnalyse comportementale UEBA\nIntegration SIEM/SOAR\nRapport hebdomadaire", '1899.00', 7],
                ['Cloud Security Monitoring', 'Detection des erreurs de configuration cloud et ecarts de conformite sur AWS, Azure ou GCP.', "Referentiels CIS Benchmarks\nDetection IAM risky grants\nAlertes stockage public non chiffre\nScore de conformite en temps reel", '599.00', 8],
                ['Identity Threat Detection', 'Detection des compromissions de comptes privilegies Active Directory et Azure AD.', "Analyse des evenements MFA\nDetection impossible travel\nAlertes connexions suspectes AD/Azure AD\nMonitoring comptes a privileges", '699.00', 9],
            ]],
            ['Reseau Securise', 4, '/images/categories/network.svg', [
                ['Firewall Manage', 'Gestion complete et maintien en condition de securite de votre firewall perimetrique.', "Revue mensuelle des regles\nSauvegardes automatiques\nGestion firmware\n5 changements/mois inclus\nRapport de conformite trimestriel", '349.00', 10],
                ['Audit Reseau & Pentest', 'Audit complet securite reseau avec tests d intrusion et restitution direction inclus.', "Cartographie exposition externe\nTests de penetration boite grise\nPreuves de compromission documentees\nPlan de remediation priorise", '2499.00', 11],
                ['Zero Trust Starter', 'Cadrage et premiere mise en oeuvre Zero Trust pour securiser les acces sensibles.', "Atelier definition de la politique\nSegmentation reseau\nMFA renforce\nBastion acces PAM\nFeuille de route 12 mois", '1299.00', 12],
            ]],
            ['Formation Cyber', 5, '/images/categories/training.svg', [
                ['Sensibilisation Collaborateurs', 'Programme e-learning pour l ensemble de vos collaborateurs avec suivi de participation.', "6 modules e-learning (phishing, mots de passe, USB, teletravail)\nQuiz de validation des acquis\nAttestation de formation\nTableau de bord RH\nDisponible FR/EN", '299.00', 13],
                ['Simulation Phishing', 'Campagnes de simulation phishing pour mesurer la vigilance de vos equipes.', "Templates phishing FR/EN/DE\nCiblage par population\nStatistiques clics/saisie\nRapport pedagogique post-campagne\n4 campagnes/an incluses", '199.00', 14],
                ['Crise Cyber COMEX', 'Session demi-journee pour dirigeants et COMEX sur la prise de decision en crise cyber.', "Scenario ransomware immersif\nObligations notification ANSSI/CNIL\nCommunication de crise\nGuide decisionnel post-crise\nJusqu a 12 participants", '899.00', 15],
            ]],
        ];

        foreach ($catalogue as [$catName, $catOrder, $catImage, $services]) {
            $category = (new Category())
                ->setName($catName)
                ->setDisplayOrder($catOrder)
                ->setImage($catImage);
            $manager->persist($category);

            foreach ($services as [$name, $desc, $specs, $price, $priority]) {
                $svc = (new SaasService())
                    ->setName($name)
                    ->setDescription($desc)
                    ->setTechnicalSpecs($specs)
                    ->setPrice($price)
                    ->setCategory($category)
                    ->setPriority($priority)
                    ->setIsAvailable(true);
                $manager->persist($svc);
            }
        }
    }

    private function loadCarouselSlides(ObjectManager $manager): void
    {
        $slides = [
            [1, 'Protegez votre SI 24/7', 'Notre SOC manage surveille vos systemes en permanence. Detection, triage et reponse aux incidents par des experts certifies.', '/images/carousel/soc-hero.png', '/catalogue', 'Decouvrir le SOC Manage'],
            [2, 'EDR Nouvelle Generation', 'Stoppez les menaces avancees sur tous vos endpoints — postes, serveurs, mobiles — avec une reponse automatisee en temps reel.', '/images/carousel/edr-hero.png', '/catalogue', 'Voir les offres EDR'],
            [3, 'Visibilite XDR sur votre Cloud', 'Correlation multi-sources, detection des mouvements lateraux et conformite cloud sur AWS, Azure et GCP.', '/images/carousel/xdr-hero.png', '/catalogue', 'Explorer XDR & Cloud'],
            [4, 'Formez vos equipes au risque cyber', 'E-learning, simulation phishing et ateliers COMEX pour faire de chaque collaborateur le premier rempart de votre securite.', '/images/carousel/training-hero.png', '/catalogue', 'Voir les formations'],
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

    private function loadHomepageTexts(ObjectManager $manager): void
    {
        $texts = [
            ['hero-title',       'Titre principal Hero',          'Cybersecurite managee pour entreprises exigeantes'],
            ['hero-subtitle',    'Sous-titre Hero',               'CYNA protege votre systeme d information avec des services SOC, EDR et XDR operes par des experts certifies, disponibles 24h/24.'],
            ['hero-cta',         'Bouton CTA Hero',               'Decouvrir nos offres'],
            ['why-title',        'Pourquoi CYNA - Titre',         'Pourquoi choisir CYNA ?'],
            ['why-arg-1',        'Argument 1',                    'Experts certifies ANSSI, CISA et CEH — analystes SOC senior disponibles 24/7.'],
            ['why-arg-2',        'Argument 2',                    'SLA contractuel 15 min sur incidents critiques avec escalade garantie.'],
            ['why-arg-3',        'Argument 3',                    'Offres modulaires sans engagement annuel obligatoire, adaptees PME, ETI et grands comptes.'],
            ['stats-clients',    'Stat clients',                  '+230'],
            ['stats-incidents',  'Stat incidents traites',        '+12 000'],
            ['stats-sla',        'Stat taux SLA',                 '99,8 %'],
            ['footer-tagline',   'Tagline footer',                'CYNA — Votre bouclier cyber manage.'],
            ['contact-intro',    'Intro page contact',            'Une question sur nos offres ? Notre equipe vous repond sous 24h ouvrees.'],
        ];

        foreach ($texts as [$slug, $title, $body]) {
            $text = (new HomepageText())
                ->setSlug($slug)
                ->setTitle($title)
                ->setBody($body);
            $manager->persist($text);
        }
    }

    private function loadPromoCodes(ObjectManager $manager): void
    {
        $codes = [
            ['BIENVENUE20', '20.00', '2026-01-01', '2026-12-31', null, true],
            ['CYNA10',      '10.00', '2026-01-01', '2026-06-30', 50,   true],
            ['CYBER2026',   '15.00', '2026-05-01', '2026-05-31', 100,  true],
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

    private function loadUsers(ObjectManager $manager): void
    {
        $users = [
            ['admin@cyna.local',  'AdminCYNA12!@',  'Admin',  'CYNA',   ['ROLE_ADMIN']],
            ['client@cyna.local', 'ClientCYNA12!@', 'Sophie', 'Martin', ['ROLE_USER']],
            ['demo@cyna.local',   'DemoCYNA12!@',   'Thomas', 'Durand', ['ROLE_USER']],
        ];

        foreach ($users as [$email, $password, $first, $last, $roles]) {
            $user = (new User())
                ->setEmail($email)
                ->setFirstname($first)
                ->setLastname($last)
                ->setRoles($roles)
                ->setIsVerified(true);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $password)
            );
            $manager->persist($user);
        }
    }
}
