<?php
/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Component\Delivery;

use Sonata\Component\Basket\BasketInterface;
use Sonata\Component\Customer\AddressInterface;
use Sonata\Component\Delivery\Pool as DeliveryPool;
use Sonata\Component\Product\Pool as ProductPool;
use Psr\Log\LoggerInterface;

/**
 * The selector selects available delivery methods depends on the provided basket
 *
 */
class Selector implements ServiceDeliverySelectorInterface
{
    /**
     * @var DeliveryPool
     */
    protected $deliveryPool;

    /**
     * @var ProductPool
     */
    protected $productPool;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param \Sonata\Component\Delivery\Pool $deliveryPool
     * @param \Sonata\Component\Product\Pool  $productPool
     */
    public function __construct(DeliveryPool $deliveryPool, ProductPool $productPool)
    {
        $this->productPool = $productPool;
        $this->deliveryPool = $deliveryPool;
    }

    /**
     * @return \Sonata\Component\Delivery\Pool
     */
    public function getDeliveryPool()
    {
        return $this->deliveryPool;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param string $message
     */
    protected function log($message)
    {
        if ($this->logger) {
            $this->logger->info($message);
        }
    }

    /**
     * @return \Sonata\Component\Product\Pool
     */
    public function getProductPool()
    {
        return $this->productPool;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableMethods(BasketInterface $basket = null, AddressInterface $deliveryAddress = null)
    {
        $instances = array();

        if (!$basket) {
            return $instances;
        }

        // no address defined !
        if (!$deliveryAddress) {
            return $instances;
        }

        // STEP 1 : We get product's delivery methods
        /** @var $basketElement \Sonata\Component\Basket\BasketElementInterface */
        foreach ($basket->getBasketElements() as $basketElement) {

            $product = $basketElement->getProduct();

            if (!$product) {
                $this->log(sprintf('[sonata::getAvailableDeliveryMethods] product.id: %d does not exist', $basketElement->getProductId()));

                return $instances;
            }

            /** @var $productDelivery \Sonata\Component\Product\DeliveryInterface */
            foreach ($product->getDeliveries() as $productDelivery) {

                // delivery method already selected
                if (array_key_exists($productDelivery->getCode(), $instances)) {
                    $this->log(sprintf('[sonata::getAvailableDeliveryMethods] product.id: %d - code : %s already selected', $basketElement->getProductId(), $productDelivery->getCode()));

                    continue;
                }

                $deliveryMethod = $this->getDeliveryPool()->getMethod($productDelivery->getCode());

                if (!$deliveryMethod) {
                    $this->log(sprintf('[sonata::getAvailableDeliveryMethods] product.id: %d - code: %s does not exist', $basketElement->getProductId(), $productDelivery->getCode()));

                    continue;
                }

                // product delivery not enable
                if (!$deliveryMethod->getEnabled()) {
                    $this->log(sprintf('[sonata::getAvailableDeliveryMethods] product.id: %d - code : %s is not enabled', $basketElement->getProductId(), $productDelivery->getCode()));

                    continue;
                }

                // the product is not deliverable at the $shippingAddress
                if ($deliveryAddress->getCountryCode() != $productDelivery->getCountryCode()) {
                    $this->log(sprintf('[sonata::getAvailableDeliveryMethods] product.id: %d - code : %s the country code does not match (%s != %s)', $basketElement->getProductId(), $productDelivery->getCode(), $deliveryAddress->getCountryCode(), $productDelivery->getCountryCode()));

                    continue;
                }

                $this->log(sprintf('[sonata::getAvailableDeliveryMethods] product.id: %d - code : %s selected', $basketElement->getProductId(), $productDelivery->getCode()));

                $instances[$deliveryMethod->getCode()] = $deliveryMethod;
            }
        }

        // STEP 2 : We select the delivery methods with the highest priority
        $instances = array_values($instances);
        usort($instances, array('Sonata\Component\Delivery\Selector', 'sort'));

        return $instances;
    }

    /**
     * @param ServiceDeliveryInterface $a
     * @param ServiceDeliveryInterface $b
     *
     * @return int
     */
    public static function sort(ServiceDeliveryInterface $a, ServiceDeliveryInterface $b)
    {
        if ($a->getPriority() === $b->getPriority()) {
            return 0;
        }

        return $a->getPriority() > $b->getPriority() ? -1 : 1;
    }
}
