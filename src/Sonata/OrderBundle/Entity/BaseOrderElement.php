<?php

namespace Sonata\OrderBundle\Entity;

use Sonata\Component\Order\OrderInterface;
use Sonata\Component\Order\OrderElementInterface;
use Sonata\Component\Product\ProductInterface;

use Application\Sonata\OrderBundle\Entity\Order;
use Application\Sonata\ProductBundle\Entity\Delivery;

/**
 * Sonata\OrderBundle\Entity\BaseOrderElement
 */
abstract class BaseOrderElement implements OrderElementInterface
{
    /**
     * @var integer $order
     */
    protected $order;

    /**
     * @var integer $quantity
     */
    protected $quantity;

    /**
     * @var decimal $price
     */
    protected $price;

    /**
     * @var decimal $vat
     */
    protected $vat;

    /**
     * @var string $designation
     */
    protected $designation;

    /**
     * @var text $description
     */
    protected $description;

    /**
     * @var array $options
     */
    protected $options;

    /**
     * @var array $options
     */
    protected $rawProduct;

    /**
     * @var integer $productId
     */
    protected $productId;

    /**
     * @var integer $status
     */
    protected $status;

    /**
     * @var integer $delivery_status
     */
    protected $deliveryStatus;

    /**
     * @var datetime $validated_at
     */
    protected $validatedAt;

    /**
     * @var Sonata\ProductBundle\Entity\BaseProduct
     */
    protected $product;

    /**
     * @var string $product_type
     */
    protected $productType;

    protected $createdAt;

    protected $updatedAt;

    public function __construct()
    {
        $this->rawProduct = array();
        $this->options = array();
    }

    /**
     * Set order
     *
     * @param Order $order
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     * Get order
     *
     * @return Order $order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get quantity
     *
     * @return integer $quantity
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param decimal $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return decimal $price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set vat
     *
     * @param decimal $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * Get vat
     *
     * @return decimal $vat
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * Set designation
     *
     * @param string $designation
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;
    }

    /**
     * Get designation
     *
     * @return string $designation
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    /**
     * Get productId
     *
     * @return integer $productId
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set status
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return integer $status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set delivery_status
     *
     * @param integer $deliveryStatus
     */
    public function setDeliveryStatus($deliveryStatus)
    {
        $this->deliveryStatus = $deliveryStatus;
    }

    /**
     * Get delivery_status
     *
     * @return integer $deliveryStatus
     */
    public function getDeliveryStatus()
    {
        return $this->deliveryStatus;
    }

    /**
     * Set validated_at
     *
     * @param datetime $validatedAt
     */
    public function setValidatedAt(\DateTime $validatedAt = null)
    {
        $this->validatedAt = $validatedAt;
    }

    /**
     * Get validated_at
     *
     * @return datetime $validatedAt
     */
    public function getValidatedAt()
    {
        return $this->validatedAt;
    }

    /**
     * Add product
     *
     * @param Sonata\ProductBundle\Entity\BaseProduct $product
     */
    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;
    }

    /**
     * Get product
     *
     * @return Doctrine\Common\Collections\Collection $product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set product_type
     *
     * @param string $productType
     */
    public function setProductType($productType)
    {
        $this->productType = $productType;
    }

    /**
     * Get product_type
     *
     * @return string $productType
     */
    public function getProductType()
    {
        return $this->productType;
    }

    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * @param array $rawProduct
     */
    public function setRawProduct($rawProduct)
    {
        $this->rawProduct = $rawProduct;
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function getRawProductValue($name, $default = null)
    {
        $values = $this->getRawProduct();

        if (array_key_exists($name, $values)) {
            return $values[$name];
        }

        return $default;
    }

    /**
     * @return array
     */
    public function getRawProduct()
    {
        return $this->rawProduct;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getDesignation();
    }

    public static function getStatusList()
    {
        return Order::getStatusList();
    }

    public static function getDeliveryStatusList()
    {
        return Delivery::getStatusList();
    }
}