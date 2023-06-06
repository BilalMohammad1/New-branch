<?php
// Basculer vers le répertoire du dépôt Git
chdir($repository_path);

// Vérifier si la branche de destination existe déjà
$command_check_branch = "git rev-parse --quiet --verify $new_branch_name";
$output_check_branch = shell_exec($command_check_branch);

if (empty($output_check_branch)) {
    // Créer une nouvelle branche à partir de branch1
    $command_create_branch = "git branch $new_branch_name $branch1";
    $output_create_branch = shell_exec($command_create_branch);
    var_dump($output_create_branch);

    // Basculer vers la nouvelle branche
    $command_checkout_branch = "git checkout $new_branch_name";
    $output_checkout_branch = shell_exec($command_checkout_branch);
    var_dump($output_checkout_branch);
    // Fusionner les branches
    $command_merge = "git merge $branch2";
    $output_merge = shell_exec($command_merge);
    var_dump($output_merge);
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
?>
