<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Fuel and Distance Calculator</title>
        <style>
            #stats {
                text-align: center;
            }
            .names {
                text-align: left;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="Model/Connection.js"></script>
        <script src="Model/Entity.js"></script>
        <script src="Model/EntityGraph.js"></script>
        <script src="Model/Globals.js"></script>
        <script src="Calculate.js"></script>
        <script>
            $(document).ready(function () {
                //hide elements until loaded
                $(".legend").hide();
                $("#source").hide();
                $("#stats").hide();
                $("#system_table").hide();
                eg = new EntityGraph(); //create new EntityGraph object. See EntityGraph for more details
                load_systems();
                $("#system_select").click(function () {
                    $(document).off("click.generate"); //keep double event firing from happening on system change
                    $("#source").hide();
                    $("#stats").hide();

                    //clear tables
                    $("#system_table tr").not(".head").remove();
                    $("#stats tr").not(".head").remove();
                    //eg.clearGraph(); //clears the EntityGraph of all entities
                    var sys_id = $("#system_list").val();

                    //Get all the details for the current system need to create the graph connections
                    $.get("/DB_Access/system_detail/graph_info.php", {
                        sys_id: sys_id
                    }, function (data, status) {
                        build_graph(eg, data);
                        $(".legend").show();
                        $("#source").show();
                        $("#system_table").show();
                        append_sources(eg);
                        //creates the distance and fuel stats table.
                        $(document).on("click.generate", "#generate", function () { generate(eg); });
                    });
                });
            });
        </script>
        <?php
            $path = $_SERVER['DOCUMENT_ROOT'];
            $path .= "/header.php";
            include $path;
        ?>
    </head>
    <body>
        <select id="system_list"></select>
        <button id="system_select">Select</button><br>
        <table id="system_table">
            <tr class="head">
                <td>Source</td>
                <td>Destination</td>
                <td>Type</td>
            </tr>
        </table>
        <br>
        <p class="legend">IP: InterPlanet</p>
        <p class="legend">IM: InterMoon</p>
        <div id="source"><select id="source_select"></select><button id="generate">Generate</button></div><br>
        <table id="stats">
            <tr class="head">
                <td>Destination</td>
                <td>Least Distance</td>
                <td>Turns at speed 3</td>
                <td>Turns at speed 4</td>
                <td>Turns at speed 5</td>
                <td>Tank Cost</td>
                <td>Fuel Cost</td>
            </tr>
        </table>
    </body>
</html>
