<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Payment
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Payment Observer
 */
namespace Magento\Payment\Model;

class Observer
{
    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_salesOrderConfig;

    /**
     * @var Config
     */
    protected $_paymentConfig;

    /**
     * @var \Magento\Core\Model\Resource\Config
     */
    protected $_resourceConfig;

    /**
     * Construct
     *
     * @param \Magento\Sales\Model\Order\Config $salesOrderConfig
     * @param \Magento\Payment\Model\Config $paymentConfig
     * @param \Magento\Core\Model\Resource\Config $resourceConfig
     */
    public function __construct(
        \Magento\Sales\Model\Order\Config $salesOrderConfig,
        \Magento\Payment\Model\Config $paymentConfig,
        \Magento\Core\Model\Resource\Config $resourceConfig
    ) {
        $this->_salesOrderConfig = $salesOrderConfig;
        $this->_paymentConfig = $paymentConfig;
        $this->_resourceConfig = $resourceConfig;
    }
    /**
     * Set forced canCreditmemo flag
     *
     * @param \Magento\Event\Observer $observer
     * @return \Magento\Payment\Model\Observer
     */
    public function salesOrderBeforeSave($observer)
    {
        $order = $observer->getEvent()->getOrder();

        if ($order->getPayment()->getMethodInstance()->getCode() != 'free') {
            return $this;
        }

        if ($order->canUnhold()) {
            return $this;
        }

        if ($order->isCanceled() || $order->getState() === \Magento\Sales\Model\Order::STATE_CLOSED) {
            return $this;
        }
        /**
         * Allow forced creditmemo just in case if it wasn't defined before
         */
        if (!$order->hasForcedCanCreditmemo()) {
            $order->setForcedCanCreditmemo(true);
        }
        return $this;
    }

    /**
     * Sets current instructions for bank transfer account
     *
     * @param \Magento\Event\Observer $observer
     * @return void
     */
    public function beforeOrderPaymentSave(\Magento\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order\Payment $payment */
        $payment = $observer->getEvent()->getPayment();
        if($payment->getMethod() === \Magento\Payment\Model\Method\Banktransfer::PAYMENT_METHOD_BANKTRANSFER_CODE) {
            $payment->setAdditionalInformation('instructions',
                $payment->getMethodInstance()->getInstructions());
        }
    }

    /**
     * @param \Magento\Event\Observer $observer
     */
    public function updateOrderStatusForPaymentMethods(\Magento\Event\Observer $observer)
    {
        if ($observer->getEvent()->getState() !== \Magento\Sales\Model\Order::STATE_NEW) {
            return;
        }
        $status = $observer->getEvent()->getStatus();
        $defaultStatus = $this->_salesOrderConfig->getStateDefaultStatus(\Magento\Sales\Model\Order::STATE_NEW);
        $methods = $this->_paymentConfig->getActiveMethods();
        foreach ($methods as $method) {
            if ($method->getConfigData('order_status') == $status) {
                $this->_resourceConfig->saveConfig(
                    'payment/' . $method->getCode() . '/order_status', $defaultStatus, 'default', 0
                );
            }
        }
    }
}
