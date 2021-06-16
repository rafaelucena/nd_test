<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class ItemControllerTest extends WebTestCase
{
    /**
     * @return string
     */
    public function testCreate(): string
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');

        $data = 'very secure new item data';
        $newItemData = ['data' => $data];

        $client->loginUser($user);
        $client->request('POST', '/item', $newItemData);

        $this->assertResponseIsSuccessful();
        $this->assertEquals('[]', $client->getResponse()->getContent());

        return $data;
    }

    /**
     * @depends testCreate
     *
     * @param string $data
     *
     * @return array
     */
    public function testList(string $data): array
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');

        $client->loginUser($user);
        $client->request('GET', '/item');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString($data, $client->getResponse()->getContent());

        $list = json_decode($client->getResponse()->getContent(), true);
        foreach ($list as $item) {
            if (strpos($item['data'], $data) !== false) {
                return $item;
            }
        }

        return [];
    }

    /**
     * @depends testList
     *
     * @param array $item
     *
     * @return int
     */
    public function testShow(array $item): int
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');

        $client->loginUser($user);
        $client->request('GET', '/item/' . $item['id']);

        $this->assertResponseIsSuccessful();
        $this->assertEquals($item, json_decode($client->getResponse()->getContent(), true));

        return $item['id'];
    }

    /**
     * @depends testShow
     *
     * @param int $itemId
     *
     * @return void
     */
    public function testDelete(int $itemId): void
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');

        $client->loginUser($user);
        $client->request('DELETE', '/item/' . $itemId);

        $this->assertResponseIsSuccessful();
        $this->assertEquals('[]', $client->getResponse()->getContent());
    }
}
