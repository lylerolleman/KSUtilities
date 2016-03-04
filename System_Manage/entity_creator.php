<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Create New Entity</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#submit").click(function () {
                    var name = $("#name").val();
                    var img_url = $("#img_src").val();
                    var atmo = $("#atmo").attr("checked");
                    var canland = $("#canland").attr("checked");
                    $("#status").text("creating entity...");
                    $.post("/DB_Access/new_entity.php", {
                        name: name,
                        img_url: img_url,
                        atmo: atmo,
                        canland: canland
                    }, function() {
                        var callback = <?php echo "\"" . $_GET["cb"] . "\""; ?>;
                        switch (callback) {
                            case "home":
                                location.assign("/index.php");
                                break;
                            case "system_manage":
                                location.assign("/System_Manage/system_manage.php");
                                break;
                            default:
                                location.assign("/index.php");
                        }
                    });
                });
            });
        </script>
    </head>
    <body>
        <?php
            $path = $_SERVER['DOCUMENT_ROOT'];
            $path .= "/header.php";
            include $path;
        ?>
        <label for="name">Name: </label>
        <input type="text" id="name"><br>
        <label for="img_src">Enter image URL: </label>
        <input type="text" id="img_src"><br>
        <input type="checkbox" id="atmo"><label for="atmo">Has Atmosphere?</label><br>
        <input type="checkbox" id="canland"><label for="canland">Has Surface (can land)?</label><br>
        <button id="submit">Create</button><p id="status"></p>
    </body>
</html>
