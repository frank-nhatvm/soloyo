<?php

class Soloyo_Campaign_AffController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function callback_facebookAction()
    {
        echo 'callback from facebook';
    }

    public function update_after_shareAction()
    {
        Mage::getModel('core/session')->unsetData('aff_player_code');

        $face_id = Mage::getModel('core/session')->getAffPlayerId();
        Mage::getModel('core/session')->unsetData('aff_player_id');

        $collection = Mage::getModel('campaign/affplayer')->getCollection()->addFieldToFilter('face_id', $face_id);
        if ($collection && count($collection)) {
            $first_item = $collection->getFirstItem();
            $id = $first_item->getId();
            $model = Mage::getModel('campaign/affplayer')->load($id);
            $model->setStatus(2);
            $model->save();
        }

    }

    public function register_affAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            $result = array();

            $face_id = $data['face_id'];
            $email = $data['email'];
            $name = $data['name'];

            if (!$this->isJoined($face_id)) {
                $model = Mage::getModel('campaign/affplayer');

                try {
                    if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
                        $model->setCreatedTime(now())
                            ->setUpdateTime(now());
                    } else {
                        $model->setUpdateTime(now());
                    }

                    $model->setFaceId($face_id);
                    $model->setEmail($email);
                    $model->setName($name);
                    $code = $this->getCode();
                    $model->setCode($code);
                    $model->setStatus(1);
                    $model->save();
                    $result['status'] = 'success';

                    Mage::getModel('core/session')->setData('aff_player_code', $code);
                    Mage::getModel('core/session')->setData('aff_player_name', $name);
                    Mage::getModel('core/session')->setData('aff_player_id', $face_id);
                    if ($email) {
                        $this->register_customer($face_id, $email, $name);
                    }

                } catch (Exception $e) {
                    $result['status'] = 'fail';
                    $result['message'] = $e->getMessage();
                }
            } else {
                $result['status'] = 'fail';
                $result['is_joined'] = '1';
                Mage::getModel('core/session')->unsetData('aff_player_code');
                $result['message'] = 'Bạn đã tham dự chương trình này. Vào trang cá nhân để nhận mã trúng thưởng.';
            }


            $this->getResponse()->setHeader('Content-type', 'application/json');
            return $this->getResponse()->setBody(json_encode($result));

        }
    }


    private function _getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    protected function register_customer($face_id, $email, $name)
    {
        $customer = Mage::getModel('customer/customer');
        $customer
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
            ->loadByEmail($email);
        if ($customer->getId()) {
            // khach hang da dang ky voi email nay, cap nhat them ten va facebook id
            $customer->setFacebookUid($face_id);
            if (!$customer->getFirstname()) {
                $customer->setFirstname($name);
            }
            $customer->save();

            //Mage::getResourceModel('customer/customer')->saveAttribute($customer, 'facebook_uid');
            $this->_getCustomerSession()->setCustomerAsLoggedIn($customer);

        } else {
            // dang ky new customer
            $randomPassword = $customer->generatePassword(8);

            $customer->setId(null)
                ->setSkipConfirmationIfEmail($email)
                ->setFirstname($name)
                ->setEmail($email)
                ->setPassword($randomPassword)
                ->setConfirmation($randomPassword)
                ->setFacebookUid($face_id);

            $customer->save();
            $customer->sendNewAccountEmail();
            $this->_getCustomerSession()->setCustomerAsLoggedIn($customer);
        }


    }

    protected function isJoined($face_id)
    {
        $collection = Mage::getModel('campaign/affplayer')->getCollection()->addFieldToFilter('face_id', $face_id);
        if ($collection && count($collection)) {
            return true;
        }

        return false;
    }

    protected function getCode()
    {
        $collection = Mage::getModel('campaign/affplayer')->getCollection();

        if ($collection && count($collection)) {

            $last_item = $collection->getLastItem();
            if ($last_item && $last_item->getCode()) {
                $code = (int) $last_item->getCode();
                $code = $code + 1;

                $str_code  = strval($code);
                $length = strlen($str_code);
                if($length == 1){
                    return '0000'.$str_code;
                }
                else if($length == 2){
                    return '000'.$str_code;
                }
                else if($length == 3){
                    return '00'.$str_code;
                }else if($length == 4){
                    return '0'.$str_code;
                }else {
                    return $str_code;
                }


            }

        }

        return '00000';
    }


}