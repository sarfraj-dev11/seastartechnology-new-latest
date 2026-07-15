<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/db.php';


$jsonFile = __DIR__ . '/data/products.json';


if (!file_exists($jsonFile)) {
    die("ERROR: products.json not found");
}


$json = file_get_contents($jsonFile);

$products = json_decode($json, true);


if (json_last_error() !== JSON_ERROR_NONE) {
    die("JSON ERROR: " . json_last_error_msg());
}


$db = get_db();


try {

    $db->beginTransaction();


    foreach ($products as $p) {


        // MAIN PRODUCT
        $stmt = $db->prepare("
            INSERT INTO products
            (
                id,
                slug,
                title,
                brand,
                category,
                price,
                duplicat_price,
                badge,
                short_desc,
                long_desc,
                description1,
                description2,
                editorDisc,
                image
            )
            VALUES
            (?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");


        $stmt->execute([
            $p['id'],
            $p['slug'],
            $p['title'],
            $p['brand'] ?? '',
            $p['category'] ?? '',
            $p['price'] ?? 0,
            $p['duplicat_price'] ?? null,
            $p['badge'] ?? '',
            $p['short_desc'] ?? '',
            $p['long_desc'] ?? '',
            $p['description1'] ?? '',
            $p['description2'] ?? '',
            $p['editorDisc'] ?? '',
            $p['image'] ?? ''
        ]);


        $productId = $p['id'];



        // PROBLEM SOLVED
        if (!empty($p['problem_solved'])) {

            $stmt = $db->prepare("
                INSERT INTO product_problem_solved
                (product_id,item_text,sort_order)
                VALUES (?,?,?)
            ");

            foreach ($p['problem_solved'] as $i=>$text) {

                $stmt->execute([
                    $productId,
                    $text,
                    $i
                ]);
            }
        }



        // WHATS INCLUDED
        if (!empty($p['whats_included'])) {

            $stmt = $db->prepare("
                INSERT INTO product_whats_included
                (product_id,item_text,sort_order)
                VALUES (?,?,?)
            ");

            foreach ($p['whats_included'] as $i=>$text) {

                $stmt->execute([
                    $productId,
                    $text,
                    $i
                ]);
            }
        }



        // SPECS
        if (!empty($p['specs'])) {

            $stmt = $db->prepare("
                INSERT INTO product_specs
                (product_id,spec_key,spec_value,sort_order)
                VALUES (?,?,?,?)
            ");

            $i = 0;

            foreach ($p['specs'] as $key=>$value) {

                $stmt->execute([
                    $productId,
                    $key,
                    $value,
                    $i
                ]);

                $i++;
            }
        }



        // GALLERY IMAGES
        if (!empty($p['gallery_images'])) {

            $stmt = $db->prepare("
                INSERT INTO product_gallery_images
                (product_id,image_path,sort_order)
                VALUES (?,?,?)
            ");


            foreach ($p['gallery_images'] as $i=>$img) {

                $stmt->execute([
                    $productId,
                    $img,
                    $i
                ]);

            }
        }



        // RELATED PRODUCTS
        if (!empty($p['related'])) {

            $stmt = $db->prepare("
                INSERT INTO product_related
                (product_id,related_slug,sort_order)
                VALUES (?,?,?)
            ");


            foreach ($p['related'] as $i=>$slug) {

                $stmt->execute([
                    $productId,
                    $slug,
                    $i
                ]);

            }
        }



        // HOW TO USE
        if (!empty($p['how_to_use'])) {

            $stmt = $db->prepare("
                INSERT INTO product_how_to_use
                (product_id,step_text,sort_order)
                VALUES (?,?,?)
            ");


            foreach ($p['how_to_use'] as $i=>$step) {

                $stmt->execute([
                    $productId,
                    $step,
                    $i
                ]);
            }
        }



    }


    $db->commit();


    echo "SUCCESS: Products imported";


}
catch(Exception $e){

    if($db->inTransaction()){
        $db->rollBack();
    }

    echo "IMPORT FAILED: ".$e->getMessage();

}