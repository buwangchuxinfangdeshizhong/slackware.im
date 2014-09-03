<?php
namespace Slackiss\Bundle\SlackwareBundle\Tests;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

class SymfonyTestCase extends WebTestCase {

    protected $em;
    protected $container;

    public function setUp()
    {
        parent::setUp();
        $kernel = static::createKernel();
        $kernel->boot();
        $this->container = $kernel->getContainer();
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->generateSchema();
    }

    protected function generateSchema()
    {
        $metadatas = $this->getMetadatas();
        if ( ! empty($metadatas)) {
            $tool = new SchemaTool($this->em);
            $tool->updateSchema($metadatas);
        } else {
            throw new Doctrine\DBAL\Schema\SchemaException('No Metadata Classes to process.');
        }
    }
    protected function getMetadatas()
    {
        return $this->em->getMetadataFactory()->getAllMetadata();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $metadatas = $this->getMetadatas();
        if(!empty($metadatas)){
            $tool = new SchemaTool($this->em);
            $tool->dropDatabase();
        }else{
            throw new SchemaException('No Metadata Classes to process.');
        }
    }
}