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

namespace App\Command\Version;

use App\Constant\Parameter;
use App\Tests\Functional\Command\Version\VersionCommandTest;
use App\Version\Version;
use Exception;
use Ixnode\PhpContainer\Json;
use Ixnode\PhpException\File\FileNotFoundException;
use Ixnode\PhpException\File\FileNotReadableException;
use Ixnode\PhpException\Function\FunctionJsonEncodeException;
use Ixnode\PhpException\Type\TypeInvalidException;
use Ixnode\PhpNamingConventions\NamingConventions;
use JsonException;
use LogicException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class VersionCommand
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-25)
 * @since 0.1.0 (2023-09-25) First version.
 * @example bin/console version:show
 * @link VersionCommandTest
 */
#[AsCommand(
    name: self::COMMAND_NAME,
    description: self::COMMAND_DESCRIPTION
)]
class VersionCommand extends Command
{
    final public const COMMAND_NAME = 'version:show';

    final public const COMMAND_DESCRIPTION = 'Shows the version of this app.';

    protected const NAME_OPTION_FORMAT = 'format';

    protected const NAME_OPTION_FORMAT_SHORT = 'f';

    protected const OPTION_FORMAT_TEXT = 'text';

    protected const OPTION_FORMAT_JSON = 'json';

    protected const KEY_VERSION = 'version';

    protected const KEY_DATE = 'date';

    protected const KEY_LICENSE = 'license';

    protected const KEY_AUTHORS = 'authors';

    protected const KEY_PHP = 'php-version';

    protected const KEY_SYMFONY = 'symfony-version';

    protected const KEY_PIMCORE = 'pimcore-version';

    protected const KEY_COMPOSER = 'composer-version';

    protected const KEY_DRIVER_NAME = 'driver-name';

    protected const KEY_ENVIRONMENT = 'environment';

//    protected const KEY_MAIL_TRANSPORT = 'mail-transport';
//
//    protected const KEY_MAIL_HOST = 'mail-host';
//
//    protected const KEY_MAIL_PORT = 'mail-port';

    protected const KEY_HOST_NAME = 'host-name';

//    protected const KEY_ERROR_RECIPIENTS = 'error-recipients';

    /**
     * @param Version $version
     * @param KernelInterface $kernel
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(protected Version $version, protected KernelInterface $kernel, protected ParameterBagInterface $parameterBag)
    {
        parent::__construct();
    }

    /**
     * Configures the command.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addOption(
                self::NAME_OPTION_FORMAT,
                self::NAME_OPTION_FORMAT_SHORT,
                InputOption::VALUE_REQUIRED,
                'Output format.',
                self::OPTION_FORMAT_TEXT
            )
        ;
    }

    /**
     * Returns the version array.
     *
     * @return array{version: string, license: string, authors: string[], php-version: string, symfony-version: string}
     * @throws Exception
     */
    protected function getVersionArray(): array
    {
        $authors = [];

        foreach (Version::VALUE_AUTHORS as $author) {
            $authors[] = $author;
        }

        return [
            self::KEY_VERSION => $this->version->getVersion(),
            self::KEY_DATE => $this->version->getDate(),
            self::KEY_LICENSE => $this->version->getLicense(),
            self::KEY_AUTHORS => $authors,
            self::KEY_DRIVER_NAME => $this->version->getDriverName(),
            self::KEY_ENVIRONMENT => $this->version->getEnvironment($this->kernel),
            //self::KEY_MAIL_TRANSPORT => $this->version->getMailTransport($this->parameterBag),
            //self::KEY_MAIL_HOST => $this->version->getMailHost($this->parameterBag),
            //self::KEY_MAIL_PORT => $this->version->getMailPort($this->parameterBag),
            self::KEY_HOST_NAME => $this->parameterBag->get(Parameter::ENVIRONMENT_HOST_NAME),
            //self::KEY_ERROR_RECIPIENTS =>$this->parameterBag->get(self::PARAMETER_EMAIL_RECIPIENTS_ERROR),
            self::KEY_PHP => $this->version->getVersionPhp(),
            self::KEY_SYMFONY => $this->version->getVersionSymfony(),
            self::KEY_PIMCORE => $this->version->getVersionPimcore(),
            self::KEY_COMPOSER => $this->version->getVersionComposer(),
        ];
    }

    /**
     * Prints the version array as text.
     *
     * @param OutputInterface $output
     * @param array{version: string, license: string, authors: string[], php-version: string, symfony-version: string} $versionArray
     * @return void
     * @throws Exception
     */
    protected function printText(OutputInterface $output, array $versionArray): void
    {
        $templateFormat = '%-25s %s';

        $output->writeln('');
        foreach ($versionArray as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $title = (new NamingConventions($key))->getTitle();
                    $output->writeln(sprintf($templateFormat, sprintf('  %s:', $title), $item));
                }
                continue;
            }

            $title = (new NamingConventions($key))->getTitle();
            $title = str_replace('Php', 'PHP', $title);
            $output->writeln(sprintf($templateFormat, sprintf('  %s:', $title), $value));
        }
        $output->writeln('');
    }

    /**
     * Prints the version array as json.
     *
     * @param OutputInterface $output
     * @param array{version: string, license: string, authors: string[], php-version: string, symfony-version: string} $versionArray
     * @return void
     * @throws JsonException
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     * @throws FunctionJsonEncodeException
     * @throws TypeInvalidException
     */
    protected function printJson(OutputInterface $output, array $versionArray): void
    {
        $output->writeln((new Json($versionArray))->getJsonStringFormatted());
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws TypeInvalidException
     * @throws FunctionJsonEncodeException
     * @throws JsonException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $format = $input->getOption(self::NAME_OPTION_FORMAT);

        if (!is_string($format) && !is_numeric($format)) {
            throw new LogicException(sprintf('Invalid format "%s".', gettype($format)));
        }

        match ($format) {
            self::OPTION_FORMAT_TEXT => $this->printText($output, $this->getVersionArray()),
            self::OPTION_FORMAT_JSON => $this->printJson($output, $this->getVersionArray()),
            default => throw new LogicException(sprintf('Unsupported output format "%s".', $format)),
        };

        return Command::SUCCESS;
    }
}
