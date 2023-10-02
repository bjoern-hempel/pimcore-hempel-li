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

namespace App\Model\DataObject\Data;

use LogicException;
use Pimcore\Model;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Pimcore\Model\DataObject\ClassDefinition\Data\EqualComparisonInterface;
use Pimcore\Model\DataObject\ClassDefinition\Data\QueryResourcePersistenceAwareInterface;
use Pimcore\Model\DataObject\ClassDefinition\Data\ResourcePersistenceAwareInterface;
use Pimcore\Model\DataObject\ClassDefinition\Data\TypeDeclarationSupportInterface;
use Pimcore\Model\DataObject\ClassDefinition\Data\VarExporterInterface;
use Pimcore\Model\DataObject\Localizedfield;
use Pimcore\Normalizer\NormalizerInterface;

/**
 * Class Markdown
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2023-09-29)
 * @since 0.1.0 (2023-09-29) First version.
 */
class Markdown extends Data implements ResourcePersistenceAwareInterface, QueryResourcePersistenceAwareInterface, TypeDeclarationSupportInterface, EqualComparisonInterface, VarExporterInterface, NormalizerInterface
{
    use Model\DataObject\Traits\DataHeightTrait;
    use Model\DataObject\Traits\DataWidthTrait;
    use Model\DataObject\ClassDefinition\Data\Extension\Text;
    use Model\DataObject\Traits\SimpleComparisonTrait;
    use Model\DataObject\Traits\SimpleNormalizerTrait;

    /**
     * @internal
     *
     * @var int|null
     */
    public ?int $maxLength = null;

    /**
     * @internal
     */
    public bool $showCharCount = false;

    /**
     * @internal
     */
    public bool $excludeFromSearchIndex = false;

    /**
     * @return int|null
     */
    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    /**
     * @param int|null $maxLength
     * @return void
     */
    public function setMaxLength(?int $maxLength): void
    {
        $this->maxLength = $maxLength;
    }

    /**
     * @return bool
     */
    public function isShowCharCount(): bool
    {
        return $this->showCharCount;
    }

    /**
     * @param bool $showCharCount
     * @return void
     */
    public function setShowCharCount(bool $showCharCount): void
    {
        $this->showCharCount = $showCharCount;
    }

    /**
     * @return bool
     */
    public function isExcludeFromSearchIndex(): bool
    {
        return $this->excludeFromSearchIndex;
    }

    /**
     * @param bool $excludeFromSearchIndex
     * @return $this
     */
    public function setExcludeFromSearchIndex(bool $excludeFromSearchIndex): static
    {
        $this->excludeFromSearchIndex = $excludeFromSearchIndex;

        return $this;
    }

    /**
     * @see ResourcePersistenceAwareInterface::getDataForResource
     *
     * @param mixed $data
     * @param null|Model\DataObject\Concrete $object
     * @param array<int|string, mixed> $params
     * @return string|null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getDataForResource(mixed $data, Model\DataObject\Concrete $object = null, array $params = []): ?string
    {
        if (!is_string($data) && !is_null($data)) {
            throw new LogicException('Data must be a string or null.');
        }

        return $data;
    }

    /**
     * @see ResourcePersistenceAwareInterface::getDataFromResource
     *
     * @param mixed $data
     * @param null|Model\DataObject\Concrete $object
     * @param array<int|string, mixed> $params
     * @return string|null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getDataFromResource(mixed $data, Model\DataObject\Concrete $object = null, array $params = []): ?string
    {
        if (!is_string($data) && !is_null($data)) {
            throw new LogicException('Data must be a string or null.');
        }

        return $data;
    }

    /**
     * @see QueryResourcePersistenceAwareInterface::getDataForQueryResource
     *
     * @param mixed $data
     * @param Model\DataObject\Concrete|null $object
     * @param array<int|string, mixed> $params
     * @return string|null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getDataForQueryResource(mixed $data, Model\DataObject\Concrete $object = null, array $params = []): ?string
    {
        if (!is_string($data) && !is_null($data)) {
            throw new LogicException('Data must be a string or null.');
        }

        return $data;
    }

    /**
     * @param mixed $data
     * @param null|Model\DataObject\Concrete $object
     * @param array<int|string, mixed> $params
     *
     * @return string|null
     *
     * @see Data::getDataForEditmode
     *
     */
    public function getDataForEditmode(mixed $data, Model\DataObject\Concrete $object = null, array $params = []): ?string
    {
        return $this->getDataForResource($data, $object, $params);
    }

    /**
     * @see Data::getDataFromEditmode
     *
     * @param mixed $data
     * @param Model\DataObject\Concrete|null $object
     * @param array<int|string, mixed> $params
     * @return string|null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getDataFromEditmode(mixed $data, Model\DataObject\Concrete $object = null, array $params = []): ?string
    {
        if ($data === '') {
            return null;
        }

        if (!is_string($data) && !is_null($data)) {
            throw new LogicException('Data must be a string or null.');
        }

        return $data;
    }

    /**
     * Generates a pretty version preview (similar to getVersionPreview) can be either html or
     * an image URL. See the https://github.com/pimcore/object-merger bundle documentation for details
     *
     * @param string|null $data
     * @param Model\DataObject\Concrete|null $object
     * @param array<int|string, mixed> $params
     * @return array<string, string>|string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getDiffVersionPreview(?string $data, Model\DataObject\Concrete $object = null, array $params = []): array|string
    {
        if ($data) {
            $value = [];
            $data = str_replace("\r\n", '<br>', $data);
            $data = str_replace("\n", '<br>', $data);
            $data = str_replace("\r", '<br>', $data);

            $value['html'] = $data;
            $value['type'] = 'html';

            return $value;
        }

        return '';
    }

    /**
     * @param Localizedfield|Model\DataObject\Fieldcollection\Data\AbstractData|Model\DataObject\Objectbrick\Data\AbstractData|Model\DataObject\Concrete $object
     * @param array<int|string, mixed> $params
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getDataForSearchIndex(Localizedfield|Model\DataObject\Fieldcollection\Data\AbstractData|Model\DataObject\Objectbrick\Data\AbstractData|Model\DataObject\Concrete $object, array $params = []): string
    {
        if ($this->isExcludeFromSearchIndex()) {
            return '';
        }

        return parent::getDataForSearchIndex($object, $params);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $data
     * @param bool $omitMandatoryCheck
     * @param array<int|string, mixed> $params
     * @return void
     * @throws Model\Element\ValidationException
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function checkValidity(mixed $data, bool $omitMandatoryCheck = false, array $params = []): void
    {
        if (!is_string($data) && !is_null($data)) {
            throw new LogicException('Data must be a string or null.');
        }

        if (!$omitMandatoryCheck && $this->getMaxLength() !== null) {
            if ($data !== null && mb_strlen($data) > $this->getMaxLength()) {
                throw new Model\Element\ValidationException('Value in field [ ' . $this->getName() . " ] longer than max length of '" . $this->getMaxLength() . "'");
            }
        }

        parent::checkValidity($data, $omitMandatoryCheck);
    }

    /**
     * {@inheritdoc}
     */
    public function isFilterable(): bool
    {
        return true;
    }

    public function getParameterTypeDeclaration(): ?string
    {
        return '?string';
    }

    public function getReturnTypeDeclaration(): ?string
    {
        return '?string';
    }

    public function getPhpdocInputType(): ?string
    {
        return 'string|null';
    }

    public function getPhpdocReturnType(): ?string
    {
        return 'string|null';
    }

    public function getColumnType(): string
    {
        return 'longtext';
    }

    public function getQueryColumnType(): string
    {
        return $this->getColumnType();
    }

    public function getFieldType(): string
    {
        return 'markdown';
    }
}
