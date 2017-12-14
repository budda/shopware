<?php declare(strict_types=1);

namespace Shopware\Shipping\Event\ShippingMethodTranslation;

use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Event\NestedEvent;
use Shopware\Framework\Event\NestedEventCollection;
use Shopware\Shipping\Collection\ShippingMethodTranslationDetailCollection;
use Shopware\Shipping\Event\ShippingMethod\ShippingMethodBasicLoadedEvent;
use Shopware\Shop\Event\Shop\ShopBasicLoadedEvent;

class ShippingMethodTranslationDetailLoadedEvent extends NestedEvent
{
    const NAME = 'shipping_method_translation.detail.loaded';

    /**
     * @var TranslationContext
     */
    protected $context;

    /**
     * @var ShippingMethodTranslationDetailCollection
     */
    protected $shippingMethodTranslations;

    public function __construct(ShippingMethodTranslationDetailCollection $shippingMethodTranslations, TranslationContext $context)
    {
        $this->context = $context;
        $this->shippingMethodTranslations = $shippingMethodTranslations;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): TranslationContext
    {
        return $this->context;
    }

    public function getShippingMethodTranslations(): ShippingMethodTranslationDetailCollection
    {
        return $this->shippingMethodTranslations;
    }

    public function getEvents(): ?NestedEventCollection
    {
        $events = [];
        if ($this->shippingMethodTranslations->getShippingMethods()->count() > 0) {
            $events[] = new ShippingMethodBasicLoadedEvent($this->shippingMethodTranslations->getShippingMethods(), $this->context);
        }
        if ($this->shippingMethodTranslations->getLanguages()->count() > 0) {
            $events[] = new ShopBasicLoadedEvent($this->shippingMethodTranslations->getLanguages(), $this->context);
        }

        return new NestedEventCollection($events);
    }
}