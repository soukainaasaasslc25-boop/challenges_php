<?php

    echo "votre totale intiale est".totaleinitital($qte,$prix);
    echo"Pourcentage total de réduction appliqué".totaleReduction();
    echo"Montant de la réduction".MantantReduction($totalinitial,$qte,$prix,$promo,$code,$totaleReduction);
    echo "Frais de livraison  ".ModeLivraison($totaleReduction,$promo,$totalinitial);
    echo "Total final ";
