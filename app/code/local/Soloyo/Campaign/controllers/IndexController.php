<?php

class Soloyo_Campaign_IndexController extends Mage_Core_Controller_Front_Action
{

    public function sendNotifyToDesignerAction(){
//        $collection = Mage::getModel('productupload/designer')
//            ->getCollection()->addFieldToFilter('status','1');
//        foreach ($collection as $designer){
//            $user_id = $designer->getUserId();
//            $user = Mage::getModel('customer/customer')->load($user_id);
//
//            $designer_email = trim($user->getEmail());
//            $designer_name = $user->getFirstname();
//            if($this->sendEmail($designer_email, $designer_name)){
//                echo '<br/>';
//                echo ' Sent to '.$designer_name.' with email '.$designer_email;
//            }else{
//                echo '<br/>';
//                echo ' Send fail to '.$designer_name.' with email '.$designer_email;
//            }
//        }
    }

    protected function sendEmail($designer_email, $designer_name){
        try {
            $emailTemplate = Mage::getModel('core/email_template')->load('5');
            $vars = array('custom_designer_data' => '');
            ($emailTemplate->getProcessedTemplate($vars));

            $emailTemplate->setSenderName('Soloyo');
            $sender_email = 'soloyo.vn@gmail.com';
            $emailTemplate->setSenderEmail($sender_email);



            if ($emailTemplate->send($designer_email, $designer_name, $vars)) {

                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo 'exception '.$e->getMessage();
        }
        return false;
    }

    /**
     * index action
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function tetAction(){
        $this->loadLayout();
        $this->renderLayout();
    }


    public function update_after_shareAction()
    {
        Mage::getModel('core/session')->unsetData('aff_player_code');

        $face_id = Mage::getModel('core/session')->getAffPlayerId();
        Mage::getModel('core/session')->unsetData('aff_player_id');

        $campaign_id = Mage::getModel('core/session')->getCampaignId();
        Mage::getModel('core/session')->unsetData('campaign_id');

        $collection = Mage::getModel('campaign/affplayer')->getCollection()->addFieldToFilter('face_id', $face_id)->addFieldToFilter('campaign_id',$campaign_id);
        if ($collection && count($collection)) {
            $first_item = $collection->getFirstItem();
            $id = $first_item->getId();
            $model = Mage::getModel('campaign/affplayer')->load($id);
            $model->setStatus(2);
            $model->save();
        }

        $campaign = Mage::getModel('campaign/campaign')->load($campaign_id);
        if($campaign && $campaign->getId()) {
            $num_player = $campaign->getNumPlayer();
            $campaign->setNumPlayer($num_player + 1);
            $campaign->save();
        }

    }

    public function register_campaignAction()
    {
        if ($data = $this->getRequest()->getPost()) {

            $result = array();

            $face_id = $data['face_id'];
            $email = $data['email'];
            $name = $data['name'];
            $campaign_id = $data['campaign_id'];

            $campaign = Mage::getModel('campaign/campaign')->load($campaign_id);
           if($campaign && $campaign->getId()){
               $status = $campaign->getStatus();

               if($status == 2){
                   $result['status'] = 'fail';
                   $result['message'] = 'Chương trình đã kết thúc. Hãy vào fanpage để tìm hiểu các chương trình quà tặng tiếp theo';
                   $this->getResponse()->setHeader('Content-type', 'application/json');
                   return $this->getResponse()->setBody(json_encode($result));
               }
           }

            if (!$this->isJoined($face_id,$campaign_id)) {
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
                    $code = $this->getCode($campaign_id);
                    $model->setCode($code);
                    $model->setStatus(1);
                    $model->setCampaignId($campaign_id);
                    $model->save();
                    $result['status'] = 'success';

                    Mage::getModel('core/session')->setData('aff_player_code', $code);
                    Mage::getModel('core/session')->setData('aff_player_name', $name);
                    Mage::getModel('core/session')->setData('aff_player_id', $face_id);
                    Mage::getModel('core/session')->setData('campaign_id',$campaign_id);

                    if($email){
                        $this->register_customer($face_id,$email,$name);
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

    protected function isJoined($face_id,$campaign_id)
    {
        $collection = Mage::getModel('campaign/affplayer')->getCollection()->addFieldToFilter('face_id', $face_id)->addFieldToFilter('campaign_id',$campaign_id);
        if ($collection && count($collection)) {
            return true;
        }

        return false;
    }

    protected function getCode($campaign_id)
    {
        $collection = Mage::getModel('campaign/affplayer')->getCollection()->addFieldToFilter('campaign_id',$campaign_id);

        if ($collection && count($collection)) {

            $last_item = $collection->getLastItem();
            if ($last_item && $last_item->getCode()) {
                $code = $last_item->getCode();
                return ($code + 1);
            }

        }

        return '000000';
    }

}