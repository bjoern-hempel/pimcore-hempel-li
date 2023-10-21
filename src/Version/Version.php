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

namespace App\Version;

use App\Constant\Environment;
use App\Constant\KeyCamelCase;
use App\Constant\Parameter;
use App\Exception\FileNotFoundException;
use Doctrine\DBAL\Platforms\MariaDb1027Platform;
use Doctrine\DBAL\Platforms\MariaDBPlatform;
use Doctrine\DBAL\Platforms\MySQL57Platform;
use Doctrine\DBAL\Platforms\MySQL80Platform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\PostgreSQL100Platform;
use Doctrine\DBAL\Platforms\PostgreSQL94Platform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Platforms\SqlitePlatform;
use Ixnode\PhpContainer\File;
use Ixnode\PhpDsnParser\DsnParser;
use LogicException;
use Pimcore\Db;
use Pimcore\Version as PimcoreVersion;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class Version
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 */
class Version
{
    final public const VALUE_LICENSE = 'Copyright (c) 2023 Björn Hempel <bjoern@hempel.li>';

    final public const VALUE_AUTHORS = [
        'Björn Hempel <bjoern@hempel.li>',
    ];

    final public const PATH_VERSION = 'VERSION';

    final public const INDEX_VERSION = 'version';

    final public const INDEX_DATE = 'date';

    final public const INDEX_LICENSE = 'license';

    final public const INDEX_AUTHORS = 'authors';

    final public const INDEX_PHP = 'php-version';

    final public const INDEX_SYMFONY = 'symfony-version';

    final public const INDEX_PIMCORE = 'pimcore-version';

    final public const INDEX_COMPOSER = 'composer-version';

    protected const APP_COMPOSER = 'composer';

    /**
     * Version constructor.
     *
     * @param string $rootDir
     */
    public function __construct(protected string $rootDir)
    {
    }

    /**
     * Returns the version of this application.
     *
     * @return string
     */
    public function getVersion(): string
    {
        $versionFile = $this->getVersionFile();

        return (new File($versionFile))->getContentAsTextTrim();
    }

    /**
     * Returns the date of version of this application.
     *
     * @param string $format
     * @return string
     * @throws FileNotFoundException
     */
    public function getDate(string $format = 'l, F d, Y - H:i:s'): string
    {
        $versionFile = $this->getVersionFile();

        $mtime = filemtime($versionFile);

        if ($mtime === false) {
            throw new FileNotFoundException($versionFile);
        }

        return date ($format, $mtime);
    }

    /**
     * Returns the license of this application.
     *
     * @return string
     */
    public function getLicense(): string
    {
        return self::VALUE_LICENSE;
    }

    /**
     * Returns the author of this application.
     *
     * @return array<int, string>
     */
    public function getAuthors(): array
    {
        return self::VALUE_AUTHORS;
    }

    /**
     * Returns the composer version.
     *
     * @return string
     */
    public function getVersionComposer(): string
    {
        $output = [];

        $returnValue = null;

        exec(sprintf('%s -V', self::APP_COMPOSER), $output, $returnValue);

        if ($returnValue !== 0) {
            return sprintf('%s is not available', self::APP_COMPOSER);
        }

        $string = implode("\n", $output);

        $matches = [];

        $result = preg_match('~[0-9]+\.[0-9]+\.[0-9]~', $string, $matches);

        if ($result !== 1) {
            return sprintf('Unable to get %s version.', self::APP_COMPOSER);
        }

        return strval(current($matches));
    }

    /**
     * Returns the php version of this application.
     *
     * @param bool $short
     * @return string
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function getVersionPhp(bool $short = false): string
    {
        $version = phpversion();

        if ($short) {
            $version = preg_replace('~\.[0-9]+$~', '', $version);

            if (!is_string($version)) {
                throw new LogicException('Unable to replace string.');
            }
        }

        return $version;
    }

    /**
     * Returns the symfony version of this application.
     *
     * @param bool $short
     * @return string
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function getVersionSymfony(bool $short = false): string
    {
        $version = Kernel::VERSION;

        if ($short) {
            $version = preg_replace('~\.[0-9]+$~', '', $version);

            if (!is_string($version)) {
                throw new LogicException('Unable to replace string.');
            }
        }

        return $version;
    }

    /**
     * Returns the pimcore version of this application.
     *
     * @param bool $short
     * @return string
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function getVersionPimcore(bool $short = false): string
    {
        $version = PimcoreVersion::getVersion();

        if ($short) {
            $version = preg_replace('~\.[0-9]+$~', '', $version);

            if (!is_string($version)) {
                throw new LogicException('Unable to replace string.');
            }

            $version = str_replace('v', '', $version);

            if (!is_string($version)) {
                throw new LogicException('Unable to replace string.');
            }
        }

        return $version;
    }

    /**
     * Returns driver name of db.
     *
     * @throws LogicException
     */
    public function getDriverName(): string
    {
        $connection = Db::getConnection();

        $driver = $connection->getDriver()->getDatabasePlatform();

        $platformClassName = $driver::class;

        return match (true) {
            str_contains($platformClassName, 'MariaDBPlatform') => 'MariaDB - unknown version', /* @link MariaDBPlatform */
            str_contains($platformClassName, 'MariaDb1027Platform') => 'MariaDB - 10.x', /* @link MariaDb1027Platform */
            str_contains($platformClassName, 'MySQL57Platform') => 'MySQL - 5.7', /* @link MySQL57Platform */
            str_contains($platformClassName, 'MySQL80Platform') => 'MySQL - 8.0', /* @link MySQL80Platform */
            str_contains($platformClassName, 'MySQLPlatform') => 'MySQL - unknown version', /* @link MySQLPlatform */
            str_contains($platformClassName, 'PostgreSQL100Platform') => 'PostgreSQL 10.0', /* @link PostgreSQL100Platform */
            str_contains($platformClassName, 'PostgreSQL94Platform') => 'PostgreSQL 9.4', /* @link PostgreSQL94Platform */
            str_contains($platformClassName, 'PostgreSQLPlatform') => 'PostgreSQL - unknown version', /* @link PostgreSQLPlatform */
            str_contains($platformClassName, 'SqlitePlatform') => 'Sqlite - unknown version', /* @link SqlitePlatform */
            default => throw new LogicException(sprintf('Unsupported database platform "%s".', $platformClassName)),
        };
    }

    /**
     * Returns DSN array.
     *
     * @param ParameterBagInterface $parameterBag
     * @return array{transport: string, host: string, port: int}
     */
    private function getMailDsn(ParameterBagInterface $parameterBag): array
    {
        $dsn = $parameterBag->get(Parameter::MAILER_DNS);

        if (!is_string($dsn)) {
            throw new LogicException(sprintf('Unsupported type given: %s', gettype($dsn)));
        }

        $dsnParser = new DsnParser($dsn);

        if ($dsnParser->getParsed() === false) {
            throw new LogicException(sprintf('Unsupported dsn "%s".', $dsn));
        }

        return [
            KeyCamelCase::TRANSPORT => strtoupper(strval($dsnParser->getProtocol())),
            KeyCamelCase::HOST => strval($dsnParser->getHost()),
            KeyCamelCase::PORT => intval($dsnParser->getPort()),
        ];
    }

    /**
     * Returns the transport of current mail.
     *
     * @param ParameterBagInterface $parameterBag
     * @return string
     */
    public function getMailTransport(ParameterBagInterface $parameterBag): string
    {
        $parts = $this->getMailDsn($parameterBag);

        return $parts[KeyCamelCase::TRANSPORT];
    }

    /**
     * Returns the host of current mail.
     *
     * @param ParameterBagInterface $parameterBag
     * @return string
     */
    public function getMailHost(ParameterBagInterface $parameterBag): string
    {
        $parts = $this->getMailDsn($parameterBag);

        return $parts[KeyCamelCase::HOST];
    }

    /**
     * Returns the port of current mail.
     *
     * @param ParameterBagInterface $parameterBag
     * @return int
     */
    public function getMailPort(ParameterBagInterface $parameterBag): int
    {
        $parts = $this->getMailDsn($parameterBag);

        return $parts[KeyCamelCase::PORT];
    }

    /**
     * Returns the environment name of this application.
     *
     * @param KernelInterface $kernel
     * @return string
     */
    public function getEnvironment(KernelInterface $kernel): string
    {
        return match($kernel->getEnvironment()) {
            Environment::DEVELOPMENT => Environment::DEVELOPMENT_NAME,
            Environment::STAGING => Environment::STAGING_NAME,
            Environment::PRODUCTION => Environment::PRODUCTION_NAME,
            Environment::TEST => Environment::TEST_NAME,
            default => throw new LogicException(sprintf('Unsupported environment "%s".', $kernel->getEnvironment())),
        };
    }

    /**
     * Returns all information (non verbose, without versions).
     *
     * @return array{version: string, license: string, authors: array<int, string>}
     * @throws FileNotFoundException
     */
    public function getAllNonVerbose(): array
    {
        return [
            self::INDEX_VERSION => $this->getVersion(),
            self::INDEX_DATE => $this->getDate(),
            self::INDEX_LICENSE => $this->getLicense(),
            self::INDEX_AUTHORS => $this->getAuthors(),
        ];
    }

    /**
     * Returns all information.
     *
     * @return array<string, mixed>
     * @throws FileNotFoundException
     */
    public function getAll(): array
    {
        return [
            self::INDEX_VERSION => $this->getVersion(),
            self::INDEX_DATE => $this->getDate(),
            self::INDEX_LICENSE => $this->getLicense(),
            self::INDEX_AUTHORS => $this->getAuthors(),
            self::INDEX_PHP => $this->getVersionPhp(),
            self::INDEX_SYMFONY => $this->getVersionSymfony(),
            self::INDEX_PIMCORE => $this->getVersionPimcore(),
            self::INDEX_COMPOSER => $this->getVersionComposer(),
        ];
    }

    /**
     * Returns the version file.
     *
     * @return string
     */
    protected function getVersionFile(): string
    {
        return sprintf('%s/%s', $this->rootDir, self::PATH_VERSION);
    }
}
