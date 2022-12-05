<?php

class Soloyo_Campaign_Block_Campaign extends Mage_Core_Block_Template
{
    /**
     * prepare block's layout
     *
     * @return Soloyo_Campaign_Block_Campaign
     */
    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getDesignCateUrl()
    {
        $cate_model = Mage::getModel('catalog/category')->load('3');
        $cate_url = $cate_model->getUrl();
        return $cate_url;
    }

    protected $_campaign;

    public function getCampaign()
    {
        $campaign_id = $this->getRequest()->getParam('id');
        if (!$campaign_id) {
            $campaign_id = '3';
        }
        $this->_campaign = Mage::getModel('campaign/campaign')->load($campaign_id);
    }

    public function getAdwardImage()
    {


        return $this->getBaseUrlImage() . $this->_campaign->getAdwardImage();
    }


    public function isRunning()
    {
        if (!$this->_campaign) {
            $this->getCampaign();
        }

        if (!$this->_campaign) {
            return false;
        }

        $status = $this->_campaign->getStatus();

        if ($status == 2) {
            return false;
        }

        return true;

    }

    public function getBanner()
    {
        if (!$this->_campaign) {
            $this->getCampaign();
        }

        $banner = $this->_campaign->getBanner();
        return $this->getBaseUrlImage() . $banner;

    }


    public function getAdwardText()
    {

        return $this->_campaign->getAdwardText();
    }

    public function getRuleWin()
    {
        return $this->_campaign->getRuleWin();
    }

    public function getRuleWinOther()
    {
        return $this->_campaign->getRuleWinOther();
    }

    public function getOtherTitle()
    {
        return $this->_campaign->getOtherTitle();
    }

    public function getOtherText()
    {
        return $this->_campaign->getOtherText();
    }

    protected function getBaseUrlImage()
    {
        return Mage::helper('campaign')->getUrlImageCampaign() . DS;
    }


}