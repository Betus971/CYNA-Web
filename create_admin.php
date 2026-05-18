<?php
use App\Entity\User;
use Symfony\Component\Dotenv\Dotenv;
require __DIR__.'/vendor/autoload.php';
(new Dotenv())->load(__DIR__.'/.env');
$kernel = new App\Kernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();
$em = $container->get('doctrine.orm.entity_manager');
$userRepo = $em->getRepository(User::class);
if (!$userRepo->findOneBy(['email' => 'admin@cyna.it'])) {
    $user = new User();
    $user->setEmail('admin@cyna.it');
    $user->setRoles(['ROLE_ADMIN']);
    $user->setFirstname('Admin');
    $user->setLastname('CYNA');
    $user->setIsVerified(true);
    // On réutilise le hash généré précédemment ou on laisse le processor faire si on passait par l'API
    // Mais ici on injecte direct en base
    $user->setPassword('$2y$13$iB6bkoTgFyUWKCW6ammDmuTAyK8s7Melo0OExwncA8uRhoLavmqpe');
    $em->persist($user);
    $em->flush();
    echo "Admin created successfully\n";
} else {
    echo "Admin already exists\n";
}
