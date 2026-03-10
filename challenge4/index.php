<?php
session_start();

// Initialisation de l'historique
if (!isset($_SESSION['historique'])) {
    $_SESSION['historique'] = [];
}

// Initialisation des variables
$motDePasseGenere = "";
$messageErreur = "";

// Jeux de caractères
$majuscules = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$minuscules = 'abcdefghijklmnopqrstuvwxyz';
$chiffres = '0123456789';
$speciaux = '!@#$%^&*()_+-=[]{};:,.<>?';
$listeNoire = array("123456", "azerty", "password", "admin", "123456789", "qwerty", "motdepasse", "000000", "111111", "12345678", "abcdef", "qwerty123");

// Récupération des choix par défaut
$longueur8_checked = isset($_POST['longueur8']) ? 'checked' : '';
$longueur12_checked = isset($_POST['longueur12']) ? 'checked' : '';
$majuscule_checked = isset($_POST['majuscule']) ? 'checked' : 'checked';
$minuscule_checked = isset($_POST['minuscule']) ? 'checked' : 'checked';
$chiffre_checked = isset($_POST['chiffre']) ? 'checked' : 'checked';
$special_checked = isset($_POST['special']) ? 'checked' : '';
$pas_espace_checked = isset($_POST['pas_espace']) ? 'checked' : 'checked';
$exclure_liste_noire_checked = isset($_POST['exclure_liste_noire']) ? 'checked' : 'checked';

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['generer'])) {
    
    // Récupération des choix utilisateur
    $longueur8 = isset($_POST['longueur8']);
    $longueur12 = isset($_POST['longueur12']);
    $majuscule = isset($_POST['majuscule']);
    $minuscule = isset($_POST['minuscule']);
    $chiffre = isset($_POST['chiffre']);
    $special = isset($_POST['special']);
    $pasEspace = isset($_POST['pas_espace']);
    $exclureListeNoire = isset($_POST['exclure_liste_noire']);
    
    // Détermination de la longueur
    if ($longueur12) {
        $longueurMin = 12;
    } elseif ($longueur8) {
        $longueurMin = 8;
    } else {
        $longueurMin = 8;
    }
    
    // Ajout d'un peu d'aléatoire (+0 à +4 caractères)
    $longueur = rand($longueurMin, $longueurMin + 4);
    
    // Construction du pool de caractères
    $pool = '';
    if ($majuscule) $pool .= $majuscules;
    if ($minuscule) $pool .= $minuscules;
    if ($chiffre) $pool .= $chiffres;
    if ($special) $pool .= $speciaux;
    if (!$pasEspace) $pool .= ' ';
    
    // Si aucun type sélectionné, tout prendre par défaut
    if (empty($pool)) {
        $pool = $majuscules . $minuscules . $chiffres . $speciaux;
        if (!$pasEspace) $pool .= ' ';
    }
    
    // Fonction pour générer le mot de passe
    function genererMotDePasse($longueur, $majuscule, $minuscule, $chiffre, $special, $pasEspace, $exclureListeNoire, $pool, $majuscules, $minuscules, $chiffres, $speciaux, $listeNoire) {
        
        do {
            $motDePasse = '';
            $typesRequis = 0;
            
            // Forcer au moins un caractère de chaque type sélectionné
            if ($majuscule) {
                $motDePasse .= $majuscules[rand(0, strlen($majuscules)-1)];
                $typesRequis++;
            }
            if ($minuscule) {
                $motDePasse .= $minuscules[rand(0, strlen($minuscules)-1)];
                $typesRequis++;
            }
            if ($chiffre) {
                $motDePasse .= $chiffres[rand(0, strlen($chiffres)-1)];
                $typesRequis++;
            }
            if ($special) {
                $motDePasse .= $speciaux[rand(0, strlen($speciaux)-1)];
                $typesRequis++;
            }
            
            // Compléter jusqu'à la longueur souhaitée
            while (strlen($motDePasse) < $longueur) {
                $motDePasse .= $pool[rand(0, strlen($pool)-1)];
            }
            
            // Mélanger le mot de passe
            $motDePasse = str_shuffle($motDePasse);
            
            // Validation des critères
            $validation = true;
            
            if ($majuscule && !preg_match('/[A-Z]/', $motDePasse)) $validation = false;
            if ($minuscule && !preg_match('/[a-z]/', $motDePasse)) $validation = false;
            if ($chiffre && !preg_match('/[0-9]/', $motDePasse)) $validation = false;
            if ($special && !preg_match('/[!@#$%^&*()_+\-=\[\]{};:,.<>?]/', $motDePasse)) $validation = false;
            if ($pasEspace && preg_match('/\s/', $motDePasse)) $validation = false;
            
            // Vérification liste noire
            if ($exclureListeNoire && in_array($motDePasse, $listeNoire)) {
                $validation = false;
            }
            
            // Vérifier que tous les types requis sont présents au moins une fois
            if ($validation) {
                $typesPresent = 0;
                if ($majuscule && preg_match('/[A-Z]/', $motDePasse)) $typesPresent++;
                if ($minuscule && preg_match('/[a-z]/', $motDePasse)) $typesPresent++;
                if ($chiffre && preg_match('/[0-9]/', $motDePasse)) $typesPresent++;
                if ($special && preg_match('/[!@#$%^&*()_+\-=\[\]{};:,.<>?]/', $motDePasse)) $typesPresent++;
                
                if ($typesPresent < $typesRequis) $validation = false;
            }
            
        } while (!$validation);
        
        return $motDePasse;
    }
    
    // Génération du mot de passe
    $motDePasseGenere = genererMotDePasse($longueur, $majuscule, $minuscule, $chiffre, $special, $pasEspace, $exclureListeNoire, $pool, $majuscules, $minuscules, $chiffres, $speciaux, $listeNoire);
    
    // Analyse détaillée
    $analyse = [
        'longueur' => strlen($motDePasseGenere),
        'majuscules' => [],
        'minuscules' => [],
        'chiffres' => [],
        'speciaux' => [],
        'espaces' => 0
    ];
    
    for ($i = 0; $i < strlen($motDePasseGenere); $i++) {
        $char = $motDePasseGenere[$i];
        if (ctype_upper($char)) $analyse['majuscules'][] = $char;
        elseif (ctype_lower($char)) $analyse['minuscules'][] = $char;
        elseif (ctype_digit($char)) $analyse['chiffres'][] = $char;
        elseif ($char == ' ') $analyse['espaces']++;
        else $analyse['speciaux'][] = $char;
    }
    
    // Ajout à l'historique (SANS LE SCORE)
    $nouvelleEntree = [
        'mot_de_passe' => str_repeat('•', strlen($motDePasseGenere)),
        'date' => date('Y-m-d H:i:s'),
        'longueur' => strlen($motDePasseGenere),
        'contient_maj' => preg_match('/[A-Z]/', $motDePasseGenere) ? 'Oui' : 'Non',
        'contient_min' => preg_match('/[a-z]/', $motDePasseGenere) ? 'Oui' : 'Non',
        'contient_chiffre' => preg_match('/[0-9]/', $motDePasseGenere) ? 'Oui' : 'Non',
        'contient_special' => preg_match('/[!@#$%^&*()_+\-=\[\]{};:,.<>?]/', $motDePasseGenere) ? 'Oui' : 'Non'
    ];
    
    array_unshift($_SESSION['historique'], $nouvelleEntree);
    
    // Garder seulement 5 entrées
    $_SESSION['historique'] = array_slice($_SESSION['historique'], 0, 5);
}

// Effacer l'historique
if (isset($_POST['effacer_historique'])) {
    $_SESSION['historique'] = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Générateur de mot de passe</title>
    <!-- <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .card-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .card-body {
            padding: 30px;
        }
        
        .options-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .option-group {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
        }
        
        .option-group h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .option-item {
            margin: 8px 0;
            display: flex;
            align-items: center;
        }
        
        .option-item input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .option-item label {
            cursor: pointer;
            color: #555;
        }
        
        .btn-generer {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin: 20px 0;
        }
        
        .btn-generer:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .resultat-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .mot-de-passe-container {
            background: white;
            border: 2px dashed #667eea;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .mot-de-passe {
            font-size: 28px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #333;
            letter-spacing: 2px;
            word-break: break-all;
        }
        
        .btn-copier {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        
        .btn-copier:hover {
            background: #218838;
        }
        
        .info-badge {
            background: #e9ecef;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
            margin: 2px;
            font-size: 12px;
        }
        
        .historique-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .historique-table th {
            background: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
        }
        
        .historique-table td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .historique-table tr:hover {
            background: #f8f9fa;
        }
        
        .btn-effacer {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        .btn-effacer:hover {
            background: #c82333;
        }
        
        .footer {
            text-align: center;
            color: rgba(255,255,255,0.8);
            margin-top: 20px;
        }
        
        .badge-oui {
            background: #28a745;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
        }
        
        .badge-non {
            background: #dc3545;
            color: white;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
        }
    </style> -->
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>🔐 Générateur de mot de passe</h1>
                <p>Choisissez vos critères et générez un mot de passe</p>
            </div>
            
            <div class="card-body">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="options-grid">
                        <div class="option-group">
                            <h3>📏 Longueur</h3>
                            <div class="option-item">
                                <input type="checkbox" id="longueur8" name="longueur8" <?php echo $longueur8_checked; ?>>
                                <label for="longueur8">8 caractères minimum</label>
                            </div>
                            <div class="option-item">
                                <input type="checkbox" id="longueur12" name="longueur12" <?php echo $longueur12_checked; ?>>
                                <label for="longueur12">12 caractères minimum</label>
                            </div>
                        </div>
                        
                        <div class="option-group">
                            <h3>🔤 Types de caractères</h3>
                            <div class="option-item">
                                <input type="checkbox" id="majuscule" name="majuscule" <?php echo $majuscule_checked; ?>>
                                <label for="majuscule">Majuscules (A-Z)</label>
                            </div>
                            <div class="option-item">
                                <input type="checkbox" id="minuscule" name="minuscule" <?php echo $minuscule_checked; ?>>
                                <label for="minuscule">Minuscules (a-z)</label>
                            </div>
                            <div class="option-item">
                                <input type="checkbox" id="chiffre" name="chiffre" <?php echo $chiffre_checked; ?>>
                                <label for="chiffre">Chiffres (0-9)</label>
                            </div>
                            <div class="option-item">
                                <input type="checkbox" id="special" name="special" <?php echo $special_checked; ?>>
                                <label for="special">Caractères spéciaux</label>
                            </div>
                        </div>
                        
                        <div class="option-group">
                            <h3>⚙️ Options</h3>
                            <div class="option-item">
                                <input type="checkbox" id="pas_espace" name="pas_espace" <?php echo $pas_espace_checked; ?>>
                                <label for="pas_espace">Sans espaces</label>
                            </div>
                            <div class="option-item">
                                <input type="checkbox" id="exclure_liste_noire" name="exclure_liste_noire" <?php echo $exclure_liste_noire_checked; ?>>
                                <label for="exclure_liste_noire">Exclure mots courants</label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" name="generer" class="btn-generer">🎲 GÉNÉRER</button>
                </form>
                
                <?php if (!empty($motDePasseGenere)): ?>
                    <div class="resultat-card">
                        <h3 style="margin-bottom: 15px;">✅ Mot de passe généré</h3>
                        
                        <div class="mot-de-passe-container">
                            <div class="mot-de-passe" id="motDePasseGenere"><?php echo $motDePasseGenere; ?></div>
                            <button class="btn-copier" onclick="copierMotDePasse()">📋 Copier</button>
                        </div>
                        
                        <div style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: center;">
                            <span class="info-badge">📏 <?php echo strlen($motDePasseGenere); ?> caractères</span>
                            <?php if (preg_match('/[A-Z]/', $motDePasseGenere)): ?>
                                <span class="info-badge">🔠 Majuscules</span>
                            <?php endif; ?>
                            <?php if (preg_match('/[a-z]/', $motDePasseGenere)): ?>
                                <span class="info-badge">🔡 Minuscules</span>
                            <?php endif; ?>
                            <?php if (preg_match('/[0-9]/', $motDePasseGenere)): ?>
                                <span class="info-badge">🔢 Chiffres</span>
                            <?php endif; ?>
                            <?php if (preg_match('/[^a-zA-Z0-9]/', $motDePasseGenere)): ?>
                                <span class="info-badge">✨ Spéciaux</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Historique -->
                <div style="margin-top: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h3>📜 Historique (5 derniers)</h3>
                        
                        <form method="post" style="display: inline;">
                            <button type="submit" name="effacer_historique" class="btn-effacer">🗑️ Effacer</button>
                        </form>
                    </div>
                    
                    <?php if (!empty($_SESSION['historique'])): ?>
                        <table class="historique-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mot de passe</th>
                                    <th>Longueur</th>
                                    <th>Caractères</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($_SESSION['historique'] as $index => $entree): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo $entree['mot_de_passe']; ?></td>
                                        <td><?php echo $entree['longueur']; ?></td>
                                        <td>
                                            <?php if ($entree['contient_maj'] == 'Oui'): ?>🔠<?php endif; ?>
                                            <?php if ($entree['contient_min'] == 'Oui'): ?>🔡<?php endif; ?>
                                            <?php if ($entree['contient_chiffre'] == 'Oui'): ?>🔢<?php endif; ?>
                                            <?php if ($entree['contient_special'] == 'Oui'): ?>✨<?php endif; ?>
                                        </td>
                                        <td><?php echo $entree['date']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">Aucun mot de passe généré</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Générateur de mot de passe</p>
        </div>
    </div>
    
    <script>
    function copierMotDePasse() {
        var motDePasse = document.getElementById('motDePasseGenere').innerText;
        navigator.clipboard.writeText(motDePasse).then(function() {
            alert('✅ Mot de passe copié !');
        }).catch(function(err) {
            alert('❌ Erreur lors de la copie');
        });
    }
    </script>
</body>
</html>