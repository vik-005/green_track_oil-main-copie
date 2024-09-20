<?php

namespace App\Test\Controller;

use App\Entity\DemandesProspection;
use App\Repository\DemandesProspectionRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DemandesProspectionControllerTest extends WebTestCase
{
    /** @var KernelBrowser */
    private $client;
    /** @var DemandesProspectionRepository */
    private $repository;
    private $path = '/demande/prospection/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(DemandesProspection::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('DemandesProspection index');

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
            'demandes_prospection[pays]' => 'Testing',
            'demandes_prospection[ville]' => 'Testing',
            'demandes_prospection[region]' => 'Testing',
            'demandes_prospection[statut]' => 'Testing',
            'demandes_prospection[dateDemande]' => 'Testing',
            'demandes_prospection[dateApprobation]' => 'Testing',
            'demandes_prospection[commentaire]' => 'Testing',
        ]);

        self::assertResponseRedirects('/demande/prospection/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new DemandesProspection();
        $fixture->setPays('My Title');
        $fixture->setVille('My Title');
        $fixture->setRegion('My Title');
        $fixture->setStatut('My Title');
        $fixture->setDateDemande('My Title');
        $fixture->setDateApprobation('My Title');
        $fixture->setCommentaire('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('DemandesProspection');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new DemandesProspection();
        $fixture->setPays('My Title');
        $fixture->setVille('My Title');
        $fixture->setRegion('My Title');
        $fixture->setStatut('My Title');
        $fixture->setDateDemande('My Title');
        $fixture->setDateApprobation('My Title');
        $fixture->setCommentaire('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'demandes_prospection[pays]' => 'Something New',
            'demandes_prospection[ville]' => 'Something New',
            'demandes_prospection[region]' => 'Something New',
            'demandes_prospection[statut]' => 'Something New',
            'demandes_prospection[dateDemande]' => 'Something New',
            'demandes_prospection[dateApprobation]' => 'Something New',
            'demandes_prospection[commentaire]' => 'Something New',
        ]);

        self::assertResponseRedirects('/demande/prospection/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getPays());
        self::assertSame('Something New', $fixture[0]->getVille());
        self::assertSame('Something New', $fixture[0]->getRegion());
        self::assertSame('Something New', $fixture[0]->getStatut());
        self::assertSame('Something New', $fixture[0]->getDateDemande());
        self::assertSame('Something New', $fixture[0]->getDateApprobation());
        self::assertSame('Something New', $fixture[0]->getCommentaire());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new DemandesProspection();
        $fixture->setPays('My Title');
        $fixture->setVille('My Title');
        $fixture->setRegion('My Title');
        $fixture->setStatut('My Title');
        $fixture->setDateDemande('My Title');
        $fixture->setDateApprobation('My Title');
        $fixture->setCommentaire('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/demande/prospection/');
    }
}
