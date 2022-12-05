<?php

class Soloyo_Soloyo_Helper_Data extends Mage_Core_Helper_Abstract
{

    const DIR_HOME_BANNER = 'home' . DS . 'banner' . DS;
    const DIR_HOME_CATE = 'home' . DS . 'cate' . DS;
    const DIR_HOME_PRODUCTS = 'home' . DS . 'products' . DS;
    const DIR_HOME_ITEM = 'home' . DS . 'item' . DS;

    public function saveImage($key, $path_save)
    {
        if (isset($_FILES[$key]['name']) && $_FILES[$key]['name'] != '') {
            try {
                $uploader = new Varien_File_Uploader($key);

                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(false);

                $uploader->setFilesDispersion(false);
                $file_name = time() . $_FILES['image']['name'];
                $result = $uploader->save($path_save, $file_name);
                return $result['file'];
            } catch (Exception $e) {
                return $_FILES[$key]['name'];
            }
        }
        return null;
    }

    public function getDirHomeBannerImage()
    {
        return $this->getDir(self::DIR_HOME_BANNER);
    }

    public function getUrlHomeBanerImage()
    {
        return $this->getUrl(self::DIR_HOME_BANNER);
    }

    public function getDirHomeCateImage()
    {
        return $this->getDir(self::DIR_HOME_CATE);
    }

    public function getUrlHomeCateImage()
    {
        return $this->getUrl(self::DIR_HOME_CATE);
    }

    public function getDirHomeProductsImage()
    {
        return $this->getDir(self::DIR_HOME_PRODUCTS);
    }

    public function getUrlHomeProductsImage()
    {
        return $this->getUrl(self::DIR_HOME_PRODUCTS);
    }

    public function getDirHomeItemImage()
    {
        return $this->getDir(self::DIR_HOME_ITEM);
    }

    public function getUrlHomeItemImage()
    {
        return $this->getUrl(self::DIR_HOME_ITEM);
    }

    protected function getDir($dir_path)
    {
        $dir = Mage::getBaseDir('media') . DS . $dir_path;
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        return $dir;
    }

    protected function getUrl($dir_path)
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $dir_path;
    }


    public function getUrlCate($cate_id)
    {
        $cate_model = Mage::getModel('catalog/category')->load($cate_id);

        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB, true) . $cate_model->getData('url_path');
    }

    public function getRecentlyProduct()
    {
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect(
                Mage::getSingleton('catalog/config')
                    ->getProductAttributes()
            )
            ->addFieldToFilter('casedesign', '0')
            ->addUrlRewrite();

        $collection->setOrder('created_at', 'desc');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        $collection->setPageSize(15);
        $items = array();

        foreach ($collection as $product) {
            $item = array();
            $product_id = $product->getId();
            $item['id'] = $product_id;
            $item['name'] = $product->getName();
            $imageHelper = Mage::helper('catalog/image');
            $thumbnail_image = $imageHelper->init($product, 'thumbnail')->resize(500, 500)->__toString();
            $item['image'] = $thumbnail_image;
            $item['url'] = $product->getProductUrl();
            $items[] = $item;

        }

        return $items;
    }

    public function isMobileOrTablet()
    {


        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') !== false) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            return true;
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-');

        if (in_array($mobile_ua, $mobile_agents)) {
            return true;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') !== false) {
            return true;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                return true;
            }
        }

        return false;

    }

}