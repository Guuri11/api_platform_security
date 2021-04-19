<?php 

namespace App\Tests\Functional;

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

}