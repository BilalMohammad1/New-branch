<!DOCTYPE html>
<html>
<head>
    <title>Fusion de branches Git</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <div class="image">
            <img src="exalog.png" alt="Logo" class="logo">
        </div>
        <h1>Fusion de branches Git</h1>
        <form action="merge.php" method="POST" target="blank">
        
            <label for="branch1">Branche 1:</label>
            <select name="branch1" id="branch1" required>
                <!-- Options pour les branches disponibles -->
                <option value="ctchelidze_BANKX-74700_BANKX-74701_from_version/bankx-v16.15.0-max">ctchelidze_BANKX-74700_BANKX-74701_from_version/bankx-v16.15.0-max</option>
                <option value="other_branch">Autre branche</option>
                <!-- Ajoutez d'autres options pour les branches -->
            </select><br><br>

            <label for="branch2">Branche 2:</label>
            <select name="branch2" id="branch2" required>
                <!-- Options pour les branches disponibles -->
                <option value="athevenet_BANKX-74845_BANKX-74846_from_version/bankx-v16.15.0-max">athevenet_BANKX-74845_BANKX-74846_from_version/bankx-v16.15.0-max</option>
                <option value="other_branch">Autre branche</option>
                <!-- Ajoutez d'autres options pour les branches -->
            </select><br><br>

            <label for="new_branch_name">Nouvelle branche:</label>
            <input type="text" name="new_branch_name" id="new_branch_name" required><br><br>

            <input type="submit" value="Fusionner et obtenir les commits">
        </form>
    </div>
</body>
</html>




