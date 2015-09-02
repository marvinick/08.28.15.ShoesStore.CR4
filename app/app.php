<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Store.php";
    require_once __DIR__."/../src/Brand.php";

    $app = new Silex\Application();
    $server = 'mysql:host=us-cdbr-iron-east-02.cleardb.net;dbname=heroku_b63e64737fc3c2a?reconnect=true';
    $username = 'b933e4e535f56e';
    $password = '157217c4';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
      'twig.path' => array (
           __DIR__.'/../views'
      )
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    //index
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array('stores' => Store::getAll(), 'brands'=> Brand::getAll()));
    });

    //store routes
    //all stores
    $app->get("/stores", function() use ($app) {
    return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    //creating store
    $app->post("/stores", function() use ($app) {
    $name = $_POST['name'];
    $store = new Store($name);
    $store->save();
    return $app['twig']->render('stores.html.twig', array('stores' => Store::getAll()));
    });

    //view a store
    $app->get("/stores/{id}", function($id) use ($app) {
    $store = Store::find($id);
    return $app['twig']->render('store.html.twig', array('store' => $store, 'stores' => Store::getAll(), 'brands' => $store->getBrands(), 'all_brands' => Brand::getAll()));
    });

    //add brand to store
    $app->post("/add_brands", function() use ($app) {
        $store = Store::find($_POST['store_id']);
        $brand = Brand::find($_POST['brand_id']);
        $store->addBrand($brand);
        return $app['twig']->render('store.html.twig', array('store' => $store, 'stores' => Store::getAll(), 'brands' => $store->getBrands(), 'all_brands' => Brand::getAll()));
    });

    //update store
    $app->get("/stores/{id}/edit", function($id) use ($app) {
        $store = Store::find($id);
        return $app['twig']->render('edit_store.html.twig', array('store' => $store));
    });

    $app->patch("/stores/{id}", function($id) use ($app) {
        $name = $_POST['name'];
        $store = Store::find($id);
        $store->update($name);
        return $app['twig']->render('store.html.twig', array('store' => $store, 'stores' => Store::getAll(), 'brands' => $store->getBrands(), 'all_brands' => Brand::getAll()));
    });

    //delete a store
    $app->delete("/stores/{id}", function($id) use ($app) {
        $store = Store::find($id);
        $store->delete();
        return $app['twig']->render('index.html.twig', array('stores' => Store::getAll(), 'brands'=> Brand::getAll()));
    });

    //delete all stores
    $app->post("/delete_stores", function() use ($app) {
        Store::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    //brand routes
    //all brands
     $app->get("/brands", function() use ($app) {
        return $app['twig']->render('brands.html.twig', array('brands' => Brand::getAll()));
    });

    //creating brand
    $app->post("/brands", function() use ($app) {
    $name = $_POST['name'];
    $brand = new Brand($name);
    $brand->save();
    return $app['twig']->render('brands.html.twig', array('brands' => Brand::getAll()));
    });

    //view a brand
    $app->get("/brands/{id}", function($id) use ($app) {
    $brand = Brand::find($id);
    return $app['twig']->render('brand.html.twig', array('brand' => $brand,  'brands' => Brand::getAll(), 'stores' => $brand->getStores(), 'all_stores' => Store::getAll()));
    });

    //add store to the brand
    $app->post("/add_stores", function() use ($app) {
        $brand = Brand::find($_POST['brand_id']);
        $store = Store::find($_POST['store_id']);
        $brand->addStore($store);
        return $app['twig']->render('brand.html.twig', array('brand' => $brand, 'brands' => Brand::getAll(), 'stores' => $brand->getStores(), 'all_stores' => Store::getAll()));
    });

    //search
    //search store
    $app->get('/store_results', function() use ($app) {
        $store_matching_search = array();
        $stores = Store::getAll();
        $name = $_GET['name'];
        foreach ($stores as $store) {
            if ($store->getName() == $name)
             {
                 array_push($store_matching_search, $store);
             }
        }
        return $app['twig']->render('result_store.html.twig', array('matched_stores' => $store_matching_search));
    });

    //search brand
    $app->get('/brand_results', function() use ($app) {
        $brand_matching_search = array();
        $brands = Brand::getAll();
        $name = $_GET['name'];
        foreach ($brands as $brand) {
            if ($brand->getName() == $name)
             {
                 array_push($brand_matching_search, $brand);
             }
        }
        return $app['twig']->render('result_brand.html.twig', array('matched_brands' => $brand_matching_search));
    });

    return $app;
?>