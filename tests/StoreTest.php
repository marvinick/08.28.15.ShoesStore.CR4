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
          $test_store = new Store($name);

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
          $name2 = "Chicken";
          $test_store = new Store($name);
          $test_store->save();
          $test_store2 = new Store($name2);
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
            $name2 = "Chicken";
            $test_store = new Store($name);
            $test_store->save();
            $test_store2 = new Store($name2);
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
            $name2 = "Chicken";
            $test_store = new Store($name);
            $test_store->save();
            $test_store2 = new Store($name2);
            $test_store2->save();

            //Act
            $id = $test_store->getId();
            $result = Store::find($id);

            //Assert
            $this->assertEquals($test_store, $result);
        }
    }
?>

