<?php

/*
 * This file is part of the xAPI package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace XApi\Repository\InMemory;

use XApi\Repository\Api\Mapping\MappedStatement;
use XApi\Repository\Api\StatementRepository as BaseStatementRepository;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class StatementRepository extends BaseStatementRepository
{
    /**
     * @var MappedStatement[]
     */
    private $statements = array();

    /**
     * {@inheritdoc}
     */
    protected function findMappedStatement(array $criteria)
    {
        foreach ($this->statements as $statement) {
            if ($this->doesStatementMatchCriteria($statement, $criteria)) {
                return $statement;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function findMappedStatements(array $criteria)
    {
        $result = array();

        foreach ($this->statements as $statement) {
            if ($this->doesStatementMatchCriteria($statement, $criteria)) {
                $result[] = $statement;
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function storeMappedStatement(MappedStatement $mappedStatement, $flush)
    {
        $this->statements[] = $mappedStatement;
    }

    private function doesStatementMatchCriteria(MappedStatement $statement, array $criteria)
    {
        if (isset($criteria['id']) && $criteria['id'] === $statement->id) {
            return true;
        }

        return false;
    }
}
