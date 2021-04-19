<?php 

namespace App\Tests\Functional;

use App\ApiPlatform\Test\ApiTestCase;
use App\Entity\User;

class CheeseListingTestv extends ApiTestCase
{

    public function testCreateCheeseListing()
    {
        $client = self::createClient();

        // Check auth
        $client->request('POST', '/api/cheeses', [
            'headers' => [ 'Content-Type' =>'application/json']
        ]);

        $this->assertResponseStatusCodeSame(401);

        // Check login system
        $user = new User();

        $user->setEmail("cheeseplease@gmail.com")
            ->setUsername("cheeseplease")
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$OL2E4ztghm/aEWwjF51ueA$xKxe5AXzDh0wrDmW52SZijL8AMAUu2wRJ8hxCQR2CEw');

        $em = self::$container->get('doctrine')->getManager();

        $em->persist($user);
        $em->flush();

        $client->request('POST', '/login', [
            'headers' => [ 'Content-Type' =>'application/json'],
            'json' => [
                'email'=>'cheeseplease@gmail.com',
                'password'=>'123'
            ]
        ]);
        $this->assertResponseStatusCodeSame(204);

    }

}