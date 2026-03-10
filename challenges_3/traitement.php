<?php

// Fonction de calcul du tarif de livraison
function tarifLivraison($depart, $arrivee, $typeEnvoi, $poids) {
    $tarifBase = 20; 
    $tarifFinal=$tarifBase;
    if ($poids > 5) {
        $tarifFinal += ($poids - 5) * 5;
    }
   
    if ($typeEnvoi === "Express") {
        $tarifFinal += $tarifBase* 0.2; 
    }
      if ($depart !== $arrivee) {
           $tarifFinal += $tarifBase* 0.5; 
    }
      
   
    
    return round($tarifFinal, 2); 
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $depart = $_POST['depart'] ?? '';
    $arrivee = $_POST['arrivee'] ?? '';
    $typeEnvoi = $_POST['typeEnvoi'] ?? '';
    $poids = $_POST['poids'] ?? '';
    
    $erreurs = [];
    
    // Validation
    if (empty($depart)) {
        $erreurs[] = "Ville de départ invalide";
    }
    if (empty($arrivee)) {
        $erreurs[] = "Ville d'arrivée invalide";
    }
    if (empty($typeEnvoi)) {
        $erreurs[] = "Type d'envoi invalide";
    }
    if (empty($poids) || !is_numeric($poids) || $poids <= 0) {
        $erreurs[] = "Le poids doit être un nombre strictement positif";
    }
    
   
    if (empty($erreurs)) {
        
        $poids = floatval($poids);
        $tarif = tarifLivraison($depart, $arrivee, $typeEnvoi, $poids);
        
        // Affichage 
        echo '<h2>Devis de livraison</h2>';
        echo '<p><strong>Ville de départ :</strong> ' . htmlspecialchars($depart) . '</p>';
        echo '<p><strong>Ville d\'arrivée :</strong> ' . htmlspecialchars($arrivee) . '</p>';
        echo '<p><strong>Poids :</strong> ' . htmlspecialchars($poids) . ' kg</p>';
        echo '<p><strong>Livraison :</strong> ' . htmlspecialchars($typeEnvoi) . '</p>';
        echo '<p><strong>Total à payer :</strong> ' . htmlspecialchars($tarif) . ' DH</p>';
        
    } else {
        // Affichage des erreurs
        echo '<strong>Erreurs de validation :</strong><br>';
        foreach ($erreurs as $erreur) {
            echo '• ' . htmlspecialchars($erreur) . '<br>';
        }
    }
    
} else {
    echo 'Aucune donnée reçue. Veuillez remplir le formulaire.';
}

?>