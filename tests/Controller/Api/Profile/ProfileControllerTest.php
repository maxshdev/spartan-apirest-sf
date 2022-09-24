<?php

namespace App\Tests\Controller\Api\Profile;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProfileControllerTest extends WebTestCase
{

    // public function testNotAuthorized()
    // {
    //     $client = static::createClient();
    //     $client->request(
    //         'POST',
    //         '/api/profiles',
    //         [],
    //         [],
    //         ['CONTENT_TYPE' => 'application/json'],
    //         '{"firstname":""}'
    //     );
    //     $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    // }

    public function testCreateProfileInvalidData()
    {
        $client = static::createClient();

        $this->sendRequest($client, ['firstname' => '']);
        
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    // public function testCreateProfileEmptyData()
    // {
    //     $client = static::createClient();
    //     $this->sendRequest($client, []);
    //     $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    // }

    // public function testSuccess()
    // {
    //     $client = static::createClient();
    //     $this->sendRequest($client, ['firstname' => 'Bilbo Bolson']);
    //     $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    // }

    private function sendRequest(KernelBrowser $client, array $json)
    {
        $client->request(
            'POST',
            '/api/profiles',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($json)
        );
    }
}