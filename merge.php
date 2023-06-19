<?php
var_dump($_POST['submit']);
var_dump($_POST['new_branch_name']);
var_dump($_POST['branch1']);
var_dump($_POST['branch2']);

/** 
 * Fonction récursive pour supprimer un répertoire et son contenu
 * @param string $dir Chemin vers le répertoire
 * @return bool True si la suppression est réussie, sinon False
 */
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmdir($dir);
}

if (isset($_POST['submit'])) {


    $repository_path = "/apache/htdocs/exalogv428/bmohammad/FinalProject"; // Chemin du référentiel Git
    $user = "bmohammad";
    $repo_url = "https://webhook:Nzc3MDM2ODM5NzQzOjKx+GyGOlxvpdcLT0yBtfmtX7QO@bitbucket.fr.exalog.net/scm/bankx/bankx-sandbox.git";
    $bankx_sandbox_path = $repository_path . "/bankx-sandbox";
    
    if(is_dir($bankx_sandbox_path)){
        echo" <br> <h1> Le répertoire existe déjà</h1>";
    }else{

        $command_clone = "git clone $repo_url ";
        $output_clone = shell_exec($command_clone);
        var_dump($output_clone);

    }

    
    $repository_path .= "/bankx-sandbox";
    if (!is_dir($repository_path)){
        echo"<h2> Le répertoire cloné n'existe pas.</h2>";
        exit;
    }else{
        echo"<h2> Le répertoire a bien été cloné.</h2>";
    }
    
    if(isset($_POST['delete_clone'])){
        if (is_dir($repository_path)) {
            // Supprimer le répertoire et son contenu récursivement
            $success = deleteDirectory($repository_path);
            if ($success) {
                echo "Répertoire cloné supprimé avec succès.";
            } else {
                echo "Une erreur s'est produite lors de la suppression du répertoire cloné.";
            }
        } else {
            echo "Le répertoire cloné n'existe pas.";
        }
    }

    chdir($repository_path);

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

        /*if (empty($output_check_branch)) {*/

            $command_remote_branch = " git ls-remote --refs $repo_url | awk '{print $2}' | sed 's/refs\/heads\///' | sed 's/refs\/tags\///'";
            $output_remote_branch = shell_exec($command_remote_branch);

            $command_check_branch1 = "git checkout $branch1";
            $output_check_branch1 = shell_exec($command_check_branch1);
            var_dump($output_check_branch1);

            $command_check_branch2 = "git checkout $branch2";
            $output_check_branch2 = shell_exec($command_check_branch2);
            var_dump($output_check_branch2);

            $command_check_base = "git merge-base $branch1 $branch2";
            $output_check_base = shell_exec($command_check_base);
            if(!empty(trim($output_check_base))){
                // Basculer vers la nouvelle branche-
                $command_checkout_branch = "git checkout -b $new_branch_name";
                $output_checkout_branch = shell_exec($command_checkout_branch);
                var_dump($output_checkout_branch);

                $command_merge = "git merge --no-ff --no-commit $branch1 $branch2";
                $output_merge = shell_exec($command_merge);
                var_dump($output_merge);
            }else{
                echo "Il n'y a pas d'ancêtre commun dans les branches que vous avez sélectionnés";
            }
            
            // Effectuer le commit des modifications de fusion
            $commit_message = 'Commit de fusion de '.$branch1.' avec '.$branch2;
            $command_commit = "git commit -am '$commit_message'";
            $command_commit .= " --quiet"; // Utiliser l'option --quiet pour supprimer la sortie non désirée
            echo " commit : $command_commit";
            
            // Exécuter la commande avec la fonction exec
            exec($command_commit, $output_commit, $return_code);
            var_dump($return_code);
            echo " return : $return_code";
            
            if ($return_code === 0) {
                echo "Le commit a été effectué avec succès.";
            } else {
                echo "Une erreur s'est produite lors de l'exécution du commit.";
            }

            // Afficher les résultats
            echo "<h2>Nouvelle branche '$new_branch_name' créée et activée.</h2>";
            echo "<h2>Commits :</h2>";
            echo "<pre>$output_log</pre>";
        /*} else {
            echo "<h2>La branche '$new_branch_name' existe déjà.</h2>";
        }*/

        $command_push = "git push $repo_url --set-upstream $new_branch_name";
        var_dump($command_push);
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