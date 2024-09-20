<?php

namespace App\Test\Controller;

use App\Entity\Utilisateurs;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UtilisateursControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client;
    /** @var UtilisateurRepository */
    private $repository;
    private $path = '/utilisateur/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Utilisateurs::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Utilisateur index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'utilisateur[email]' => 'Testing',
            'utilisateur[role]' => 'Testing',
            'utilisateur[password]' => 'Testing',
            'utilisateur[nomUtilisateur]' => 'Testing',
            'utilisateur[photo]' => 'Testing',
            'utilisateur[dateCreation]' => 'Testing',
            'utilisateur[prenomutilisateur]' => 'Testing',
        ]);

        self::assertResponseRedirects('/utilisateur/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Utilisateurs();
        $fixture->setEmail('My Title');
        $fixture->setRole('My Title');
        $fixture->setPassword('My Title');
        $fixture->setNomUtilisateur('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setDateCreation('My Title');
        $fixture->setPrenomutilisateur('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Utilisateur');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Utilisateurs();
        $fixture->setEmail('My Title');
        $fixture->setRole('My Title');
        $fixture->setPassword('My Title');
        $fixture->setNomUtilisateur('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setDateCreation('My Title');
        $fixture->setPrenomutilisateur('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'utilisateur[email]' => 'Something New',
            'utilisateur[role]' => 'Something New',
            'utilisateur[password]' => 'Something New',
            'utilisateur[nomUtilisateur]' => 'Something New',
            'utilisateur[photo]' => 'Something New',
            'utilisateur[dateCreation]' => 'Something New',
            'utilisateur[prenomutilisateur]' => 'Something New',
        ]);

        self::assertResponseRedirects('/utilisateur/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getRole());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getNomUtilisateur());
        self::assertSame('Something New', $fixture[0]->getPhoto());
        self::assertSame('Something New', $fixture[0]->getDateCreation());
        self::assertSame('Something New', $fixture[0]->getPrenomutilisateur());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Utilisateurs();
        $fixture->setEmail('My Title');
        $fixture->setRole('My Title');
        $fixture->setPassword('My Title');
        $fixture->setNomUtilisateur('My Title');
        $fixture->setPhoto('My Title');
        $fixture->setDateCreation('My Title');
        $fixture->setPrenomutilisateur('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/utilisateur/');
    }
}
