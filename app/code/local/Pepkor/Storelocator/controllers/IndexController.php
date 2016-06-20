<?php
/**
 * Created by PhpStorm.
 * User: janizol
 * Date: 2016/06/06
 * Time: 9:58 AM
 */

class Pepkor_Storelocator_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction ()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function mamethodeAction ()
    {
        echo 'test mymethod';
    }

    public function getJsonStoresAction(){
        $province_id = $this->getRequest()->getPost('province_id');
        $stores = $this->getLayout()->getBlockSingleton('storelocator/myblock')->getJsonStores(false, $province_id);
        echo $stores;
    }
}