<?php
require "function.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $prix = isset($_POST['prix'])? trim($_POST['prix']) :""; 
    $code = isset($_POST['code'])? trim($_POST['code']) :""; 
    $qte = isset($_POST['qte'])? trim($_POST['qte']) :""; 
    $promo = isset($_POST['modeLivraison'])? trim($_POST['modeLivraison']) :""; 

    if (filter_var($prix, FILTER_VALIDATE_FLOAT) !== false  ) {
        echo "Valid float";
    }elseif($prix<=0){
        echo "entrez postitive value";
    }
    else{
        echo "inValid prix";
    }
    if (filter_var($qte, FILTER_VALIDATE_INT) !== false) {
        echo "Valid integer";
    }
    elseif($qte<=0){
        echo "entrez postive value";
    }else{
        echo "inValid qte";
    }
    // if (empty($code)) {
    //     echo "Le code est obligatoir.";

    // }
    if(empty($_POST['modeLivraison'])){
        echo "Please select mode .";
    }
}
?>
