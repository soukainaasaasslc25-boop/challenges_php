<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de devis livraison</title>
    
</head>
<body>
    <div class="container">
        <h1>Devis de livraison</h1>
        
        <form action="traitement.php" method="POST">
            
                <label for="depart">Ville de départ *</label>
                <select id="depart" name="depart" required>
                    <option value="">Sélectionnez une ville</option>
                    <option value="Casablanca">Casablanca</option>
                    <option value="Rabat">Rabat</option>
                    <option value="Tanger">Tanger</option>
                </select>
            

          
                <label for="arrivee">Ville d'arrivée *</label>
                <select id="arrivee" name="arrivee" required>
                    <option value="">Sélectionnez une ville</option>
                    <option value="Casablanca">Casablanca</option>
                    <option value="Rabat">Rabat</option>
                    <option value="Tanger">Tanger</option>
                </select>
           

            
                <label>Type d'envoi *</label>
               
                 
                        <input type="radio" name="typeEnvoi" value="Standard" checked required> Standard
                  
                 
                        <input type="radio" name="typeEnvoi" value="Express"> Express
                   
          
         

            
                <label for="poids">Poids (kg) *</label>
                <input type="number" id="poids" name="poids" min="0.1" step="0.1" required>
          

            <button type="submit">Calculer mon devis</button>
        </form>
    </div>
</body>
</html>