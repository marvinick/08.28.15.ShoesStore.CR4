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

    class StoreTest extends PHPUnit_Framework_TestCase
    {

         protected function tearDown()
        {
            Brand::deleteAll();
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
          $test_brand = new Brand($name);

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
          $name2 = "Knockoff";
          $test_brand = new Brand($name);
          $test_brand->save();
          $test_brand2 = new Brand($name2);
          $test_brand2->save();

          //act
          $result = Brand::getAll();

          //assert
          $this->assertEquals([$test_brand, $test_brand2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $name = "Wings";
            $name2 = "Chicken";
            $test_brand = new Brand($name);
            $test_brand->save();
            $test_brand2 = new Store($name2);
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
            $name = "Wings";
            $name2 = "Chicken";
            $test_brand = new Brand($name);
            $test_brand->save();
            $test_brand2 = new Brand($name2);
            $test_brand2->save();

            //Act
            $id = $test_brand->getId();
            $result = Brand::find($id);

            //Assert
            $this->assertEquals($test_brand, $result);
        }
    }
?>

