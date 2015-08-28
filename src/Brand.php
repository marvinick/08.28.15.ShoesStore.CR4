<?php

    class Brand {

        private $name;
        private $id;

        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }

        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }

        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO brands (name) VALUES ('{$this->getName()}');");
             $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_brands = $GLOBALS['DB']->query("SELECT * FROM brands;");
            $brands = array();
            foreach($returned_brands as $brand) {
                $name = $brand['name'];
                $id = $brand['id'];
                $new_brand = new Brand($name, $id);
                array_push($brands, $new_brand);
            }
            return $brands;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM brands;");
        }

        static function find($search_id)
        {
            $found_brand = null;
            $brands = Brand::getAll();
            foreach($brands as $brand) {
                $brand_id = $brand->getId();
                if ($brand_id == $search_id) {
                    $found_brand = $brand;
                }
            }
            return $found_brand;
        }

        function addStore($store)
        {
            $GLOBALS['DB']->exec("INSERT INTO outlets (brand_id, store_id) VALUES ({$this->getId()}, {$store->getId()});");
        }

        function getStores()
        {
            $brand_id = $this->getId();
            $returned_stores = $GLOBALS['DB']->query("SELECT stores.* FROM brands JOIN outlets ON (brands.id = outlets.brand_id) JOIN stores ON(outlets.store_id = stores.id) WHERE brands.id = {$brand_id}");

            $stores = array();
            foreach($returned_stores as $store) {
                $name = $store['name'];
                $id = $store['id'];
                $new_store = new Store($name, $id);
                array_push($stores, $new_store);
            }
            return $stores;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM brands WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM outlets WHERE brand_id = {$this->getId()};");
        }
    }
?>
