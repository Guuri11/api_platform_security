<?php 

namespace App\Tests\Functional;

use App\Entity\User;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserTest extends CustomApiTestCase
{

    // test all tests from class php bin/phpunit tests/Functional/UserResourceTest.php

    // esto está op, vacia nuestra base de datos de tests para operar de nuevo lmao
    use ReloadDatabaseTrait;

    public function testCreateUser()
    {
        $client = self::createClient();

        $client->request('POST', '/api/users', [
            'json' => [
                'email' => 'cheeseplease@gmail.com',
                'username'=> 'cheeseplease',
                'password'=> 'lel'
            ]
        ]);

        $this->assertResponseStatusCodeSame(201);

        $this->logIn($client, 'cheeseplease@gmail.com', 'lel');

        
    }

    public function testUpdateUser()
    {
        $client = self::createClient();

        $user = $this->createUserAndLogin($client, 'cheeseplease@gmail.com', '123');

        $client->request('PUT', '/api/users/'.$user->getId(), [
            'json' => [
                'username'=> 'cheeseplease2',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'username' => 'cheeseplease2'
        ]);

        
    }


    // the purpose of this test in be available of set a property but not return it directly
    public function testGetUser()
    {
        $client = self::createClient();
        
        $user = $this->createUserAndLogin($client, 'cheeseplease@gmail.com', '123');
        $user->setPhoneNumber('555 124 234');

        $em = $this->getEntityManager();
        $em->flush();

        $client->request('GET', '/api/users/'.$user->getId());
        $this->assertJsonContains([
            'username' => 'cheeseplease'
        ]);

        $data = $client->getResponse()->toArray();

        $this->assertArrayNotHasKey('phoneNumber', $data);

        // We do this because after each request is made, all objects and container are reset. Chapter 23
        $user = $em->getRepository(User::class)->find($user->getId());
        $user->setRoles(['ROLE_ADMIN']);
        $em->flush();

        $this->logIn($client, 'cheeseplease@gmail.com', '123');

        $client->request('GET', '/api/users/'.$user->getId());
        $this->assertJsonContains([
            'phoneNumber' => '555 124 234'
        ]);

    }

}