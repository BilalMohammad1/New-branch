<?php
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
    $repository_path = "/apache/htdocs/exalogv428/bmohammad/TEST"; // Chemin du référentiel Git
    $user = "bmohammad";
    $repo_url = "https://webhook:Nzc3MDM2ODM5NzQzOjKx+GyGOlxvpdcLT0yBtfmtX7QO@bitbucket.fr.exalog.net/scm/bankx/bankx-sandbox.git";
    $bankx_sandbox_path = $repository_path . "/bankx-sandbox";

    if (is_dir($bankx_sandbox_path)) {
        echo "<br> <h1>Le répertoire existe déjà</h1>";
    } else {
        $command_clone = "git clone $repo_url ";
        $output_clone = shell_exec($command_clone);
    }

    $repository_path .= "/bankx-sandbox";
    if (!is_dir($repository_path)) {
        echo"<h2>Le répertoire cloné n'existe pas.</h2>";
        exit;
    } else {
        echo"<div class ='repertoire'><h2>Le répertoire a bien été cloné.</h2><br></div>";
    }

    if (isset($_POST['delete_clone'])) {
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
        $branches = $_POST['branches'];
        $new_branch_name = $_POST['new_branch_name'];

        // Vérifier si la branche de destination existe déjà
        $command_check_branch = "git rev-parse --quiet --verify $new_branch_name";
        $output_check_branch = shell_exec($command_check_branch);


            // Checkout de la nouvelle branche
            $command_checkout_new_branch = "git checkout -b $new_branch_name";
            $output_checkout_new_branch = shell_exec($command_checkout_new_branch);
            
            if($output_checkout_new_branch === null){

            
                // Fusion de toutes les branches sélectionnées dans la nouvelle branche
                foreach ($branches as $branch) {

                    // Fusion de la branche
                    $command_merge = "git merge --ff-only --no-commit $branch";
                    $output_merge = shell_exec($command_merge);
                }
            }

            $command_checkout_new_branch = "git checkout $new_branch_name";
            $output_checkout_new_branch = shell_exec($command_checkout_new_branch);

            // Commit des modifications de fusion
            $commit_message = 'Commit de fusion de ' . implode(', ', $branches);
            $command_commit = "git commit -am '$commit_message'";
            $command_commit .= " --quiet";

            exec($command_commit, $output_commit, $return_code);

            if ($return_code === 0) {
                // Pousser le commit vers le référentiel distant
                $command_push = "git push origin $new_branch_name";
                $output_push = shell_exec($command_push);

                echo "<h2>Les branches sélectionnées ont été fusionnées avec succès dans la nouvelle branche '$new_branch_name'.</h2>";
            } else {
                echo "<h2>Une erreur s'est produite lors de la fusion des branches.</h2>";
            }
    } else {
        echo "<div class ='message'><h2>Le répertoire spécifié n'est pas un référentiel Git valide.</h2></div>";
    }
}
?>

<style>
    <?php 
    include 'style2.css';
    include 'style.css';
    ?>
</style>





