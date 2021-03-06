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
 * @package     Magento_Email
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Email information model
 * Email message may contain addresses in any of these three fields:
 *  -To:  Primary recipients
 *  -Cc:  Carbon copy to secondary recipients and other interested parties
 *  -Bcc: Blind carbon copy to tertiary recipients who receive the message
 *        without anyone else (including the To, Cc, and Bcc recipients) seeing who the tertiary recipients are
 *
 * @category    Magento
 * @package     Magento_Email
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Magento\Email\Model;

class Info extends \Magento\Object
{
    /**
     * Name list of "Bcc" recipients
     *
     * @var string[]
     */
    protected $_bccNames = array();

    /**
     * Email list of "Bcc" recipients
     *
     * @var string[]
     */
    protected $_bccEmails = array();

    /**
     * Name list of "To" recipients
     *
     * @var string[]
     */
    protected $_toNames = array();

    /**
     * Email list of "To" recipients
     *
     * @var string[]
     */
    protected $_toEmails = array();


    /**
     * Add new "Bcc" recipient to current email
     *
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function addBcc($email, $name = null)
    {
        $this->_bccNames[] = $name;
        $this->_bccEmails[] = $email;
        return $this;
    }

    /**
     * Add new "To" recipient to current email
     *
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function addTo($email, $name = null)
    {
        $this->_toNames[] = $name;
        $this->_toEmails[] = $email;
        return $this;
    }

    /**
     * Get the name list of "Bcc" recipients
     *
     * @return string[]
     */
    public function getBccNames()
    {
        return $this->_bccNames;
    }

    /**
     * Get the email list of "Bcc" recipients
     *
     * @return string[]
     */
    public function getBccEmails()
    {
        return $this->_bccEmails;
    }

    /**
     * Get the name list of "To" recipients
     *
     * @return string[]
     */
    public function getToNames()
    {
        return $this->_toNames;
    }

    /**
     * Get the email list of "To" recipients
     *
     * @return string[]
     */
    public function getToEmails()
    {
        return $this->_toEmails;
    }
}
