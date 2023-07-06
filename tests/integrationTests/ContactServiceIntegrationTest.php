<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PHPUnit\Framework\TestCase;

require __DIR__.'/../../src/ContactService.php';

/**
 * * @covers invalidInputException
 * @covers \ContactService
 *
 * @internal
 */
final class ContactServiceIntegrationTest extends TestCase
{
    private $contactService;

    public function __construct(string $name = null, array $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->contactService = new ContactService();
    }

    // test de suppression de toute les données, nécessaire pour nettoyer la bdd de tests à la fin
    public function testDeleteAll()
    {
        $this->contactService->createContact('null','null');
        $this->contactService->createContact('null','null');
        $this->contactService->createContact('null','null');

        $this->contactService->deleteAllContact();

        $reponse = $this->contactService->getAllContacts();
        $this->assertIsArray($reponse);
        $this->assertEmpty($reponse);
    }
    public function testgetAllContacts()
    {
        $this->contactService->deleteAllContact();

        $this->contactService->createContact('null','null');
        $this->contactService->createContact('null','null');
        $this->contactService->createContact('null','null');

        $reponse = $this->contactService->getAllContacts();
        $this->assertIsArray($reponse);
        $this->assertNotEmpty($reponse);
        self::assertCount(3,$reponse);
    }

    public function testCreationContact()
    {
        $this->contactService->createContact('nom','prenom');
        $reponse = $this->contactService->getAllContacts();
        $row = end($reponse);
        self::assertEquals('nom', $row['nom']);
        self::assertEquals('prenom', $row['prenom']);
    }

    public function testSearchContact()
    {
        $this->contactService->createContact('Svale','tom');
        $row = $this->contactService->searchContact('Svale');
//        $row = end($reponse);
        self::assertEquals('Svale', $row[0]['nom']);
        self::assertEquals('tom', $row[0]['prenom']);
    }
    public function testGetContact()
    {
        $reponse = $this->contactService->getAllContacts();
        $row = end($reponse);
        $this->contactService->createContact('mal','fem');

        $row = $this->contactService->getContact($row['id']+1);

        self::assertEquals('mal', $row['nom']);
        self::assertEquals('fem', $row['prenom']);
    }

    public function testModifyContact()
    {
        $this->contactService->createContact('lolo','toto');
        $reponse = $this->contactService->getAllContacts();
        $row = end($reponse);
        $this->contactService->updateContact($row['id'],'lala','tata');
        $reponse2 = $this->contactService->getAllContacts();
        $row2 = end($reponse2);
        self::assertEquals('lala', $row2['nom']);
        self::assertEquals('tata', $row2['prenom']);
    }

    public function testDeleteContact()
    {
        $this->contactService->createContact('max','map');
        $reponse = $this->contactService->getAllContacts();
        $row = end($reponse);
        $this->contactService->deleteContact($row['id']);
        $reponse2 = $this->contactService->getAllContacts();
        $row2 = end($reponse2);
        self::assertNotEquals($row['id'], $row2['id']);
    }

}
