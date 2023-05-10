<?php

require_once(__DIR__ . '/../../vendor/autoload.php'); 
require_once("../functions/define.php");
require_once("../functions/functions.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');


use Amazon\ProductAdvertisingAPI\v1\ApiException;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\api\DefaultApi;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\PartnerType;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\ProductAdvertisingAPIClientException;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\SearchItemsRequest;
use Amazon\ProductAdvertisingAPI\v1\com\amazon\paapi5\v1\SearchItemsResource;
use Amazon\ProductAdvertisingAPI\v1\Configuration;

$date = date("Y-m-d");
$datepast = date('Y-m-d',(strtotime ( '-4 day' , strtotime ( $date) ) ));
$sql = "SELECT * FROM $tableKeepa";

$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)) {

  $global = $row["CANAL"];
  $chat_id = $row["IDTELEGRAM"];
  $canal = $row["URLTELEGRAM"];
  $urlkeepa = $row["URLKEEPA"];
  $urlkeepa = str_replace("YOUR_API_KEY", KEEPAAPI , $urlkeepa);

  $query = mysqli_query($conn,"SELECT * FROM $tableKeepa WHERE `CANAL` LIKE '$global' AND (`ASINS` IS NULL or `ASINS`!= '')  AND `revisado` IS FALSE");
  $count = mysqli_num_rows($query);

  $revisado = $row["revisado"];
  

  if ($count != 0 && $revisado == 0)
  {
    echo "<h2>".$global."</h2>";
    echo "</br>El canal ".$global." tiene chollos en la BBDD</br>";
    $query = mysqli_fetch_assoc(mysqli_query($conn,"SELECT `ASINS` FROM $tableKeepa WHERE `CANAL` LIKE '$global'"));
    $chollos = $query['ASINS'];

    // echo "</br> Estos son los ASINS que hay en la BBDD del canal ".$global.": ".$chollos;
    $chollos = explode(',', $chollos);

    foreach ($chollos as $chollo){

    //CONSULTA BASE DE DATOS SI YA EXISTE ESTE CHOLLO
    // $query = mysqli_query($conn,"SELECT * FROM $table WHERE `ASIN` LIKE '$chollo'");
    $query = mysqli_query($conn,"SELECT * FROM $table WHERE `ASIN` LIKE '$chollo' AND `date` > '$datepast'");

    $count = mysqli_num_rows($query);
    echo "</br>Mirando el siguiente chollo: ".$chollo."</br>";
    
    
    if (is_array($chollos)) {
      $chollos = implode(',', $chollos);
    }
    
    $pos = strpos($chollos, ",");
    $chollos = substr($chollos, $pos + 1);

    // Supongamos que tienes una cadena llamada $chollos
    if (substr($chollos, 0, 1) != 'B') {
      $chollos = '';
    }

    
    
    $insert = mysqli_query($conn,"UPDATE $tableKeepa SET `ASINS`='$chollos' WHERE `CANAL` LIKE '$global'");

      if ( $count == 0){
        $keyword = $chollo;
          # Forming the request
        $config = new Configuration();

         # Please add your access key here
        $config->setAccessKey(AccessKey);
        # Please add your secret key here
        $config->setSecretKey(SecretKey);

        # Please add your partner tag (store/tracking id) here
        $config->setHost(Host);
        $config->setRegion(Region);
    
        $apiInstance = new DefaultApi(
            /*
             * If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
             * This is optional, `GuzzleHttp\Client` will be used as default.
             */
            new GuzzleHttp\Client(),
            $config
        );

        $searchIndex = "All";


    $itemCount = 1;


    $resources = array(
        SearchItemsResource::ITEM_INFOTITLE,
        SearchItemsResource::OFFERSLISTINGSPRICE,
        SearchItemsResource::BROWSE_NODE_INFOBROWSE_NODES,
        SearchItemsResource::BROWSE_NODE_INFOBROWSE_NODESANCESTOR,
        SearchItemsResource::BROWSE_NODE_INFOBROWSE_NODESSALES_RANK,
        SearchItemsResource::BROWSE_NODE_INFOWEBSITE_SALES_RANK,
        SearchItemsResource::IMAGESPRIMARYSMALL,
        SearchItemsResource::IMAGESPRIMARYMEDIUM,
        SearchItemsResource::IMAGESPRIMARYLARGE,
        SearchItemsResource::IMAGESVARIANTSSMALL,
        SearchItemsResource::IMAGESVARIANTSMEDIUM,
        SearchItemsResource::IMAGESVARIANTSLARGE,
        SearchItemsResource::ITEM_INFOBY_LINE_INFO,
        SearchItemsResource::ITEM_INFOCONTENT_INFO,
        SearchItemsResource::ITEM_INFOCONTENT_RATING,
        SearchItemsResource::ITEM_INFOCLASSIFICATIONS,
        SearchItemsResource::ITEM_INFOEXTERNAL_IDS,
        SearchItemsResource::ITEM_INFOFEATURES,
        SearchItemsResource::ITEM_INFOMANUFACTURE_INFO,
        SearchItemsResource::ITEM_INFOPRODUCT_INFO,
        SearchItemsResource::ITEM_INFOTECHNICAL_INFO,
        SearchItemsResource::ITEM_INFOTRADE_IN_INFO,
        SearchItemsResource::OFFERSLISTINGSAVAILABILITYMAX_ORDER_QUANTITY,
        SearchItemsResource::OFFERSLISTINGSAVAILABILITYMESSAGE,
        SearchItemsResource::OFFERSLISTINGSAVAILABILITYMIN_ORDER_QUANTITY,
        SearchItemsResource::OFFERSLISTINGSAVAILABILITYTYPE,
        SearchItemsResource::OFFERSLISTINGSCONDITION,
        SearchItemsResource::OFFERSLISTINGSCONDITIONSUB_CONDITION,
        SearchItemsResource::OFFERSLISTINGSDELIVERY_INFOIS_AMAZON_FULFILLED,
        SearchItemsResource::OFFERSLISTINGSDELIVERY_INFOIS_FREE_SHIPPING_ELIGIBLE,
        SearchItemsResource::OFFERSLISTINGSDELIVERY_INFOIS_PRIME_ELIGIBLE,
        SearchItemsResource::OFFERSLISTINGSDELIVERY_INFOSHIPPING_CHARGES,
        SearchItemsResource::OFFERSLISTINGSIS_BUY_BOX_WINNER,
        SearchItemsResource::OFFERSLISTINGSLOYALTY_POINTSPOINTS,
        SearchItemsResource::OFFERSLISTINGSMERCHANT_INFO,
        SearchItemsResource::OFFERSLISTINGSPROGRAM_ELIGIBILITYIS_PRIME_EXCLUSIVE,
        SearchItemsResource::OFFERSLISTINGSPROGRAM_ELIGIBILITYIS_PRIME_PANTRY,
        SearchItemsResource::OFFERSLISTINGSPROMOTIONS,
        SearchItemsResource::OFFERSLISTINGSSAVING_BASIS,
        SearchItemsResource::OFFERSSUMMARIESHIGHEST_PRICE,
        SearchItemsResource::OFFERSSUMMARIESLOWEST_PRICE,
        SearchItemsResource::OFFERSSUMMARIESOFFER_COUNT,
        SearchItemsResource::PARENT_ASIN,
        SearchItemsResource::RENTAL_OFFERSLISTINGSAVAILABILITYMAX_ORDER_QUANTITY,
        SearchItemsResource::RENTAL_OFFERSLISTINGSAVAILABILITYMESSAGE,
        SearchItemsResource::RENTAL_OFFERSLISTINGSAVAILABILITYMIN_ORDER_QUANTITY,
        SearchItemsResource::RENTAL_OFFERSLISTINGSAVAILABILITYTYPE,
        SearchItemsResource::RENTAL_OFFERSLISTINGSBASE_PRICE,
        SearchItemsResource::RENTAL_OFFERSLISTINGSCONDITION,
        SearchItemsResource::RENTAL_OFFERSLISTINGSCONDITIONSUB_CONDITION,
        SearchItemsResource::RENTAL_OFFERSLISTINGSDELIVERY_INFOIS_AMAZON_FULFILLED,
        SearchItemsResource::RENTAL_OFFERSLISTINGSDELIVERY_INFOIS_FREE_SHIPPING_ELIGIBLE,
        SearchItemsResource::RENTAL_OFFERSLISTINGSDELIVERY_INFOIS_PRIME_ELIGIBLE,
        SearchItemsResource::RENTAL_OFFERSLISTINGSDELIVERY_INFOSHIPPING_CHARGES,
        SearchItemsResource::RENTAL_OFFERSLISTINGSMERCHANT_INFO);
    

    # Forming the request
    $searchItemsRequest = new SearchItemsRequest();
    $searchItemsRequest->setSearchIndex($searchIndex);
    $searchItemsRequest->setKeywords($keyword);
    $searchItemsRequest->setItemCount($itemCount);
    $searchItemsRequest->setPartnerTag($partnerTag);
    $searchItemsRequest->setPartnerType(PartnerType::ASSOCIATES);
    $searchItemsRequest->setResources($resources);
        
    # Validating request
    $invalidPropertyList = $searchItemsRequest->listInvalidProperties();
    $length = count($invalidPropertyList);
    if ($length > 0) {
        echo "Error forming the request", PHP_EOL;
        foreach ($invalidPropertyList as $invalidProperty) {
            echo $invalidProperty, PHP_EOL;
        }
        return;
    }

    # Sending the request
    try {
        $searchItemsResponse = $apiInstance->searchItems($searchItemsRequest);

        // echo 'API called successfully', PHP_EOL;
        // echo 'Complete Response: ', $searchItemsResponse, PHP_EOL;

        # Parsing the response
        if ($searchItemsResponse->getSearchResult() !== null) {
            // echo 'Printing first item information in SearchResult:', PHP_EOL;
            $item = $searchItemsResponse->getSearchResult()->getItems()[0];
            if ($item !== null) {
                if ($item->getASIN() !== null) {
                    $product['ASIN']= $item->getASIN();
                    // echo "ASIN: ", $item->getASIN(), PHP_EOL;
                }
                if ($item->getDetailPageURL() !== null) {
                    // echo "DetailPageURL: ", $item->getDetailPageURL(), PHP_EOL;
                    $product['link'] = $item->getDetailPageURL();
                }
                if ($item->getItemInfo() !== null
                    and $item->getItemInfo()->getTitle() !== null
                    and $item->getItemInfo()->getTitle()->getDisplayValue() !== null) {
                    // echo "Title: ", $item->getItemInfo()->getTitle()->getDisplayValue(), PHP_EOL;
                    $product['title'] = $item->getItemInfo()->getTitle()->getDisplayValue();
                }
                if ( $item->getItemInfo()->getByLineInfo() != null and $item->getItemInfo()->getByLineInfo()->getBrand() != null and $item->getItemInfo()->getByLineInfo()->getBrand()->getDisplayValue() != null) {
                    $product['marca'] = $item->getItemInfo()->getByLineInfo()->getBrand()->getDisplayValue();
                }
                if ($item->getOffers() !== null
                    and $item->getOffers() !== null
                    and $item->getOffers()->getListings() !== null
                    and $item->getOffers()->getListings()[0]->getPrice() !== null
                    and $item->getOffers()->getListings()[0]->getPrice()->getDisplayAmount() !== null) {
                    // echo "Buying price: ", $item->getOffers()->getListings()[0]->getPrice()->getDisplayAmount(), PHP_EOL;
                    $product['pricetelegram'] = $item->getOffers()->getListings()[0]->getPrice()->getDisplayAmount();
                    $product['price'] = $item->getOffers()->getListings()[0]->getPrice()->getAmount();
                }
                if ($item->getImages()->getPrimary()->getLarge()->getuRL() != null){
                    $product['image'] = $item->getImages()->getPrimary()->getLarge()->getuRL();
                  }

                  if ($item->getOffers()->getSummaries() != null){
                    foreach ($item->getOffers()->getSummaries() as $summaries) {
                      if ($summaries->getCondition()->getValue() == "New"){
                        $product['highestPricetelegram'] = $summaries->gethighestPrice()->getDisplayAmount();
                        $product['highestPrice'] = $summaries->gethighestPrice()->getAmount();
                        $product['lowestPricetelegram'] = $summaries->getlowestPrice()->getDisplayAmount();
                        $product['lowestPrice'] = $summaries->getlowestPrice()->getAmount();
                      }
                    }
                  }
            }
        }
        if ($searchItemsResponse->getErrors() !== null) {
            echo PHP_EOL, 'Printing Errors:', PHP_EOL, 'Printing first error object from list of errors', PHP_EOL;
            echo 'Error code: ', $searchItemsResponse->getErrors()[0]->getCode(), PHP_EOL;
            echo 'Error message: ', $searchItemsResponse->getErrors()[0]->getMessage(), PHP_EOL;
            continue;
        }
    } catch (ApiException $exception) {
        echo "Error calling PA-API 5.0!", PHP_EOL;
        echo "HTTP Status Code: ", $exception->getCode(), PHP_EOL;
        echo "Error Message: ", $exception->getMessage(), PHP_EOL;
        if ($exception->getResponseObject() instanceof ProductAdvertisingAPIClientException) {
            $errors = $exception->getResponseObject()->getErrors();
            foreach ($errors as $error) {
                echo "Error Type: ", $error->getCode(), PHP_EOL;
                echo "Error Message: ", $error->getMessage(), PHP_EOL;
            }
        } else {
            echo "Error response body: ", $exception->getResponseBody(), PHP_EOL;
        }
    } catch (Exception $exception) {
        echo "Error Message: ", $exception->getMessage(), PHP_EOL;
    }
       
      

        $imagetelegram = imagetelegram($product['pricetelegram'],$product['image'],$ofertaidioma,$product['ASIN']);
        echo "<h2>Nueva oferta encontrada: ".$product['title']."</h2></br><b>".$product['pricetelegram']."</b></br>".$product['link']."</br>".$product['image']."</br>--------------</br>";
        $title = str_replace('&','',$product['title']);
        // $linkwhatsapp = urlencode($product['link']);
        $linktelegram = "https://chollosadiario.com/ver-producto/Amazon/".$product['ASIN'];
        $textwhatsapp = urlencode("🔥CHOLLAZO QUE HE ENCONTRADO EN EL CANAL DE TELEGRAM ").$canal."%0A%0A".urlencode($title)."%0A%0A".$linktelegram;
        
        $price = $product['price'];
        $lowestprice = $product['lowestPrice'];
        echo $lowestprice;
        $highestprice = $product['highestPrice'];
        echo $highestprice;
        if ($highestprice != 0) {
          $descuento = ($price*100) / $highestprice;
          $descuento = 100 - $descuento;
        } else {
          $descuento = 0;
          echo "El divisor es cero, se asignó el valor 0 a la variable resultado.";
        }
        $descuento = 100 - $descuento;
        $titletelegram = str_replace('-',' ',$product['title']);
        $titletelegram = str_replace('_',' ',$product['title']);
        $titletelegram = str_replace('·',' ',$product['title']);

        $botones = botones($linktelegram,$textwhatsapp,$TODOSCANALES,$ofertasamazon);
        if ( $descuento <= "0" || $descuento == "-0" || $descuento <= 0 ){
          $descuento = NULL;
        }

        else {    $descuento = " (-".round($descuento)."%)";}   
        //AÑADIR A BBDD

        
        $insert = mysqli_query($conn,"INSERT INTO $table (`date`,`CANAL`, `ASIN`, `PRICE`, `LOWESTPRICE`, `HIGHESTPRICE`) VALUES ('$date','$global', '$chollo', '$price', '$lowestprice', '$highestprice')");
        echo "Añadido a la base de datos el ASIN: ".$chollo." con el precio de: ".$product['pricetelegram']."</br> Precio mínimo de: ".$product['lowestPrice']."</br> Precio máximo de: ".$product['highestPrice']."</br> Descuento: ".$descuento;

        // mysqli_close($conn); 
        if ($product['title'] == NULL){ break;}

  //ENVIAR A TELEGRAM
  $data = [
          'text' => '
  🔥'.$titletelegram.' #AmazonES [ ]('.$imagetelegram.') 

  💵 *Oferta'.$descuento.': '.$product['pricetelegram'].'*


  ➡ [Comprar en Amazon]('.$linktelegram.') ⬅',
          'chat_id' => $chat_id,
          'parse_mode' => 'MARKDOWN'
      ];

      // if (file_get_contents("https://api.telegram.org/bot$token/sendMessage?".http_build_query($data)."&reply_markup=".botones($linktelegram,$textwhatsapp,$TODOSCANALES,$ofertasamazon)) == FALSE){
      //     file_get_contents("https://api.telegram.org/bot$token/sendMessage?".http_build_query($data));}

      //  file_get_contents("https://api.telegram.org/bot$token/sendMessage?".http_build_query($data)."&reply_markup=".botones($linktelegram,$textwhatsapp,$TODOSCANALES,$ofertasamazon));
     
      $url = "https://api.telegram.org/bot$token/sendMessage?".http_build_query($data)."&reply_markup=".$botones;
      $response = file_get_contents($url);

      $response=json_decode($response);
      $id_lastmessage = $response->result->message_id;


      // $markup = botones($linktelegram,$textwhatsapp,$TODOSCANALES,$ofertasamazon);
      // $url = "https://api.telegram.org/bot$token/sendMessage?" . http_build_query($data) . "&reply_markup=" . urlencode($markup);
      // file_get_contents($url);

      // Configura los datos de la solicitud
      // $url = "https://api.telegram.org/bot$token/sendMessage";
    
      // $markup = botones($linktelegram,$textwhatsapp,$TODOSCANALES,$ofertasamazon);
      // $query_string = http_build_query(array('reply_markup' => $markup));

      // // Inicia una sesión de cURL
      // $ch = curl_init();

      // // Configura las opciones de cURL
      // curl_setopt($ch, CURLOPT_URL, $url);
      // curl_setopt($ch, CURLOPT_POST, true);
      // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

      // // Agrega la cadena de consulta al final de la URL
      // curl_setopt($ch, CURLOPT_URL, $url . '?' . $query_string);

      // // Ejecuta la solicitud y obtiene la respuesta
      // $response = curl_exec($ch);

      // // Cierra la sesión de cURL
      // curl_close($ch);

      $chollospublicados = mysqli_fetch_assoc(mysqli_query($conn,"SELECT `chollospublicados` FROM $tableKeepa WHERE `CANAL` LIKE '$global'"));
      $chollospublicados = $chollospublicados['chollospublicados'] + 1;  
      unset($chollo);
      unset($product);
      // echo "</br> Antes de eliminar el primer valor ".var_dump($chollos);
      // echo "</br> Despues de eliminar el primer valor ".var_dump($chollos);
      $insert = mysqli_query($conn,"UPDATE $tableKeepa SET `ASINS`='$chollos', `revisado`='1', `chollospublicados`='$chollospublicados', `IDLASTMESSAGE`='$id_lastmessage' WHERE `CANAL` LIKE '$global'");


      // echo "</br> Volvemos a pasar la array de chollos a un string para guardarlo en la BBDD: ".$chollos;
      
      $chollos = explode(',',$chollos);
      break;
    

    }
    else {    

      echo "YA PUBLICADA: ".$chollo."</br>";
      // echo "</br> Antes de eliminar el primer valor ".var_dump($chollos);
      if (is_array($chollos)) {
        $chollos = implode(',', $chollos);
      }
      // Supongamos que tienes una cadena llamada $chollos
      if (substr($chollos, 0, 1) != 'B') {
        $chollos = '';
      }

      $pos = strpos($chollos, ",");
      echo "la pos es: ".$pos."</br>";
      
      if ($pos === false) {
        $chollos = NULL;
      }
     
      $chollos = substr($chollos, $pos + 1);

      // echo "</br> Volvemos a pasar la array de chollos a un string para guardarlo en la BBDD: ".$chollos;

      $insert = mysqli_query($conn,"UPDATE $tableKeepa SET `ASINS`='$chollos' WHERE `CANAL` LIKE '$global'");
      $chollos = explode(',',$chollos);

    }


    }

    }
  }


//codigo por si no funciona el envio a telegram
// add_filter( 'wptelegram_p2tg_current_user_has_permission', '__return_true' );

//Bucle para publicar todos los artículos de Amazon



