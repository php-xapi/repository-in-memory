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

use Rhumsaa\Uuid\Uuid;
use Xabbuh\XApi\Common\Exception\NotFoundException;
use Xabbuh\XApi\Model\Actor;
use Xabbuh\XApi\Model\Statement;
use Xabbuh\XApi\Model\StatementId;
use Xabbuh\XApi\Model\StatementsFilter;
use XApi\Repository\Api\StatementRepositoryInterface;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
final class StatementRepository implements StatementRepositoryInterface
{
    /**
     * @var Statement[]
     */
    private $statements = array();

    /**
     * {@inheritdoc}
     */
    public function findStatementById(StatementId $statementId, Actor $authority = null)
    {
        if (!isset($this->statements[$statementId->getValue()])) {
            throw new NotFoundException(sprintf('A statement with id "%s" could not be found.', $statementId->getValue()));
        }

        $statement = $this->statements[$statementId->getValue()];

        if ($statement->isVoidStatement()) {
            throw new NotFoundException(sprintf('The statement with id "%s" is a voiding statement.', $statementId->getValue()));
        }

        return $statement;
    }

    /**
     * {@inheritdoc}
     */
    public function findVoidedStatementById(StatementId $voidedStatementId, Actor $authority = null)
    {
        if (!isset($this->statements[$voidedStatementId->getValue()])) {
            throw new NotFoundException(sprintf('A statement with id "%s" could not be found.', $voidedStatementId->getValue()));
        }

        $statement = $this->statements[$voidedStatementId->getValue()];

        if (!$statement->isVoidStatement()) {
            throw new NotFoundException(sprintf('The statement with id "%s" is not a voiding statement.', $voidedStatementId->getValue()));
        }

        return $statement;
    }

    /**
     * {@inheritdoc}
     */
    public function findStatementsBy(StatementsFilter $criteria, Actor $authority = null)
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function storeStatement(Statement $statement, $flush = true)
    {
        if (null === $statement->getId()) {
            $statement = $statement->withId(StatementId::fromUuid(Uuid::uuid4()));
        }

        $this->statements[$statement->getId()->getValue()] = $statement;

        return $statement->getId();
    }
}
