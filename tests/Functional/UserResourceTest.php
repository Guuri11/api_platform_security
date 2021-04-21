<?php 

namespace App\Tests\Functional;

use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UserTest extends CustomApiTestCase
{

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

}