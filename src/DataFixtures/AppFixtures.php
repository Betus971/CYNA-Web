<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\SaasService;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Données de démo pour le catalogue CYNA et un compte admin.
 * Usage : php bin/console doctrine:fixtures:load --no-interaction
 */
final class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        /* ---------------- Catégories ---------------- */
        $categoriesData = [
            ['name' => 'SOC manage', 'order' => 1, 'image' => null],
            ['name' => 'EDR & postes', 'order' => 2, 'image' => null],
            ['name' => 'XDR & cloud', 'order' => 3, 'image' => null],
            ['name' => 'Reseau securise', 'order' => 4, 'image' => null],
            ['name' => 'Formation cyber', 'order' => 5, 'image' => null],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $cat = (new Category())
                ->setName($data['name'])
                ->setDisplayOrder($data['order'])
                ->setImage($data['image']);
            $manager->persist($cat);
            $categories[$data['name']] = $cat;
        }

        /* ---------------- Services SaaS ---------------- */
        $servicesData = [
            ['SOC manage 24/7', 'Surveillance continue, triage des alertes et escalade incident critique.', 'Collecte SIEM, correlation, runbooks d escalade, reporting mensuel.', '1499.00', 'SOC manage', 100, true],
            ['SOC pilotage essentiel', 'Supervision cyber en heures ouvrees pour PME et ETI.', 'Comite mensuel, indicateurs MTTD/MTTR, qualification alertes EDR.', '799.00', 'SOC manage', 90, true],
            ['SOC incident readiness', 'Preparation operationnelle a la reponse incident.', 'Plan de crise, matrice contacts, exercice table-top et rapport executif.', '1199.00', 'SOC manage', 75, true],
            ['EDR Endpoint Pro', 'Detection comportementale et reponse automatisee sur postes et serveurs.', 'Isolation machine, rollback, IOC, politiques Windows/macOS/Linux.', '399.00', 'EDR & postes', 95, true],
            ['EDR Workstation Lite', 'Protection endpoint avancee pour postes utilisateurs.', 'Antimalware nouvelle generation, controle peripheriques, alerting centralise.', '149.00', 'EDR & postes', 60, true],
            ['Patch posture monitoring', 'Suivi des correctifs critiques et priorisation des failles exploitees.', 'Inventaire agents, scoring CVSS/EPSS, exports CSV et alertes email.', '249.00', 'EDR & postes', 55, true],
            ['XDR Cloud Pack', 'Visibilite XDR sur environnements SaaS, identite et cloud public.', 'Connecteurs AWS Azure GCP M365, detection lateral movement, tableaux de bord.', '1899.00', 'XDR & cloud', 80, true],
            ['Cloud Security Monitoring', 'Detection des erreurs de configuration cloud et ecarts de conformite.', 'CIS benchmarks, IAM risky grants, stockage public, recommandations.', '599.00', 'XDR & cloud', 70, true],
            ['Identity Threat Detection', 'Detection des compromissions de comptes privilegies.', 'Analyse MFA, connexions impossibles, impossible travel, alertes AD/Azure AD.', '699.00', 'XDR & cloud', 65, true],
            ['Firewall manage', 'Gestion et maintien en condition de securite du firewall perimetrique.', 'Revue regles, sauvegardes, firmware, changements mensuels inclus.', '349.00', 'Reseau securise', 50, true],
            ['Audit reseau & pentest', 'Audit annuel reseau avec rapport technique et restitution direction.', 'Cartographie exposition, tests controles, preuves, plan de remediation.', '2499.00', 'Reseau securise', 40, true],
            ['Zero Trust starter', 'Cadrage et premiere mise en oeuvre Zero Trust pour acces sensibles.', 'Segmentation, MFA, bastion, politique acces conditionnel.', '1299.00', 'Reseau securise', 45, true],
            ['Sensibilisation cyber', 'Programme de formation pour collaborateurs et managers.', 'Modules e-learning, quiz, suivi participation, attestation.', '299.00', 'Formation cyber', 30, true],
            ['Simulation phishing', 'Campagnes de simulation phishing et reporting pedagogique.', 'Templates FR/EN, ciblage populations, statistiques clic/saisie.', '199.00', 'Formation cyber', 20, true],
            ['Formation crise cyber COMEX', 'Session courte pour dirigeants sur decisions de crise cyber.', 'Scenario ransomware, obligations notification, communication de crise.', '899.00', 'Formation cyber', 25, true],
        ];

        foreach ($servicesData as [$name, $desc, $specs, $price, $catName, $priority, $available]) {
            $svc = (new SaasService())
                ->setName($name)
                ->setDescription($desc)
                ->setTechnicalSpecs($specs)
                ->setPrice($price)
                ->setCategory($categories[$catName] ?? null)
                ->setPriority($priority)
                ->setIsAvailable($available);
            $manager->persist($svc);
        }

        /* ---------------- Compte admin de demo ---------------- */
        $admin = (new User())
            ->setEmail('admin@cyna.local')
            ->setFirstname('Admin')
            ->setLastname('CYNA')
            ->setRoles(['ROLE_ADMIN'])
            ->setIsVerified(true);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'AdminCYNA12!@'));
        $manager->persist($admin);

        $manager->flush();
    }
}
