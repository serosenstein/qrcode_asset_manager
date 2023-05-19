<?php

// Set the path where the JSON file will be saved
$file_path = "color-templates.json";

// Load existing templates
$templates = [];
if (file_exists($file_path)) {
    $templates = json_decode(file_get_contents($file_path), true);
}

// Check if the user submitted the form to create a new template
if (isset($_POST["create_template"])) {
    // Get the submitted data
    $tag = $_POST["tag"];
    $tag = str_replace(' ', '_', $tag);
    #convert all to lower
    $tag = strtolower($tag);
    $foreground = $_POST["foreground"];
    $background = $_POST["background"];
    $is_default = isset($_POST["default"]);

    // Check if a template with the same tag name already exists

    if (array_key_exists($tag, $templates)) {
        echo "A template with the tag name '$tag' already exists.";
    } else {
        // Create the new template
        $template = [
            "foreground" => $foreground,
            "background" => $background,
            "default" => $is_default,
        ];
        // Add it to the list of templates
        $templates[$tag] = $template;
        // If this is the default template, unset the default flag for other templates
        if ($is_default) {
            foreach ($templates as &$t) {
                if ($t !== $template) {
                    $t["default"] = false;
                }
            }
        }
        // Save the updated list of templates
        file_put_contents($file_path, json_encode($templates));
        echo "Template saved successfully.";
    }
}

// Check if the user submitted the form to update/delete a template
if (isset($_POST["action"])) {
    // Get the submitted data
    $tag = $_POST["tag"];
    $action = $_POST["action"];
    #make sure that tag doesn't have any space and replace it with underscores
    $tag = str_replace(' ', '_', $tag);
    #convert all to lower
    $tag = strtolower($tag);

    // Check if the template exists
    if (array_key_exists($tag, $templates)) {
        if ($action === "update") {
            // Get the updated data
            $foreground = $_POST["foreground"];
            $background = $_POST["background"];
            $is_default = isset($_POST["default"]);

            // Update the template
            $template = $templates[$tag];
            $template["foreground"] = $foreground;
            $template["background"] = $background;
            $template["default"] = $is_default;
            $templates[$tag] = $template;
            // If this is the default template, unset the default flag for other templates
            if ($is_default) {
                foreach ($templates as &$t) {
                    if ($t !== $template) {
                        $t["default"] = false;
                    }
                }
            }
            // Save the updated list of templates
            file_put_contents($file_path, json_encode($templates));
            echo "Template updated successfully.";
        } elseif ($action === "delete") {
            // Delete the template
            unset($templates[$tag]);
            // Save the updated list of templates
            file_put_contents($file_path, json_encode($templates));
            echo "Template deleted successfully.";
        }
    } else {
        echo "Template with tag name '$tag' does not exist.";
    }
}

?>

<!-- Create new template form -->
<!DOCTYPE html>
        <html>
        <head>
        <link rel="stylesheet" href="style.php" media="screen">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        <ul>
          <li><a href="add_device.php">Add Device</a></li>
            <li><a href="config.php">Settings</a></li>
		<li><a href="qrcodes_tags.php">Tag Colors</a></li>
		<li><a href="#" id="myBtn">Advanced Search</a></li>
              <form action="qrcodes_search.php" method="post">
                <input type="text" name="quick_search" placeholder="Quick Search..." name="search">
                  <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
            </ul>
<h2>Add/Create New Color Template</h2>
<form method="post">
    <label for="tag">Tag name:</label>
    <input type="text" id="tag" name="tag" required>
    <br>
    <label for="foreground">Foreground color:</label>
    <input type="color" id="foreground" name="foreground" value="#000000" required>
    <br>
    <label for="background">Background color:</label>
	<input type="color" id="background" name="background" value="#FFFFFF" required>
	<br>
	<label for="default">Default:</label>
	<input type="radio" id="default" name="default">
	<br>
	<button type="submit" class="button" name="create_template">Add Color Template</button>
</form>
<!-- List existing templates -->
<h2>Existing Templates</h2>
<?php if (empty($templates)) : ?>
    <p>No templates exist yet.</p>
        <div id="myModal" class="modal">
                  <!-- Modal content -->
                  <div class="modal-content">
                                <span class="close">&times;</span>
                <div class="section" >
                        <h2>Advanced Search<br>(empty values will return all)</h2>
                <form action="qrcodes_search.php" method="post">
                        Device Name (partial match)<br><input type="text" name="device_name"><br>
                        Device Details (partial match)<br><input type="text" name="device_details"><br>
                        Device ID (exact match)<br><input type="text" name="device_id"><br>
                        <br>
                        <input type="submit" class="button" value="Search">
                        <br><br><br>
                </form>
                </div>
                                      </div>

        </div>

        <script>
                // Get the modal
                 var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
          modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
          modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
          if (event.target == modal) {
                      modal.style.display = "none";
                    }
}
        </script>
<?php else : ?>
        <?php foreach ($templates as $tag => $template) : ?>
            <li class="templatedisplay">
                Name: <strong><?= $tag ?></strong>
                <form method="post" class="templatedisplay" style="display: inline;">
                    <input type="hidden" name="tag" value="<?= $tag ?>">
                    <input type="hidden" name="action" value="update">
                    <label for="foreground">Foreground color:</label>
                    <input type="color" id="foreground" name="foreground" value="<?= $template["foreground"] ?>" required>
                    <label for="background">Background color:</label>
                    <input type="color" id="background" name="background" value="<?= $template["background"] ?>" required>
                    <label for="default">Default:</label>
                    <input type="radio" id="default" name="default" <?= $template["default"] ? "checked" : "" ?>>
                    <button type="submit">Update</button>
                </form>
                <form method="post" class="templatedisplay" style="display: inline;">
                    <input type="hidden" name="tag" value="<?= $tag ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this template?')">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
        <div id="myModal" class="modal">
                  <!-- Modal content -->
                  <div class="modal-content">
                                <span class="close">&times;</span>
                <div class="section" >
                        <h2>Advanced Search<br>(empty values will return all)</h2>
                <form action="qrcodes_search.php" method="post">
                        Device Name (partial match)<br><input type="text" name="device_name"><br>
                        Device Details (partial match)<br><input type="text" name="device_details"><br>
                        Device ID (exact match)<br><input type="text" name="device_id"><br>
                        <br>
                        <input type="submit" class="button" value="Search">
                        <br><br><br>
                </form>
                </div>
                                      </div>

        </div>

        <script>
                // Get the modal
                 var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
          modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
          modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
          if (event.target == modal) {
                      modal.style.display = "none";
                    }
}
        </script>
<?php endif; ?>
