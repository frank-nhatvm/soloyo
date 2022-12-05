<?php

class Soloyo_OnestepCheckout_IndexController extends Mage_Checkout_Controller_Action
{
    private $_current_layout = null;

    protected $_sectionUpdateFunctions = array(
        'review' => '_getReviewHtml',
        'shipping-method' => '_getShippingMethodsHtml',
        'payment-method' => '_getPaymentMethodsHtml',
    );

    public function preDispatch()
    {
        parent::preDispatch();
        $this->_preDispatchValidateCustomer();
        return $this;
    }

    protected function _ajaxRedirectResponse()
    {
        $this->getResponse()
            ->setHeader('HTTP/1.1', '403 Session Expired')
            ->setHeader('Login-Required', 'true')
            ->sendResponse();
        return $this;
    }

    protected function _expireAjax()
    {
        if (!$this->getOnestepcheckout()->getQuote()->hasItems()
            || $this->getOnestepcheckout()->getQuote()->getHasError()
            || $this->getOnestepcheckout()->getQuote()->getIsMultiShipping()
        ) {
            $this->_ajaxRedirectResponse();
            return true;
        }
        $action = $this->getRequest()->getActionName();
        if (Mage::getSingleton('checkout/session')->getCartWasUpdated(true)
            && !in_array($action, array('index', 'progress'))
        ) {
            $this->_ajaxRedirectResponse();
            return true;
        }

        return false;
    }

    protected function _getUpdatedLayout()
    {
        $this->_initLayoutMessages('checkout/session');
        if ($this->_current_layout === null) {
            $layout = $this->getLayout();
            $update = $layout->getUpdate();
            $update->load('onestepcheckout_index_updatecheckout');

            $layout->generateXml();
            $layout->generateBlocks();
            $this->_current_layout = $layout;
        }

        return $this->_current_layout;
    }

    protected function _getShippingMethodsHtml()
    {
        $layout = $this->_getUpdatedLayout();
        $block = $layout->getBlock('checkout.shipping.method');


        return $block->toHtml();
    }

    protected function _getTotalsHtml()
    {
        $layout = $this->_getUpdatedLayout();
        return $layout->getBlock('checkout.onepage.review.info.totals')->toHtml();
    }

    protected function _getPaymentMethodsHtml()
    {
        $layout = $this->_getUpdatedLayout();
        return $layout->getBlock('checkout.payment.method')->toHtml();
    }

    protected function _getCouponDiscountHtml()
    {
        $layout = $this->_getUpdatedLayout();
        return $layout->getBlock('checkout.cart.coupon')->toHtml();
    }

    protected function _getReviewHtml()
    {
        $layout = $this->_getUpdatedLayout();
        return $layout->getBlock('checkout.review')->toHtml();
    }

    public function getOnestepcheckout()
    {
        return Mage::getSingleton('onestepcheckout/type_geo');
    }

    public function indexAction()
    {
        if (!Mage::helper('onestepcheckout')->isOnestepCheckoutEnabled()) {
            Mage::getSingleton('checkout/session')->addError($this->__('The one page checkout is disabled.'));
            $this->_redirect('checkout/cart');
            return;
        }


        $quote = $this->getOnestepcheckout()->getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->_redirect('checkout/cart');
            return;
        }


        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();; // Customer id

            $customer = Mage::getModel('customer/customer');

            // Load customer
            $customer->load($customerId);
            if ($customer->getPrimaryBillingAddress()) {
                // Get current address
                $address = $customer->getPrimaryBillingAddress();

            } else {
                $list_address = $customer->getAddresses();
                if ($list_address && count($list_address)) {
                    $address = $list_address[0];

                }

            }

            if ($address) {
                $this->getOnestepcheckout()->saveShipping($address->toArray(), $quote->getShippingAddress()->getId(), false);
            }

        }


        if (!$quote->validateMinimumAmount()) {
            $error = Mage::getStoreConfig('sales/minimum_order/error_message');
            Mage::getSingleton('checkout/session')->addError($error);
            $this->_redirect('checkout/cart');
            return;
        }

        Mage::getSingleton('checkout/session')->setCartWasUpdated(false);
        Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*/*/*', array('_secure' => true)));

        $this->getOnestepcheckout()->initDefaultData()->initCheckout();
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $title = Mage::getStoreConfig('onestepcheckout/general/title');
        $this->getLayout()->getBlock('head')->setTitle($title);
        $this->renderLayout();
    }

    public function successAction()
    {
        $lastOrderId = $this->getOnestepcheckout()->getCheckout()->getLastOrderId();

        if (Mage::getStoreConfig('campaign/general/enable') && $lastOrderId) {
            $this->sendCode($lastOrderId);
        }
        $this->_redirect('checkout/onepage/success/');
        return;
    }


    protected function sendCode($order_id)
    {

        $order = Mage::getModel('sales/order')->load($order_id);

        $customer_email = $order->getCustomerEmail();
        $customer_name = $order->getCustomerFirstname();

        $code = $this->savePlayer($customer_email, $customer_name, $order_id);
        $this->sendEmail($customer_email, $customer_name, $code);
    }


    protected function savePlayer($email, $name, $order_id)
    {
        $campaign_id = '3';
        $model = Mage::getModel('campaign/affplayer');
        if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
            $model->setCreatedTime(now())
                ->setUpdateTime(now());
        } else {
            $model->setUpdateTime(now());
        }

        $model->setFaceId($order_id);
        $model->setEmail($email);
        $model->setName($name);
        $code = $this->getCode($campaign_id);
        $model->setCode($code);
        $model->setStatus(1);
        $model->setCampaignId($campaign_id);
        $model->save();

        return $code;
    }

    protected function sendEmail($email, $name, $code)
    {
        $code = $this->formatCode($code);
        try {
            $emailTemplate = Mage::getModel('core/email_template')->load('6');
            $custom_email_data = array('name' => $name, 'code' => $code);
            $vars = array('custom_email_data' => $custom_email_data);
            ($emailTemplate->getProcessedTemplate($vars));

            $emailTemplate->setSenderName('Soloyo');
            $sender_email = 'soloyo.vn@gmail.com';
            $emailTemplate->setSenderEmail($sender_email);

            if ($emailTemplate->send($email, $name, $vars)) {

                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo 'exception ' . $e->getMessage();
        }
        return false;
    }

    protected function formatCode($code)
    {
        $length = strlen($code);
        if ($length == 1) {
            return '0000' . $code;
        } else if ($length == 2) {
            return '000' . $code;
        } else if ($length == 3) {
            return '00' . $code;
        } else if ($length == 4) {
            return '0' . $code;
        } else {
            return $code;
        }
    }

    protected function getCode($campaign_id)
    {
        $collection = Mage::getModel('campaign/affplayer')->getCollection()->addFieldToFilter('campaign_id', $campaign_id);

        if ($collection && count($collection)) {

            $last_item = $collection->getLastItem();
            if ($last_item && $last_item->getCode()) {
                $code = $last_item->getCode();
                // chap nhan 2 truong hop la 1 va 2. Con lai bo qua kha nang co duoi 1000 nguoi mua van trung thuong
                $code = $code + 97;
                return ($code + 1);
            }

        }

        return '000000';
    }

    // End Tet Campaign


    public function failureAction()
    {
        $lastQuoteId = $this->getOnestepcheckout()->getCheckout()->getLastQuoteId();
        $lastOrderId = $this->getOnestepcheckout()->getCheckout()->getLastOrderId();

        if (!$lastQuoteId || !$lastOrderId) {
            $this->_redirect('checkout/cart');
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    public function getAddressAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        $addressId = $this->getRequest()->getParam('address', false);
        if ($addressId) {
            $address = $this->getOnestepcheckout()->getAddress($addressId);

            if (Mage::getSingleton('customer/session')->getCustomer()->getId() == $address->getCustomerId()) {
                $this->getResponse()->setHeader('Content-type', 'application/x-json');
                $this->getResponse()->setBody($address->toJson());
            } else {
                $this->getResponse()->setHeader('HTTP/1.1', '403 Forbidden');
            }
        }
    }

    public function updateCouponAction()
    {
        if ($this->_expireAjax() || !$this->getRequest()->isPost()) {
            return;
        }


        $quote = $this->getOnestepcheckout()->getQuote();
        $couponData = $this->getRequest()->getPost('coupon', array());
        $processCoupon = $this->getRequest()->getPost('process_coupon', false);

        $isValidCoupon = false;
        if ($couponData && $processCoupon) {
            if (!empty($couponData['remove'])) {
                $couponData['code'] = '';
            }


            $oldCouponCode = $quote->getCouponCode();
            if ($oldCouponCode != $couponData['code']) {
                try {
                    $this->getOnestepcheckout()->getQuote()->setCouponCode(
                        strlen($couponData['code']) ? $couponData['code'] : ''
                    );
                } catch (Mage_Core_Exception $e) {

                    Mage::getSingleton('checkout/session')->addError($e->getMessage());
                } catch (Exception $e) {

                    Mage::getSingleton('checkout/session')->addError($this->__('Cannot apply the coupon code.'));
                }

            }
        }
        $this->getOnestepcheckout()->getQuote()->collectTotals()->save();
        $totals = $this->getOnestepcheckout()->getQuote()->getTotals();

        if (isset($totals["discount"]) && $totals["discount"]->getValue() ) {

        }else{
            $result['message_coupon'] = 'Mã giảm giá không hợp lệ.';
        }
        $result['update_section']['totals'] = $this->_getTotalsHtml();
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function updateCheckoutAction()
    {
        if ($this->_expireAjax() || !$this->getRequest()->isPost()) {
            return;
        }
        /*********** DISCOUNT CODES **********/

        $quote = $this->getOnestepcheckout()->getQuote();
        $couponData = $this->getRequest()->getPost('coupon', array());
        $processCoupon = $this->getRequest()->getPost('process_coupon', false);

        $couponChanged = false;
        if ($couponData && $processCoupon) {
            if (!empty($couponData['remove'])) {
                $couponData['code'] = '';

            }
            $oldCouponCode = $quote->getCouponCode();
            if ($oldCouponCode != $couponData['code']) {
                try {
                    $this->getOnestepcheckout()->getQuote()->setCouponCode(
                        strlen($couponData['code']) ? $couponData['code'] : ''
                    );
                    $this->getRequest()->setPost('payment-method', true);
                    $this->getRequest()->setPost('shipping-method', true);
                    if ($couponData['code']) {
                        $couponChanged = true;
                    } else {
                        $couponChanged = true;
                        Mage::getSingleton('checkout/session')->addSuccess($this->__('Coupon code was canceled.'));
                    }
                } catch (Mage_Core_Exception $e) {
                    $couponChanged = true;
                    Mage::getSingleton('checkout/session')->addError($e->getMessage());
                } catch (Exception $e) {
                    $couponChanged = true;
                    Mage::getSingleton('checkout/session')->addError($this->__('Cannot apply the coupon code.'));
                }

            }
        }

        /***********************************/

        $bill_data = $this->getRequest()->getPost('billing', array());
        $bill_data = $this->_filterPostData($bill_data);
        $bill_addr_id = $this->getRequest()->getPost('billing_address_id', false);
        $result = array();
        $ship_updated = false;


        if ($this->_checkChangedAddress($bill_data, 'Billing', $bill_addr_id)) {

            if (isset($bill_data['email'])) {
                $bill_data['email'] = trim($bill_data['email']);
            }
            if (isset($bill_data['postcode'])) {
                $bill_data['postcode'] = trim($bill_data['postcode']);
            }


            $bill_result = $this->getOnestepcheckout()->saveBilling($bill_data, $bill_addr_id, true, true);

            if (!isset($bill_result['error'])) {

                if (isset($bill_data['use_for_shipping']) && $bill_data['use_for_shipping'] == 1 && !$this->getOnestepcheckout()->getQuote()->isVirtual()) {

                    $result['update_section']['shipping-method'] = $this->_getShippingMethodsHtml();
                    $result['duplicateBillingInfo'] = 'true';

                    $ship_updated = true;
                }
            } else {

                $result['error_messages'] = $bill_result['message'];
            }
        }

        if ($this->getRequest()->getPost('payment-method', false)) {

            $pmnt_data = $this->getRequest()->getPost('payment', array());
            $this->getOnestepcheckout()->usePayment(isset($pmnt_data['method']) ? $pmnt_data['method'] : null);
            $result['update_section']['payment-method'] = $this->_getPaymentMethodsHtml();
        }

        $ship_data = $this->getRequest()->getPost('shipping', array());
        $ship_addr_id = $this->getRequest()->getPost('shipping_address_id', false);
        $ship_method = $this->getRequest()->getPost('shipping_method', false);

        if (!$ship_updated && !$this->getOnestepcheckout()->getQuote()->isVirtual()) {

            if ($this->_checkChangedAddress($ship_data, 'Shipping', $ship_addr_id) || $ship_method) {

                $ship_result = $this->getOnestepcheckout()->saveShipping($ship_data, $ship_addr_id, false);

                if (!isset($ship_result['error'])) {
                    $result['update_section']['shipping-method'] = $this->_getShippingMethodsHtml();
                }

                $this->getOnestepcheckout()->saveShippingMethod('soloyo_ishipping');

            }
        }

        $this->getOnestepcheckout()->useShipping($ship_method);
        $this->getOnestepcheckout()->getQuote()->collectTotals()->save();


        $total = $this->getOnestepcheckout()->getQuote()->getTotals();
        $result['shipping_incltax'] = $total['shipping']->getAddress()->getShippingInclTax();
        $result['shipping_excltax'] = $total['shipping']->getAddress()->getShippingAmount();
        $result['grand_total_incl_tax'] = $total['grand_total']->getValue();
        $result['grand_total_excl_tax'] = $this->getTotalExclTaxGrand($total['grand_total']);


        $result['update_section']['totals'] = $this->_getTotalsHtml();


        /******CUSTOM CODE TO UPDATE COD TOTAL STARTS HERE ******/
        if ($this->getRequest()->getPost('payment-method', true)) {
            $paydata = $this->getRequest()->getPost('payment', array());
            if ($paydata['method'] != '') {

                if ($paydata['method'] == 'msp_cashondelivery') {
                    //Only if payment method is cashondelivery

                    // get section and redirect data
                    if (!isset($result['error'])) {

                        $this->getOnestepcheckout()->usePayment($paydata['method']);
                        $result = $this->getOnestepcheckout()->savePayment($paydata);
                        //$result['update_section']['review'] = $this->_getReviewHtml();
                    }

                    $this->getOnestepcheckout()->getQuote()->collectTotals()->save();
                } else {
                    //$result = $this->getOnestepcheckout()->savePayment($paydata);
                    if (!isset($result['error'])) {
                        $this->getOnestepcheckout()->usePayment($paydata['method']);
                        $result = $this->getOnestepcheckout()->savePayment($paydata);
                        //$result['update_section']['review'] = $this->_getReviewHtml();
                    }
                    //$this->getOnestepcheckout()->getQuote()->collectTotals()->save();
                }

            }
        }


        /**** CUSTOM CODE TO UPDATE COD TOTAL ENDS HERE ****/


        /************************************/
        $result['update_section']['review'] = $this->_getReviewHtml();
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function getTotalExclTaxGrand($total)
    {
        $excl = $total->getAddress()->getGrandTotal() - $total->getAddress()->getTaxAmount();
        $excl = max($excl, 0);
        return $excl;
    }

    public function forgotpasswordAction()
    {
        $session = Mage::getSingleton('customer/session');

        if ($this->_expireAjax() || $session->isLoggedIn()) {
            return;
        }

        $email = $this->getRequest()->getPost('email');
        $result = array('success' => false);

        if (!$email) {
            $result['error'] = Mage::helper('customer')->__('Please enter your email.');
        } else {
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                $session->setForgottenEmail($email);
                $result['error'] = Mage::helper('checkout')->__('Invalid email address.');
            } else {
                $customer = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);
                if (!$customer->getId()) {
                    $session->setForgottenEmail($email);
                    $result['error'] = Mage::helper('customer')->__('This email address was not found in our records.');
                } else {
                    try {
                        $new_pass = $customer->generatePassword();
                        $customer->changePassword($new_pass, false);
                        $customer->sendPasswordReminderEmail();
                        $result['success'] = true;
                        $result['message'] = Mage::helper('customer')->__('A new password has been sent.');
                    } catch (Exception $e) {
                        $result['error'] = $e->getMessage();
                    }
                }
            }
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function loginAction()
    {
        $session = Mage::getSingleton('customer/session');
        if ($this->_expireAjax() || $session->isLoggedIn()) {
            return;
        }

        $result = array('success' => false);

        if ($this->getRequest()->isPost()) {
            $login_data = $this->getRequest()->getPost('login');
            if (empty($login_data['username']) || empty($login_data['password'])) {
                $result['error'] = Mage::helper('customer')->__('Login and password are required.');
            } else {
                try {
                    $session->login($login_data['username'], $login_data['password']);
                    $result['success'] = true;
                    $result['redirect'] = Mage::getUrl('*/*/index');
                } catch (Mage_Core_Exception $e) {
                    switch ($e->getCode()) {
                        case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
                            $message = Mage::helper('customer')->__('Email is not confirmed. <a href="%s">Resend confirmation email.</a>', Mage::helper('customer')->getEmailConfirmationUrl($login_data['username']));
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $result['error'] = $message;
                    $session->setUsername($login_data['username']);
                }
            }
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function saveOrderAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        $result = array();


        try {
            $bill_data = $this->_filterPostData($this->getRequest()->getPost('billing', array()));

            $bill_data['use_for_shipping'] = 1;

            // shipping_method soloyo_ishipping_soloyo_ishipping
            //payment[method] cashondelivery

            $result = $this->getOnestepcheckout()->saveBilling($bill_data, $this->getRequest()->getPost('billing_address_id', false), true, true);
            if ($result) {
                $result['error_messages'] = $result['message'];
                $result['error'] = true;
                $result['success'] = false;
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
                return;
            }


            $result = $this->_saveOrderPurchase();

            if ($result && !isset($result['redirect'])) {
                $result['error_messages'] = $result['error'];
            }

            if (!isset($result['error'])) {
                Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method', array('request' => $this->getRequest(), 'quote' => $this->getOnestepcheckout()->getQuote()));
//                $this->_subscribeNews();
            }

            Mage::getSingleton('customer/session')->setOrderCustomerComment($this->getRequest()->getPost('order-comment'));

            if (!isset($result['redirect']) && !isset($result['error'])) {
                $pmnt_data = $this->getRequest()->getPost('payment', false);
                if ($pmnt_data)
                    $this->getOnestepcheckout()->getQuote()->getPayment()->importData($pmnt_data);

                $this->getOnestepcheckout()->saveOrder();
                $redirectUrl = $this->getOnestepcheckout()->getCheckout()->getRedirectUrl();

                $result['success'] = true;
                $result['error'] = false;
                $result['order_created'] = true;
            }
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnestepcheckout()->getQuote(), $e->getMessage());

            $result['error_messages'] = $e->getMessage();
            $result['error'] = true;
            $result['success'] = false;

            $goto_section = $this->getOnestepcheckout()->getCheckout()->getGotoSection();
            if ($goto_section) {
                $this->getOnestepcheckout()->getCheckout()->setGotoSection(null);
                $result['goto_section'] = $goto_section;
            }

            $update_section = $this->getOnestepcheckout()->getCheckout()->getUpdateSection();
            if ($update_section) {
                if (isset($this->_sectionUpdateFunctions[$update_section])) {
                    $layout = $this->_getUpdatedLayout();

                    $updateSectionFunction = $this->_sectionUpdateFunctions[$update_section];
                    $result['update_section'] = array(
                        'name' => $update_section,
                        'html' => $this->$updateSectionFunction()
                    );
                }
                $this->getOnestepcheckout()->getCheckout()->setUpdateSection(null);
            }

            $this->getOnestepcheckout()->getQuote()->save();
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::helper('checkout')->sendPaymentFailedEmail($this->getOnestepcheckout()->getQuote(), $e->getMessage());
            $result['error_messages'] = Mage::helper('checkout')->__('There was an error processing your order. Please contact support or try again later.');
            $result['error'] = true;
            $result['success'] = false;

            $this->getOnestepcheckout()->getQuote()->save();
        }

        if (isset($redirectUrl)) {
            $result['redirect'] = $redirectUrl;
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

        //}
    }

    protected function _saveOrderPurchase()
    {
        $result = array();

        try {
            $pmnt_data = $this->getRequest()->getPost('payment', array());
            $result = $this->getOnestepcheckout()->savePayment($pmnt_data);
            $redirectUrl = $this->getOnestepcheckout()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }
        } catch (Mage_Payment_Exception $e) {
            if ($e->getFields()) {
                $result['fields'] = $e->getFields();
            }
            $result['error'] = $e->getMessage();
        } catch (Mage_Core_Exception $e) {
            $result['error'] = $e->getMessage();
        } catch (Exception $e) {
            Mage::logException($e);
            $result['error'] = Mage::helper('checkout')->__('Unable to set Payment Method.');
        }
        return $result;
    }

    protected function _subscribeNews()
    {
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('newsletter')) {
            $customerSession = Mage::getSingleton('customer/session');

            if ($customerSession->isLoggedIn())
                $email = $customerSession->getCustomer()->getEmail();
            else {
                $bill_data = $this->getRequest()->getPost('billing');
                $email = $bill_data['email'];
            }

            try {
                if (!$customerSession->isLoggedIn() && Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1)
                    Mage::throwException(Mage::helper('newsletter')->__('Sorry, subscription for guests is not allowed. Please <a href="%s">register</a>.', Mage::getUrl('customer/account/create/')));

                $ownerId = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email)->getId();

                if ($ownerId !== null && $ownerId != $customerSession->getId())
                    Mage::throwException(Mage::helper('newsletter')->__('Sorry, you are trying to subscribe email assigned to another user.'));

                $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
            } catch (Mage_Core_Exception $e) {
            } catch (Exception $e) {
            }
        }
    }

    protected function _filterPostData($data)
    {
        $data = $this->_filterDates($data, array('dob'));
        return $data;
    }

    protected function _checkChangedAddress($data, $addr_type = 'Billing', $addr_id = false)
    {
        $method = "get{$addr_type}Address";
        $address = $this->getOnestepcheckout()->getQuote()->{$method}();

        if (!$addr_id) {
            if (($address->getRegionId() != $data['region_id']) || ($address->getPostcode() != $data['postcode']) || ($address->getCountryId() != $data['country_id']))
                return true;
            else
                return false;
        } else {
            if ($addr_id != $address->getCustomerAddressId())
                return true;
            else
                return false;
        }
    }

}
