<!DOCTYPE html>
<html>
<head>
    <title>Fusion de branches Git</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="style2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        .center-form {
            text-align: center;
            margin: 0 auto;
            max-width: 600px;
        }

        .branch-selector {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .branch-selector .delete-button {
            margin-left: 10px;
            color: red;
            cursor: pointer;
        }
    </style>
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
</head>
<body>
    <?php
    function getBranches() {
        $repo_url = "https://webhook:Nzc3MDM2ODM5NzQzOjKx+GyGOlxvpdcLT0yBtfmtX7QO@bitbucket.fr.exalog.net/scm/bankx/bankx-sandbox.git";
        $command = "git ls-remote --refs $repo_url | awk '{print $2}' | sed 's/refs\\/heads\\///' | sed 's/refs\\/tags\\///'";
        $output = shell_exec($command);
        $branches = explode("\n", trim($output));
        return $branches;
    }
    ?>

    <div class="container">
        <div class="image">
            <img src="exalog.png" alt="Logo" class="logo">
        </div>
        <h1>Fusion de branches Git</h1>
        <div class="add-button-container">
            <button onclick="addBranchSelector()">+</button>
        </div>
        <form action="merge.php" method="POST" target="_blank" class="center-form">
            <div class="branch-container">
                <label for="branches[]">Branche 1:</label>
                <select name="branches[]" id="branches[]" class="searchable-select" required>
                    <?php
                    $branches = getBranches();
                    foreach ($branches as $branch) {
                        echo "<option value=\"$branch\">$branch</option>";
                    }
                    ?>
                    <option value="other_branch">Autre branche</option>
                </select><br><br>
            </div>
            <div id="branch-selectors" class ="branch-selectors-container"></div>

            <label for="new_branch_name">Nouvelle branche:</label>
            <input type="text" name="new_branch_name" id="new_branch_name" placeholder="Insérer le nom de la nouvelle branche" style="width: 300px;" required><br><br>

            <input type="checkbox" name="delete_clone" id="delete_clone">
            <label for="delete_clone">Supprimer le clone du référentiel après la fusion</label><br><br>

            <input type="submit" name="submit" value="Fusionner les branches">
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        var branchCounter = 1;

        $(document).ready(function() {
            $(".searchable-select").select2();

            $("#branches\\[\\]").change(function() {
                if ($(this).val() == "other_branch") {
                    addBranchSelector();
                    $(this).val("");
                }
            });
        });

        function addBranchSelector() {
            var selector = document.createElement("div");
            selector.classList.add("branch-selector");

            var branchLabel = document.createElement("label");
            branchLabel.textContent = "Branche " + (branchCounter + 1) + ":";

            var select = document.createElement("select");
            select.name = "branches[]";
            select.classList.add("searchable-select","branch-select", "branch-label");

            var branches = <?php echo json_encode($branches); ?>;
            for (var i = 0; i < branches.length; i++) {
                var option = document.createElement("option");
                option.value = branches[i];
                option.text = branches[i];
                select.appendChild(option);
            }

            var deleteButton = document.createElement("i");
            deleteButton.classList.add("fas", "fa-times", "delete-button");
            deleteButton.onclick = function() {
                deleteBranchSelector(selector);
            };

            selector.appendChild(branchLabel);
            selector.appendChild(select);
            selector.appendChild(deleteButton);
            selector.appendChild(document.createElement("br"));
            selector.classList.add("branch-selector");
            document.getElementById("branch-selectors").appendChild(selector);

            $(".searchable-select").select2();

            branchCounter++;
        }

        function deleteBranchSelector(selector) {
            selector.remove();
        }
    </script>
</body>
</html>








