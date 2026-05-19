<?php
require 'vendor/autoload.php';
$kernel = new App\Kernel('dev', true);
$kernel->boot();
$em = $kernel->getContainer()->get('doctrine')->getManager();
$user = $em->getRepository(App\Entity\User::class)->find(2);
$jwtManager = $kernel->getContainer()->get('lexik_jwt_authentication.jwt_manager');
echo $jwtManager->create($user);
