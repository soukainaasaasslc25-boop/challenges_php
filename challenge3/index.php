<?php
$score = 0;
$interpretation = "";
$modepass = "";
$messageSpecial = ""; 

if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["password"])){ 
    $modepass = $_POST['password'];
    $listeNoire = array("123456", "0000", "password", "admin", "123456789", "1111", "modepass", "12345678");
     $speciaux = array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '-', '=', '[', ']', '{', '}', ';', ':', '\'', '"', '\\', '|', ',', '.', '<', '>', '/', '?');
        

    if (in_array($modepass, $listeNoire)) { 
       $score = 0;
       $interpretation = "Faible";
    }
    else{
        $score = 0;
        if(strlen($modepass) >= 8){
            $score += 20;
        }
        if(strlen($modepass) >= 12){
            $score += 10;
        }
        if(preg_match('/[A-Z]/', $modepass)){
            $score += 15;
        }else{
            $messageSpecial = "Ajoutez au moins un caractère en Majiscule : ";
        }
        if(preg_match('/[a-z]/', $modepass)){
            $score += 15;
        }else{ $messageSpecial = "Ajoutez au moins un caractère en Miniscule : ";}

        if(preg_match('/\d/', $modepass)){
            $score += 15;
        }else{ $messageSpecial = "Ajoutez  au moins 1 chiffre  : ";}
        
       
        $contientSpecial = false;
        foreach ($speciaux as $caractere) {
            if (strpos($modepass, $caractere) !== false) {
                $contientSpecial = true;
                break;
            }
        }
        
        if ($contientSpecial) {
            $score += 15;
        } else {
            $messageSpecial = "Ajoutez au moins un caractère spécial parmi : " . implode(' ', $speciaux);
        }
        
        if (!preg_match('/\s/', $modepass)) { 
            $score += 10;
        }else{
             $messageSpecial = " modePasse doit  contient pas d’espace: ";
        }
        
        if ($score >= 0 && $score <= 39) {
            $interpretation = "Faible";
        } elseif ($score >= 40 && $score <= 69) {
            $interpretation = "Moyen";
        } elseif ($score >= 70 && $score <= 89) {
            $interpretation = "Bon";
        } elseif ($score >= 90 && $score <= 100) {
            $interpretation = "Très fort";
        }
    }
}  
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérificateur de mot de passe</title>
   
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="password">Password :</label>
        <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($modepass); ?>">
        <button type="submit">Vérifier</button>
    </form>
    
    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?> 
        <div>
            <p>Score : <?php echo $score; ?> / 100</p>
            <p>Niveau : <?php echo $interpretation; ?></p>
            
            <?php if (!empty($messageSpecial)): ?>
                <div class="message-special">
                    <?php echo $messageSpecial; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    
    
</body>
</html>