<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Store.php";

    $server = 'mysql:host=localhost; dbname=shoes_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class StoreTest extends PHPUnit_Framework_TestCase
    {

         protected function tearDown()
        {
            Store::deleteAll();
            Brand::deleteAll();
        }

        function test_getId()
        {
            //Arrange
            $name = "Wings";
            $id = 1;
            $test_store = new Store($name, $id);

            //Act
            $result = $test_store->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function test_save()
        {
          //arrange
          $name = "Wings";
          $id = 1;
          $test_store = new Store($name, $id);

          //act
          $test_store->save();

          //assert
          $result = Store::getAll();
          $this->assertEquals($test_store, $result[0]);

        }

        function test_getAll()
        {
          //arrange
          $name = "Wings";
          $id = 1;
          $name2 = "Chicken";
          $id2 = 2;
          $test_store = new Store($name, $id);
          $test_store->save();
          $test_store2 = new Store($name2, $id2);
          $test_store2->save();

          //act
          $result = Store::getAll();

          //assert
          $this->assertEquals([$test_store, $test_store2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $name = "Wings";
            $id = 1;
            $name2 = "Chicken";
            $id2 = 2;
            $test_store = new Store($name, $id);
            $test_store->save();
            $test_store2 = new Store($name2, $id2);
            $test_store2->save();

            //Act
            Store::deleteAll();

            //Assert
            $result = Store::getAll();
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $name = "Wings";
            $id = 1;
            $name2 = "Chicken";
            $id2 = 2;
            $test_store = new Store($name, $id);
            $test_store->save();
            $test_store2 = new Store($name2, $id2);
            $test_store2->save();

            //Act
            $id = $test_store->getId();
            $result = Store::find($id);

            //Assert
            $this->assertEquals($test_store, $result);
        }

        function testUpdate()
        {
            //Arrange
            $name = "Wings";
            $id = 1;
            $test_store = new Store($name, $id);
            $test_store->save();

            $new_name = "Chicken";

            //Act
            $test_store->update($new_name);

            //Assert
            $this->assertEquals("Chicken", $test_store->getName());
        }

        function testDelete()
        {

            //Arrange
            $name = "Chicken";
            $id2 = 2;
            $test_brand = new Brand($name, $id2);
            $test_brand->save();

            $name = "Wings";
            $id = 1;
            $test_store = new Store($name, $id);
            $test_store->save();

            //Act
            $test_store->addBrand($test_brand);
            $test_store->delete();

            //Assert
            $this->assertEquals([], $test_brand->getStores());
        }

        function testAddBrand()
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
            $test_store->addBrand($test_brand);

            //Assert
            $this->assertEquals($test_store->getBrands(), [$test_brand]);
        }

        function testGetBrands()
        {
            //Arrange
            $name = "Buffalo";
            $id = 1;
            $test_store = new Store($name, $id);
            $test_store->save();

            $name = "Wings";
            $id2 = 2;
            $test_brand = new Brand($name, $id2);
            $test_brand->save();

            $name2 = "Chicken";
            $id3 = 3;
            $test_brand2 = new Brand($name2, $id3);
            $test_brand2->save();


            //Act
            $test_store->addBrand($test_brand);
            $test_store->addBrand($test_brand2);

            //Assert
            $this->assertEquals($test_store->getBrands(), [$test_brand, $test_brand2]);
        }
    }
?>

