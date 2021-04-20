<?php 

namespace App\Tests\Functional;

use App\Entity\CheeseListing;
use App\Entity\User;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class CheeseListingTestv extends CustomApiTestCase
{

    // esto está op, vacia nuestra base de datos de tests para operar de nuevo lmao
    use ReloadDatabaseTrait;

    public function testCreateCheeseListing()
    {
        $client = self::createClient();

        // Check auth before login
        $client->request('POST', '/api/cheeses', [
            'headers' => [ 'Content-Type' =>'application/json']
        ]);

        $this->assertResponseStatusCodeSame(401);

        $this->createUserAndLogin($client, "cheeseplease@gmail.com", '123');

        // check auth after login
        $client->request('POST', '/api/cheeses', [
            'headers' => [ 'Content-Type' =>'application/json']
        ]);

        $this->assertResponseStatusCodeSame(400);


    }

    public function testUpdateCheeseListing()
    {
        $client = self::createClient();
        $user1 = $this->createUser("user1@gmail.com", '123');
        $user2 = $this->createUser("user2@gmail.com", '123');

        $cheeseListing = new CheeseListing("Cheese test");
        $cheeseListing->setOwner($user1);
        $cheeseListing->setPrice(1000);
        $cheeseListing->setDescription("description test");

        $em = $this->getEntityManager();

        $em->persist($cheeseListing);
        $em->flush();

        $this->logIn($client, 'user2@gmail.com', '123');
        $client->request('PUT', '/api/cheeses/'.$cheeseListing->getId(), [
            'headers' => [ 'Content-Type' =>'application/json'],
            'json' => ['title'=> 'updated', 'owner'=> '/api/users/'.$user2->getId()]
        ]);   

        $this->assertResponseStatusCodeSame(403);

        $this->logIn($client, 'user1@gmail.com', '123');
        $client->request('PUT', '/api/cheeses/'.$cheeseListing->getId(), [
            'headers' => [ 'Content-Type' =>'application/json'],
            'json' => ['title'=> 'updated']
        ]);        

        $this->assertResponseStatusCodeSame(200);

    }
}