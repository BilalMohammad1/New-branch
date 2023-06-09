<?php
var_dump($_POST['submit']);
var_dump($_POST['new_branch_name']);
if (isset($_POST['submit'])) {
    $repository_path = "/apache/htdocs/exalogv428/bmohammad/Fusion-branche/bankx-sandbox"; // Chemin du référentiel Git

    // Vérifier si le répertoire spécifié est un référentiel Git valide
    if (is_dir($repository_path . "/.git")) {
        // Basculer vers le répertoire du dépôt Git
        chdir($repository_path);

        // Récupérer les valeurs du formulaire
        $branch1 = $_POST['branch1'];
        $branch2 = $_POST['branch2'];
        $new_branch_name = $_POST['new_branch_name'];

        // Vérifier si la branche de destination existe déjà
        $command_check_branch = "git rev-parse --quiet --verify $new_branch_name";
        $output_check_branch = shell_exec($command_check_branch);
        var_dump($output_check_branch);

        if (empty($output_check_branch)) {
            // Créer une nouvelle branche à partir de branch1
            $command_create_branch = "git branch $new_branch_name $branch1";
            $output_create_branch = shell_exec($command_create_branch);
            var_dump($output_create_branch);

            // Basculer vers la nouvelle branche
            $command_checkout_branch = "git checkout $new_branch_name";
            $output_checkout_branch = shell_exec($command_checkout_branch);
            var_dump($output_checkout_branch);

            // Fusionner les branches branch1 et branch2 dans la nouvelle branche
            $command_merge = "git merge $branch1 $branch2";
            $output_merge = shell_exec($command_merge);
            var_dump($output_merge);

            // Effectuer le commit des modifications de fusion
            $commit_message = "Commit de fusion de $branch1 avec $branch2";
            $command_commit = "git commit -am '$commit_message'";
            $output_commit = shell_exec($command_commit);
            var_dump($output_commit);

            // Obtenir les commits entre les branches fusionnées
            $command_log = "git log --oneline $branch1..$branch2";
            $output_log = shell_exec($command_log);
            var_dump($output_log);

            // Afficher les résultats
            echo "<h2>Nouvelle branche '$new_branch_name' créée et activée.</h2>";
            echo "<h2>Commits :</h2>";
            echo "<pre>$output_log</pre>";
        } else {
            echo "<h2>La branche '$new_branch_name' existe déjà.</h2>";
        }
        
        $repo_url = "https://webhook:Nzc3MDM2ODM5NzQzOjKx+GyGOlxvpdcLT0yBtfmtX7QO@bitbucket.fr.exalog.net/scm/bankx/bankx-sandbox.git";
        $command_push = "git push --set-upstream $repo_url $new_branch_name";
        $output_push = shell_exec($command_push);
        var_dump($output_push);
        

        // Vérifier si le push a réussi
        if (strpos($output_push, 'Everything up-to-date') !== false) {
            echo "<h2>Push réussi vers l'origine.</h2>";
        } else {
            echo "<h2>Erreur lors du push vers l'origine.</h2>";
        }
    } else {
        echo "<h2>Chemin du référentiel invalide.</h2>";
    }
}
?>






