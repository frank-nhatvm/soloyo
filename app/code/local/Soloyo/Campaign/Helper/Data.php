<?php

class Soloyo_Campaign_Helper_Data extends Mage_Core_Helper_Abstract
{
    const DIR_IMAGE_CAMPAIGN = 'campaign'.DS;

    public function getDirImageCampaign(){
        $dir = Mage::getBaseDir('media') . DS.self::DIR_IMAGE_CAMPAIGN;
        if(!file_exists($dir)){
            mkdir($dir, 0777, true);
        }

        return $dir;
    }

    public function getUrlImageCampaign(){
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).self::DIR_IMAGE_CAMPAIGN;
    }

    public function saveImageFile($key_file)
    {
        if (isset($_FILES[$key_file]['name']) && $_FILES[$key_file]['name'] != '') {
            try {
                $uploader = new Varien_File_Uploader($key_file);
                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $path = $this->getDirImageCampaign();
                $file_name = time() . $_FILES[$key_file]['name'];
                $result = $uploader->save($path, $file_name);
                return $result['file'];
            } catch (Exception $e) {
                return $_FILES[$key_file]['name'];
            }
        }
        return '';
    }

}