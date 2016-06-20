<?php
class Pepkor_Storelocator_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editAction(){
        Mage::register('store_id', $this->getRequest()->getParam('id'));
        $this->loadLayout();
        $this->renderLayout();

        //$this->_redirect('adminstorelocator/adminhtml_index');
    }

    public function deleteAction(){
        $store = Mage::getModel('storelocator/storelocator');
        $store->setId($this->getRequest()->getParam('id'))->delete();
        Mage::getSingleton('adminhtml/session')->addSuccess('Store deleted successfully!');
        $this->_redirect('adminstorelocator/adminhtml_index');
    }

    public function saveAction()
    {
        //set data

        $name = $this->getRequest()->getPost('name');
        $address = $this->getRequest()->getPost('address');
        $latitude = $this->getRequest()->getPost('lat');
        $longitude = $this->getRequest()->getPost('lng');
        $telephone = $this->getRequest()->getPost('telephone');
        $province = $this->getRequest()->getPost('province');
        $hours = [
            'Monday' => $this->getRequest()->getPost('mon_from').' - '.$this->getRequest()->getPost('mon_to'),
            'Tuesday' => $this->getRequest()->getPost('tue_from').' - '.$this->getRequest()->getPost('tue_to'),
            'Wednesday' => $this->getRequest()->getPost('wed_from').' - '.$this->getRequest()->getPost('wed_to'),
            'Thursday' => $this->getRequest()->getPost('thu_from').' - '.$this->getRequest()->getPost('thu_to'),
            'Friday' => $this->getRequest()->getPost('fri_from').' - '.$this->getRequest()->getPost('fri_to'),
            'Saturday' => $this->getRequest()->getPost('sat_from').' - '.$this->getRequest()->getPost('sat_to'),
            'Sunday' => $this->getRequest()->getPost('sun_from').' - '.$this->getRequest()->getPost('sun_to')
        ];

        $hours = json_encode($hours);

        if(isset($name)&&($name!='') && isset($address)&&($address!='')
            && isset($latitude)&&($latitude!='') && isset($longitude)&&($longitude!='') )
        {

            $store = Mage::getModel('storelocator/storelocator');
            $store->setData('name', $name);
            $store->setData('address', $address);
            $store->setData('lat', $latitude);
            $store->setData('lng', $longitude);
            $store->setData('telephone', $telephone);
            $store->setData('storelocator_province_id', $province);
            $store->setData('store_hours', $hours);
        }

        //is it an edit or create

        if($this->getRequest()->getParam('edit')){
            //edit

            $store->setId($this->getRequest()->getParam('edit'))->save();
            Mage::getSingleton('adminhtml/session')->addSuccess('Store edited successfully!');
        } else {
            //create

            $store->save();
            Mage::getSingleton('adminhtml/session')->addSuccess('New store added to storelocator!');
        }

        $this->_redirect('adminstorelocator/adminhtml_index');
    }
}