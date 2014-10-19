<?php


namespace Acme\DemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * YeeplyOrder
 *
 * @ORM\Table("yp_order")
 * @ORM\Entity(repositoryClass="Acme\DemoBundle\Entity\Repository\OrderRepository")
 */
class AcmeOrder
{
    const STATUS_NOT_PAYED = 'not_payed';
    const STATUS_PAYED = 'payed';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=120)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="Yeeply\OrderBundle\Entity\OrderItem", mappedBy="order", cascade={"all"}, orphanRemoval=true)
     */
//    private $items;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=120)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="billing_addres", type="string", length=255, nullable=true)
     */
    private $billingAddres;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=10)
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="paymentStatus", type="string", length=120)
     */
    private $paymentStatus;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="coupon", type="object")
     */

    /**
     * @var Coupon $coupon
     *
     * @ORM\ManyToOne(targetEntity="Yeeply\CouponBundle\Entity\Coupon", inversedBy="orders", cascade={"all"} )
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
//    private $coupon;

    /**
     * var ArrayCollection $adjustments
     * @ORM\OneToMany(targetEntity="Yeeply\OrderBundle\Entity\Adjustment", mappedBy="order", cascade={"all"}, orphanRemoval=true)
     */
//    private $adjustments;

    /**
     * @var Cart $cart
     *
     * @ORM\OneToOne(targetEntity="Yeeply\CartBundle\Entity\Cart")
     */
//    protected $cart;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_type", type="string", length=120)
     */
    protected $paymentType;

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="Yeeply\UserBundle\Entity\User", inversedBy="orders", cascade={"all"} )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
//    protected $user;

    /**
     * @var GuestUser $guestUser
     *
     * @ORM\ManyToOne(targetEntity="Yeeply\UserBundle\Entity\GuestUser", inversedBy="orders", cascade={"all"} )
     * @ORM\JoinColumn(name="guest_user_id", referencedColumnName="id")
     */
//    protected $guestUser;

    /**
     * @var bool
     * @ORM\Column(name="admin_validation", type="boolean")
     */
    private $adminValidation;

    /**
     * @var string $confirmationToken
     *
     * @ORM\Column(name="subscription_token", type="string", length=255, nullable=true)
     */
    private $subscriptionToken;

    /**
     * @var string $confirmationToken
     *
     * @ORM\Column(name="transaction_token", type="string", length=255, nullable=true)
     */
    private $transactionToken;

    public function __construct()
    {
        $this->type = '';
        $this->adminValidation = false;
        $this->createdAt = new \DateTime();
        $this->items = new ArrayCollection();
        $this->adjustments = new ArrayCollection();
        $this->status = self::STATUS_NOT_PAYED;
        $this->paymentStatus = self::STATUS_NOT_PAYED;
//        $this->currency = CurrencyTypes::EURO;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \Yeeply\UserBundle\Entity\User $user
     */
    public function setUser(User $user)
    {
        $user->addOrder($this);
        $this->user = $user;
    }

    /**
     * @return \Yeeply\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return \Yeeply\UserBundle\Entity\UserBasicInfo
     */
    public function getUserBasicInfo()
    {
        $user = $this->getUser();
        if ($user == null) {
            $user = $this->getGuestUser();
        }
        return $user;
    }

    /**
     * @param \Yeeply\UserBundle\Entity\GuestUser $guestUser
     */
    public function setGuestUser(GuestUser $guestUser = null)
    {
        if ($guestUser instanceof GuestUser) {
            $guestUser->addOrder($this);
        }
        $this->guestUser = $guestUser;
    }

    /**
     * @return \Yeeply\UserBundle\Entity\GuestUser
     */
    public function getGuestUser()
    {
        return $this->guestUser;
    }

    /**
     * Set createdAt
     *
     * @param  \DateTime $createdAt
     * @return YeeplyOrder
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ArrayCollection $items
     */
    public function setItems(ArrayCollection $items)
    {
        $this->items = $items;
    }

    /**
     * @param  string $productType
     * @return array
     */
    public function getItemsByProductType($productType)
    {
        $items = array();

        foreach ($this->getItems() as $item) {
            if ($item->hasProduct($productType)) {
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * @param string $paymentType
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param string $subscriptionToken
     */
    public function setSubscriptionToken($subscriptionToken)
    {
        $this->subscriptionToken = $subscriptionToken;
    }

    /**
     * @param string $transactionToken
     */
    public function setTransactionToken($transactionToken)
    {
        $this->transactionToken = $transactionToken;
    }

    /**
     * @return string
     */
    public function getTransactionToken()
    {
        return $this->transactionToken;
    }

    /**
     * @return string
     */
    public function getSubscriptionToken()
    {
        return $this->subscriptionToken;
    }

    /**
     * @return AbstractProduct
     */
    public function getFirstProduct(){
        return $this->getItems()[0]->getProduct();
    }

    /**
     * @return int
     */
    public function countItems()
    {
        return $this->items->count();
    }

    /**
     * @param  OrderItem $item
     * @return $this
     */
    public function addItem(OrderItem $item)
    {
        if ($this->hasItem($item)) {
            return;
        }

        $item->setOrder($this);
        $this->items->add($item);
    }

    /**
     * {@inheritdoc}
     */
    public function removeItem(OrderItem $item)
    {
        if ($this->hasItem($item)) {
            $item->setOrder(null);
            $this->items->removeElement($item);
        }

        return $this;
    }

    /**
     * @param  OrderItem $item
     * @return bool
     */
    public function hasItem(OrderItem $item)
    {
        return $this->items->contains($item);
    }

    /**
     * @param boolean $adminValidation
     */
    public function setAdminValidation($adminValidation)
    {
        $this->adminValidation = $adminValidation;
    }

    /**
     * @return boolean
     */
    public function getAdminValidation()
    {
        return $this->adminValidation;
    }

    /**
     * Set status
     *
     * @param  string $status
     * @return YeeplyOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set billingAddres
     *
     * @param string $billingAddres
     */
    public function setBillingAddres($billingAddres)
    {
        $this->billingAddres = $billingAddres;

    }

    /**
     * Get billingAddres
     *
     * @return string
     */
    public function getBillingAddres()
    {
        return $this->billingAddres;
    }

    /**
     * Set currency
     *
     * @param  string $currency
     * @return YeeplyOrder
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set paymentStatus
     *
     * @param  string $paymentStatus
     * @return YeeplyOrder
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * Get paymentStatus
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * Set coupon
     *
     * @param  Coupon $coupon
     * @return YeeplyOrder
     */
    public function setCoupon(Coupon $coupon)
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Get coupon
     *
     * @return Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * @param \Yeeply\CartBundle\Entity\Cart $cart
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    /**
     * @return \Yeeply\CartBundle\Entity\Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param mixed $adjustments
     */
    public function setAdjustments($adjustments)
    {
        $this->adjustments = $adjustments;
    }

    /**
     * @return ArrayCollection
     */
    public function getAdjustments()
    {
        return $this->adjustments;
    }

    /**
     * sets Paymill transaction_id
     * @param string $confirmationToken
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * @return int
     */
    public function countAdjustments()
    {
        return $this->adjustments->count();
    }

    /**
     * @param Adjustment $adjustment
     */
    public function addAdjustments(Adjustment $adjustment)
    {
        if ($this->hasAdjustment($adjustment)) {
            return;
        }

        $adjustment->setOrder($this);
        $this->adjustments->add($adjustment);
        $this->applyAdjusments();
    }

    /**
     * @param Adjustment $adjustment
     */
    public function removeAdjustment(Adjustment $adjustment)
    {
        if ($this->hasAdjustment($adjustment)) {
            $adjustment->setOrder(null);
            $this->adjustments->removeElement($adjustment);
        }
    }

    /**
     * @param  Adjustment $item
     * @return bool
     */
    public function hasAdjustment(Adjustment $item)
    {
        return $this->adjustments->contains($item);
    }

    /**
     * @return int
     */
    public function getBaseAmount()
    {
        $baseAmount = 0;
        foreach ($this->getItems() as $item) {
            $baseAmount += $item->getAmount(CurrencyTypes::EURO);
        }

        return $baseAmount;
    }

    /**
     * @return float
     */
    public function getTotalAmountBeforePaymentCommission()
    {
        $amount = $this->getBaseAmount();
        $this->applyAdjusments();

        foreach ($this->getAdjustments() as $adjustment) {

            if ($adjustment->getType() != AdjustmentPaymentModeBuilder::TYPE) {
                $amount += $adjustment->getAmount();
            }
        }

        return floatval($amount);
    }


    /**
     * @return float
     */
    public function getTotalAmount()
    {
        $amount = $this->getBaseAmount();
        $this->applyAdjusments();

        foreach ($this->getAdjustments() as $adjustment) {
            $amount += $adjustment->getAmount();
        }

        return floatval($amount);
    }

    /**
     *
     */
    public function applyAdjusments()
    {
        $baseAmount = $this->getBaseAmount();

        foreach ($this->getAdjustments() as $adjustment) {
            $adjustment->applyAdjustment($baseAmount);
            $baseAmount += $adjustment->getAmount();
        }
    }

    /**
     * @param  string $adjustmentType
     * @return Adjustment|null
     */
    public function getAdjustmentByType($adjustmentType = '')
    {
        $adjustment = null;

        foreach ($this->getAdjustments() as $adjustmentNode) {
            if ($adjustmentNode->getType() == $adjustmentType) {
                $adjustment = $adjustmentNode;
                break;
            }
        }

        return $adjustment;
    }

    /**
     * @return float|int
     */
    public function getTaxAmount()
    {
        $amount = 0;
        $adjustment = $this->getAdjustmentByType(AdjustmentTaxBuilder::TYPE);

        if ($adjustment instanceof Adjustment) {
            $amount = $adjustment->getAmount();
        }

        return $amount;
    }

    /**
     * @return bool
     */
    public function hasTaxAdjustment()
    {
        $adjustment = $this->getAdjustmentByType(AdjustmentTaxBuilder::TYPE);

        return $adjustment instanceof Adjustment;
    }

    /**
     * @return int
     */
    public function getDiscountAmount()
    {
        $amount = 0;

        $adjustment = $this->getAdjustmentByType(AdjustmentDiscountBuilder::TYPE);

        if ($adjustment instanceof Adjustment) {
            $amount = $adjustment->getAmount();
        }

        return $amount;
    }

    /**
     * @return float|int
     */
    public function getTransactionAmount()
    {
        $amount = 0;
        $adjustment = $this->getAdjustmentByType(AdjustmentPaymentModeBuilder::TYPE);

        if ($adjustment instanceof Adjustment) {
            $amount = $adjustment->getAmount();
        }

        return $amount;
    }

    /**
     * @return int
     */
    public function getCommissionAmount()
    {

        $amount = 0;

        $adjustment = $this->getAdjustmentByType(AdjustmentEscrowCommissionBuilder::TYPE);

        if ($adjustment instanceof Adjustment) {
            $amount = $adjustment->getAmount();
        }

        return $amount;
    }

    /**
     * @return int
     */
    public function getCommissionAfterTaxAmount()
    {

        $afterTaxAmount = $this->getCommissionAmount();
        $adjustment = $this->getAdjustmentByType(AdjustmentTaxBuilder::TYPE);

        if ($adjustment instanceof Adjustment) {
            $afterTaxAmount = $afterTaxAmount * $adjustment->getPercentage();
        }


        return $afterTaxAmount;
    }

    /**
     *
     */
    public function setAsPayed()
    {
        $this->setStatus(self::STATUS_PAYED);
        $this->setPaymentStatus(self::STATUS_PAYED);
    }

    /**
     *
     */
    public function setAsNotPayed()
    {
        $this->setStatus(self::STATUS_NOT_PAYED);
        $this->setPaymentStatus(self::STATUS_NOT_PAYED);
    }

    /**
     * @return bool
     */
    public function isPayed()
    {
        return $this->getStatus() == self::STATUS_PAYED;
    }

}
