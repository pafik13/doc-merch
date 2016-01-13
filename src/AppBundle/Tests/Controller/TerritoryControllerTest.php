<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TerritoryControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/territories');
    }

    public function testNew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/territories/add');
    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/territories/edit/{id}');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/territories/{id}');
    }

    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/territories/{id}');
    }

}
