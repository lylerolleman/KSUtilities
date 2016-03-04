
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Kessler Syndrome Board Game Utilities</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                $("#profile_create").hide();
                $("#main").hide();
                $.get("/Session_Manage/get_session_var.php?key=profile", function (data, status) {
                    if (status == "nocontent") {
                        $("#profile_create").show();
                    } else {
                        $("#profile_name").text("Profile: " + data);
                        $("#main").show();
                    }
                });
                $("#profile_input").keyup(function(event){
                    if(event.keyCode == 13){
                        $("#profile_submit").click();
                    }
                });
                $("#profile_submit").click(function () {
                    console.log("creating profile...");
                    $.post("/Session_Manage/set_session_var.php",
                    { key: "profile", value: $("#profile_input").val() },
                    function () {
                        console.log("profile created, called back");
                        $("#profile_create").hide();
                        $("#main").show();
                        $("#profile_name").text("Profile: " + $("#profile_input").val());
                    });
                });
                $("#util_select").click(function () {
                    switch ($("#utils").val()) {
                        case "dist-fuel_calc":
                            location.assign("/Utils/DistanceFuelCalc/index.php");
                            break;
                        case "ACTracker":
                            location.assign("/Utils/ActionCardTracker/index.php");
                            break;
                        case "KSCalc":
                            location.assign("/Utils/KSCalc/index.php");
                            break;
                    }
                });
                $("#tool_select").click(function () {
                    switch ($("#tools").val()) {
                        case "science":
                            location.assign("/Deck_Manage/science.php");
                            break;
                        case "action":
                            location.assign("/Deck_Manage/action.php");
                            break;
                        case "systems":
                            location.assign("/System_Manage/system_manage.php");
                            break;
                        case "entities":
                            location.assign("/System_Manage/entity_creator.php?cb=home");
                            break;
                    }
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
        <div id="profile_create">
            <label for="profile_input">Enter Profile Name: </label>
            <input type="text" id="profile_input">
            <button id="profile_submit">Go</button>
        </div>
        <div id="main">
            <p id="profile_name"></p>
            <h4>Utilities:</h4>
            <select id="utils">
                <option value="dist-fuel_calc">Distance/Fuel Calculator</option>
                <!--<option value="ACTracker">Action Card Tracker</option>-->
                <!--<option value="KSCalc">Kessler Syndrome Probability Calculator</option>-->
            </select>
            <button id="util_select">Go</button><br><br>
            <h4>Manage:</h4>
            <select id="tools">
                <option value="systems">Manage Systems</option>
                <option value="entities">Create New Entity</option>
                <!--<option value="science">Manage Science Deck</option>-->
                <!--<option value="action">Manage Action Card Deck</option>-->
            </select>
            <button id="tool_select">Go</button>
        </div>
    </body>
</html>
