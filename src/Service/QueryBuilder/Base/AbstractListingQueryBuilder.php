<?php

/*
 * This file is part of the https://github.com/bjoern-hempel/pimcore-hempel-li.git project.
 *
 * (c) 2023 Björn Hempel <bjoern@hempel.lil>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Service\QueryBuilder\Base;

use App\Constant\KeyDb;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use InvalidArgumentException;
use LogicException;
use Pimcore\Db;
use Pimcore\Model\Listing\AbstractListing;
use Symfony\Component\HttpFoundation\Request;

/**
 * Abstract class AbstractListingQueryBuilder
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
abstract class AbstractListingQueryBuilder
{
    protected QueryBuilder $queryBuilder;

    protected Connection $connection;

    /**
     * @param AbstractListing $listing
     * @param Request $request
     */
    public function __construct(
        private readonly AbstractListing $listing,
        private readonly Request $request
    )
    {
        if (!is_callable([$this->listing, 'onCreateQueryBuilder'])) {
            throw new LogicException('Method "onCreateQueryBuilder" is not callable.');
        }

        $this->connection = Db::get();

        $this->listing->onCreateQueryBuilder(function (QueryBuilder $queryBuilder) {
            $this->queryBuilder = $queryBuilder;

            $this->addQueryBefore();

            foreach ($this->all() as $key => $value) {
                $method = $this->getMethodName($key);

                if (!method_exists($this, $method) || !is_callable([$this, $method])) {
                    continue;
                }

                $callable = [$this, $method];

                if (!is_callable($callable)) {
                    throw new InvalidArgumentException(sprintf('Method "%s" is not callable.', $method));
                }

                call_user_func($callable, $value);
            }

            $this->addQueryAfter();
        });
    }

    /**
     * Returns all parameters from the request.
     *
     * @return array<string, mixed>
     */
    protected function all(): array
    {
        return array_merge(
            $this->request->request->all(),
            $this->request->query->all(),
            $this->request->attributes->all()
        );
    }

    /**
     * Converts the given _ names to CamelCase format.
     *
     * @param string $key
     * @return string
     */
    private function getMethodName(string $key): string
    {
        return lcfirst(
            implode(
                '',
                array_map(fn($part): string => ucfirst((string) $part), explode('_', $key)),
            )
        );
    }

    /**
     * Returns the like term.
     *
     * @param string $term
     * @return string
     */
    protected function getLikeTerm(string $term): string
    {
        $likeTerm = $this->connection->quote(sprintf('%%%s%%', $term));

        if (!is_string($likeTerm)) {
            throw new LogicException('Term must be a string.');
        }

        return $likeTerm;
    }

    /**
     * Calls the given $name with given arguments:
     *
     * $this->listing->{$name}(...$args)
     *
     * @param string $name
     * @param array<int, mixed> $arguments
     * @return array<int, AbstractListing>|AbstractListing
     */
    public function __call(string $name, array $arguments): array|AbstractListing
    {
        $callable = [$this->listing, $name];

        if (!is_callable($callable)) {
            throw new InvalidArgumentException(sprintf('Method "%s" is not callable.', $name));
        }

        $return = call_user_func_array($callable, $arguments);

        if (!is_array($return) && (!$return instanceof AbstractListing)) {
            throw new LogicException(sprintf('Method "%s" must return an array or an instance of AbstractListing.', $name));
        }

        return $return;
    }

    /**
     * Returns the listing query builder.
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->listing->getQueryBuilder();
    }

    /**
     * Returns the listing variable.
     *
     * @return AbstractListing
     */
    public function getListing(): AbstractListing
    {
        return $this->listing;
    }

    /**
     * Execute sort by method if available.
     *
     * @param string $sortBy
     * @return void
     */
    protected function sortBy(string $sortBy): void
    {
        $elements = explode('-', $sortBy);

        $sortOrder = strtoupper(array_pop($elements));
        $sortBy = strtolower(implode('-', $elements));

        if (!in_array($sortOrder, [KeyDb::ASC, KeyDb::DESC]) || empty($sortBy)) {
            return;
        }

        $method = 'sortBy'.ucwords($sortBy);

        if (method_exists($this, $method) && is_callable([$this, $method])) {
            $this->{$method}($sortOrder);
        }
    }

    /**
     * Do some logic before the query is built.
     *
     * @return void
     */
    protected function addQueryBefore()
    {

    }

    /**
     * @return void
     */
    protected function addQueryAfter()
    {

    }
}