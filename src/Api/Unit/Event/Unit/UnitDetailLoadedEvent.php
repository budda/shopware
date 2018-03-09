<?php declare(strict_types=1);

namespace Shopware\Api\Unit\Event\Unit;

use Shopware\Api\Unit\Collection\UnitDetailCollection;
use Shopware\Api\Unit\Event\UnitTranslation\UnitTranslationBasicLoadedEvent;
use Shopware\Context\Struct\ShopContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;

class UnitDetailLoadedEvent extends NestedEvent
{
    public const NAME = 'unit.detail.loaded';

    /**
     * @var ShopContext
     */
    protected $context;

    /**
     * @var UnitDetailCollection
     */
    protected $units;

    public function __construct(UnitDetailCollection $units, ShopContext $context)
    {
        $this->context = $context;
        $this->units = $units;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): ShopContext
    {
        return $this->context;
    }

    public function getUnits(): UnitDetailCollection
    {
        return $this->units;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [];
        if ($this->units->getTranslations()->count() > 0) {
            $events[] = new UnitTranslationBasicLoadedEvent($this->units->getTranslations(), $this->context);
        }

        return new NestedEventCollection($events);
    }
}