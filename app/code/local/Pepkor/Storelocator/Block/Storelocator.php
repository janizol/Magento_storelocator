<?php
/**
 * Created by PhpStorm.
 * User: janizol
 * Date: 2016/06/06
 * Time: 1:20 PM
 */

class Pepkor_Storelocator_Block_Storelocator extends Mage_Core_Block_Template
{
    public function methodblock()
    {
        $return ='';
        $collection = $this->getStoreData();

        foreach($collection as $data)
        {
            //var_dump($data);die();
            $return .= $data->getData('name').' '.$data->getData('address')
                .' '.$data->getData('lat').' '.$data->getData('lng')
                .' <a href="'.Mage::helper("adminhtml")->getUrl("adminstorelocator/adminhtml_index/edit/id/".$data->getData('store_id')).'">Edit</a> | <a href="'.Mage::helper("adminhtml")->getUrl("adminstorelocator/adminhtml_index/delete/id/".$data->getData('store_id')).'" onclick="return confirm(\'Are you sure you want to delete this item?\');">Delete</a><br />';
        }

        return $return;
    }

    public function getProvinces(){
        $collection = Mage::getModel('storelocator/storelocatorprovinces')->getCollection()
            ->setOrder('storelocator_province','asc');
        $i = 0;
        foreach($collection as $data) {
            $return[$i] = ['id' => $data->getData('id'), 'storelocator_province' => $data->getData('storelocator_province')];
            $i++;
        }
        return $return;
    }

    protected function getStoreData($array = false, $id = false, $province_id = false){
        if($id){
            $collection = Mage::getModel('storelocator/storelocator')->getCollection()
                ->addFieldToFilter(array('store_id'), array($id))
                ->setOrder('store_id','asc')
                ->join(Mage::getConfig()->getTablePrefix().'storelocatorprovinces',
                    'main_table.storelocator_province_id='.Mage::getConfig()->getTablePrefix().'storelocatorprovinces.id',
                    array('storelocator_province' => 'storelocator_province'), null, 'left');
        } elseif($province_id){
            $collection = Mage::getModel('storelocator/storelocator')->getCollection()
                ->addFieldToFilter(array('storelocator_province_id'), array($province_id))
                ->setOrder('store_id','asc')
                ->join(Mage::getConfig()->getTablePrefix().'storelocatorprovinces',
                    'main_table.storelocator_province_id='.Mage::getConfig()->getTablePrefix().'storelocatorprovinces.id',
                    array('storelocator_province' => 'storelocator_province'), null, 'left');
        } else {
            $collection = Mage::getModel('storelocator/storelocator')->getCollection()
                ->setOrder('store_id','asc')
                ->join(Mage::getConfig()->getTablePrefix().'storelocatorprovinces',
                    'main_table.storelocator_province_id='.Mage::getConfig()->getTablePrefix().'storelocatorprovinces.id',
                    array('storelocator_province' => 'storelocator_province'), null, 'left');
        }
        if($array){
            $i = 0;
            foreach($collection as $data)
            {
                $return[$i] = ['name' => $data->getData('name'), 'address' => $data->getData('address'), 'lat' => $data->getData('lat'), 'lng' => $data->getData('lng'), 'telephone' => $data->getData('telephone'), 'store_hours' => $data->getData('store_hours'), 'storelocator_province_id' => $data->getData('storelocator_province_id'), 'storelocator_province' => $data->getData('storelocator_province')];
                $i++;
            }
            return $return;
        }
        return $collection;
    }

    public function getJsonStores($id = false, $province_id = false){
        if($id){
            $return = $this->getStoreData(true,$id);
        } elseif ($province_id){
            $return = $this->getStoreData(true,false,$province_id);
        } else {
            $return = $this->getStoreData(true);
        }

        return json_encode($return);
    }

    public function readStoreHours($json){
        $array = json_decode($json,true);

        //UGLY!!!!

        if(!empty($array['Monday'])){
            $return['mon_from'] = substr($array['Monday'], 0, 5);
            $return['mon_to'] = substr($array['Monday'], -5);
        } else {
            $return['mon_from'] = '';
            $return['mon_to'] = '';
        }

        if(!empty($array['Tuesday'])){
            $return['tue_from'] = substr($array['Tuesday'], 0, 5);
            $return['tue_to'] = substr($array['Tuesday'], -5);
        } else {
            $return['tue_from'] = '';
            $return['tue_to'] = '';
        }

        if(!empty($array['Wednesday'])){
            $return['wed_from'] = substr($array['Wednesday'], 0, 5);
            $return['wed_to'] = substr($array['Wednesday'], -5);
        } else {
            $return['wed_from'] = '';
            $return['wed_to'] = '';
        }

        if(!empty($array['Thursday'])){
            $return['thu_from'] = substr($array['Thursday'], 0, 5);
            $return['thu_to'] = substr($array['Thursday'], -5);
        } else {
            $return['thu_from'] = '';
            $return['thu_to'] = '';
        }

        if(!empty($array['Friday'])){
            $return['fri_from'] = substr($array['Friday'], 0, 5);
            $return['fri_to'] = substr($array['Friday'], -5);
        } else {
            $return['fri_from'] = '';
            $return['fri_to'] = '';
        }

        if(!empty($array['Saturday'])){
            $return['sat_from'] = substr($array['Saturday'], 0, 5);
            $return['sat_to'] = substr($array['Saturday'], -5);
        } else {
            $return['sat_from'] = '';
            $return['sat_to'] = '';
        }

        if(!empty($array['Sunday'])){
            $return['sun_from'] = substr($array['Sunday'], 0, 5);
            $return['sun_to'] = substr($array['Sunday'], -5);
        } else {
            $return['sun_from'] = '';
            $return['sun_to'] = '';
        }

        return $return;
    }
}