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
            ['name' => 'SOC manage',        'order' => 1, 'image' => null],
            ['name' => 'Protection postes', 'order' => 2, 'image' => null],
            ['name' => 'Cloud & XDR',       'order' => 3, 'image' => null],
            ['name' => 'Reseaux',           'order' => 4, 'image' => null],
            ['name' => 'Formation cyber',   'order' => 5, 'image' => null],
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
            ['SOC manage 24/7', 'Surveillance continue avec analystes dedies et alertes prioritaires.', '1499.00', 'SOC manage', 100, true],
            ['SOC pilotage essentiel', 'Pilotage securite operationnelle pour PME, supervision 8h/j.', '799.00', 'SOC manage', 90, true],
            ['EDR Endpoint Pro', 'Detection comportementale et reponse sur tous vos postes.', '399.00', 'Protection postes', 95, true],
            ['EDR Workstation Lite', 'Protection antivirus avancee pour postes utilisateurs.', '149.00', 'Protection postes', 60, true],
            ['XDR Cloud Pack', 'Visibilite XDR sur infrastructures cloud (AWS / Azure / GCP).', '1899.00', 'Cloud & XDR', 80, true],
            ['Cloud Security Monitoring', 'Detection des erreurs de configuration cloud.', '599.00', 'Cloud & XDR', 70, true],
            ['Firewall manage', 'Gestion et maintenance de votre firewall periphique.', '349.00', 'Reseaux', 50, true],
            ['Audit reseaux & pentest', 'Audit annuel de la securite reseau + rapport executif.', '2499.00', 'Reseaux', 40, true],
            ['Sensibilisation cyber', 'Programme de formation pour vos collaborateurs.', '299.00', 'Formation cyber', 30, true],
            ['Simulation phishing', 'Campagnes de simulation et reporting RGPD.', '199.00', 'Formation cyber', 20, true],
        ];

        foreach ($servicesData as [$name, $desc, $price, $catName, $priority, $available]) {
            $svc = (new SaasService())
                ->setName($name)
                ->setDescription($desc)
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
