<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace XApi\Repository\InMemory\Tests\Functional;

use XApi\Repository\Api\Test\Functional\StatementRepositoryTest as BaseStatementRepositoryTest;
use XApi\Repository\InMemory\StatementRepository;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class StatementRepositoryTest extends BaseStatementRepositoryTest
{
    protected function createStatementRepository()
    {
        return new StatementRepository();
    }

    protected function cleanDatabase()
    {
    }

    /**
     * @return ObjectManager
     */
    protected function createObjectManager()
    {
        $config = new Configuration();
        $config->setProxyDir(__DIR__.'/../proxies');
        $config->setProxyNamespace('Proxy');
        $config->setHydratorDir(__DIR__.'/../hydrators');
        $config->setHydratorNamespace('Hydrator');
        $fileLocator = new SymfonyFileLocator(
            array(__DIR__.'/../../metadata' => 'XApi\Repository\Api\Mapping'),
            '.mongodb.xml'
        );
        $driver = new XmlDriver($fileLocator);
        $config->setMetadataDriverImpl($driver);

        return DocumentManager::create(new Connection(), $config);
    }

    protected function getStatementClassName()
    {
        return 'XApi\Repository\Api\Mapping\MappedStatement';
    }
}
