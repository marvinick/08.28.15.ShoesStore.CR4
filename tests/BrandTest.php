<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Brand.php";

    $server = 'mysql:host=localhost; dbname=shoes_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BrandTest extends PHPUnit_Framework_TestCase
    {

         protected function tearDown()
        {
            Brand::deleteAll();
            Store::deleteAll();
        }

        function test_getId()
        {
            //Arrange
            $name = "Buffalo";
            $id = 1;
            $test_brand = new Brand($name, $id);

            //Act
            $result = $test_brand->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function test_save()
        {
          //arrange
          $name = "Buffalo";
          $id = 1;
          $test_brand = new Brand($name, $id);

          //act
          $test_brand->save();

          //assert
          $result = Brand::getAll();
          $this->assertEquals($test_brand, $result[0]);

        }

        function test_getAll()
        {
          //arrange
          $name = "Buffalo";
          $id = 1;
          $name2 = "Knockoff";
          $id2 = 2;
          $test_brand = new Brand($name, $id);
          $test_brand->save();
          $test_brand2 = new Brand($name2, $id2);
          $test_brand2->save();

          //act
          $result = Brand::getAll();

          //assert
          $this->assertEquals([$test_brand, $test_brand2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $name = "Buffalo";
            $id = 1;
            $name2 = "Knockoff";
            $id2 = 2;
            $test_brand = new Brand($name, $id);
            $test_brand->save();
            $test_brand2 = new Brand($name2, $id2);
            $test_brand2->save();

            //Act
            Brand::deleteAll();

            //Assert
            $result = Brand::getAll();
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $name = "Buffalo";
            $id = 1;
            $name2 = "Knockoff";
            $id2 = 2;
            $test_brand = new Brand($name, $id);
            $test_brand->save();
            $test_brand2 = new Brand($name2, $id2);
            $test_brand2->save();

            //Act
            $id = $test_brand->getId();
            $result = Brand::find($id);

            //Assert
            $this->assertEquals($test_brand, $result);
        }

        function testAddStore()
        {
            //Arrange
            $name = "Buffalo";
            $id = 1;
            $test_brand = new Brand($name, $id);
            $test_brand->save();

            $name = "Wings";
            $id2 = 2;
            $test_store = new Store($name, $id2);
            $test_store->save();

            //Act
            $test_brand->addStore($test_store);

            //Assert
            $this->assertEquals($test_brand->getStores(), [$test_store]);
        }

        function testGetStores()
        {
            //Arrange
            $name = "Buffalo";
            $id = 1;
            $test_brand = new Brand($name, $id);
            $test_brand->save();

            $name = "Wings";
            $id2 = 2;
            $test_store = new Store($name, $id2);
            $test_store->save();

            $name2 = "Wings";
            $id3 = 3;
            $test_store2 = new Store($name2, $id3);
            $test_store2->save();


            //Act
            $test_brand->addStore($test_store);
            $test_brand->addStore($test_store2);

            //Assert
            $this->assertEquals($test_brand->getStores(), [$test_store, $test_store2]);
        }

        function testDelete()
        {

            //Arrange
            $name = "Chicken";
            $id = 1;
            $test_brand = new Brand($name, $id);
            $test_brand->save();

            $name = "Wings";
            $id2 = 2;
            $test_store = new Store($name, $id2);
            $test_store->save();

            //Act
            $test_brand->addStore($test_store);
            $test_brand->delete();

            //Assert
            $this->assertEquals([], $test_store->getBrands());
        }
    }
?>

