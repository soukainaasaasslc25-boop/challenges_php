<?php
// header("Location: affichage.php");

// function calcule totale 
function totaleinitital($qte,$prix){
    return $qte*$prix;
}

// ana ghadi nstafed men 20% de réduction ======> LE POURCENTAGE DE REDUCTION
// Ghadi tna9asli 200 DH ====> LE MONTANT DE REDUCTION

// function reduction
function MantantReduction($totalinitial,$qte,$promo){

    $pourcentage = 0;
    $montantReduc = 0;

    if($qte>=5){
        $pourcentage += 10;
    }
    if ($totalinitial>=1000) {  
        $pourcentage += 15;
    }
    if ($promo==="DEV10" ) {
      $pourcentage += 10;
    }
    if ($promo==="SUPER20") {
        $pourcentage += 20;
    }
    $montantReduc = $totalinitial * $pourcentage / 100;
    return [$pourcentage, $montantReduc];
    
}


function ModeLivraison($totaleReduction,$promo,$totalinitial){
    if ($promo===" standard") {

       echo"0 dh";
    }
    if ($promo===" express") {
        return $totaleReduction+50;

    }
    if ($totaleReduction>=1500) {
        echo"livraison gratuit";
    }


}

function totaleReduction(){

}

