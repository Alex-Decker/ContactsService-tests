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

require __DIR__ . '/../../src/ContactService.php';

/**
 * * @covers invalidInputException
 * @covers \ContactService
 *
 * @internal
 */
final class ContactServiceUnitTest extends TestCase {
    private $contactService;

    public function __construct(string $name = null, array $data = [], $dataName = '') {
        parent::__construct($name, $data, $dataName);
        $this->contactService = new ContactService();
    }

    //--------------------------createContact
    public function testCreationContactWithoutAnyText() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage('le nom  doit être renseigné');

        $this->contactService->createContact(null,null);
    }

    public function testCreationContactWithoutPrenom() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage('le prenom doit être renseigné');

        $this->contactService->createContact('null',null);
    }

    public function testCreationContactWithoutNom() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage('le nom  doit être renseigné');

        $this->contactService->createContact(null,'null');
    }

    public function testCreationContactWithNomPrenom() {
        $reponse = $this->contactService->createContact('luc','tom');
//        ContactServiceUnitTest::assertTrue($reponse);
        self::assertTrue($reponse);
    }

    //--------------------------getContact
    public function testgetContactWithTextAsId() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage("l'id doit être un entier non nul");

        $this->contactService->getContact('1fgd');
    }
    public function testgetContactWithIdUnder0() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage("l'id doit être un entier non nul");

        $this->contactService->getContact(-1);
    }
    public function testgetContactWithIdEqualsNull() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage("l'id doit être renseigné");

        $this->contactService->getContact(null);
    }
    public function testgetContactWithIdOk() {
        $this->contactService->createContact('luc','tom');
        $reponse = $this->contactService->getContact(1);

        self::assertEquals('luc',$reponse['nom']);
    }
    //--------------------------searchContact
    public function testSearchContactWithNumber() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage('search doit être une chaine de caractères');

        $this->contactService->searchContact(5678);

    }
    public function testSearchContactWithoutText() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage('search doit être renseigné');

        $this->contactService->searchContact('');
    }
    public function testSearchContactWithText() {
        $reponse = $this->contactService->searchContact('luc');
//        self::assertCount(3,$reponse);
        $this->assertIsArray($reponse);
//        $this->assertNotEmpty($reponse);
    }

    //--------------------------getAllContacts
    public function testgetAllContacts() {
        $reponse = $this->contactService->getAllContacts();
//        self::assertCount(12,$reponse);
        $this->assertIsArray($reponse);
//        $this->assertNotEmpty($reponse);
    }



    //--------------------------updateContact
    public function testModifyContactWithInvalidId() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage("l'id doit être un entier non nul");

        $this->contactService->updateContact(-1,'dfgdf','jjvkdjv');
        $this->contactService->updateContact('gh','dfgdf','jjvkdjv');

    }
    public function testModifyContactWithEmptyId() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage("l'id doit être renseigné");

        $this->contactService->updateContact('','dfgdf','jjvkdjv');
    }
    public function testModifyContactWithEmptyName() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage("le nom  doit être renseigné");

        $this->contactService->updateContact(1,null,'jjvkdjv');
    }
    public function testModifyContactWithEmptyPrenom() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage("le prenom doit être renseigné");

        $this->contactService->updateContact(1,'null',null);
    }
    public function testModifyContactWithValidIdNomPrenom() {
        $reponse = $this->contactService->updateContact(1,'nulll','nulll');
        self::assertTrue($reponse);
    }


    //--------------------------deleteContact
    public function testDeleteContactWithTextAsId() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage("l'id doit être un entier non nul");

        $this->contactService->deleteContact('1fgd');
    }
    public function testDeleteContactWithIdUnder0() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage("l'id doit être un entier non nul");

        $this->contactService->deleteContact(-1);
    }
    public function testDeleteContactWithIdEqualsNull() {
        $this->expectException(invalidInputException::class);
        $this->expectExceptionMessage("l'id doit être renseigné");

        $this->contactService->deleteContact(null);
    }
    public function testDeleteContactWithIdOk() {
        $reponse = $this->contactService->deleteContact(1);

        self::assertTrue($reponse);
    }


    //--------------------------deleteAllContact
    public function testDeleteAllContact() {
        $reponse = $this->contactService->deleteAllContact();

        $this->assertInstanceOf(PDOStatement::class, $reponse);
        self::assertNotFalse( $reponse);
    }







}
