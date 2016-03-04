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
        <script>
            $(document).ready(function () {
                //hide elements until loaded
                $(".legend").hide();
                $("#source").hide();
                $("#stats").hide();
                $("#system_table").hide();
                eg = new EntityGraph(); //create new EntityGraph object. See EntityGraph for more details
                $("#system_list").load("/DB_Access/system_detail/system_list.php");
                $("#system_select").click(function () {
                    $(document).off("click.generate"); //keep double event firing from happening on system change
                    $("#source").hide();
                    $("#stats").hide();

                    //clear tables
                    $("#system_table tr").not(".head").remove();
                    $("#stats tr").not(".head").remove();
                    eg.clearGraph(); //clears the EntityGraph of all entities
                    var sys_id = $("#system_list").val();

                    //Get all the details for the current system need to create the graph connections
                    $.get("/DB_Access/system_detail/graph_info.php", {
                        sys_id: sys_id
                    }, function (data, status) {
                        var rows = data.split(";");
                        for (var i = 0; i < rows.length - 1; i++) {
                            var row = rows[i].split(",");

                            //Add planet/moon surfaces to the graph (where applicable)
                            if (row[2] == "true") {
                                if (row[1] == "true") {
                                    eg.addConnectedPair(row[0], row[0] + " Surface", "A");
                                } else {
                                    eg.addConnectedPair(row[0], row[0] + " Surface", "NA");
                                }
                            }
                            if (row[5] == "true") {
                                if (row[4] == "true") {
                                    eg.addConnectedPair(row[3], row[3] + " Surface", "A");
                                } else {
                                    eg.addConnectedPair(row[3], row[3] + " Surface", "NA");
                                }
                            }
                            eg.addConnectedPair(row[0], row[3], row[6]);
                            $("#system_table").append("<tr><td>" + row[0] + "</td><td>" + row[3] + "</td><td>" + row[6] + "</td></tr>");
                            $(".legend").show();
                            $("#source").show();
                        }
                        $("#system_table").show();
                        for (var i in eg.entities) {
                            $("#source_select").append("<option value='" + eg.entities[i].name + "'>" + eg.entities[i].name + "</option>");
                        }

                        //creates the distance and fuel stats table.
                        $(document).on("click.generate", "#generate", function () {
                            var source = $("#source_select").val();
                            var e_source = eg.getEntityByName(source);
                            console.log(eg.entities.length);
                            for (var i in eg.entities) {
                                console.log(eg.entities[i].name);
                                if (eg.entities[i].name == source) {
                                    continue;
                                }
                                var ld = eg.leastDistance(e_source, eg.entities[i]);
                                var lf = eg.leastFuelCost(e_source, eg.entities[i]);
                                var t3 = Math.ceil(ld / 3);
                                var t4 = Math.ceil(ld / 4);
                                var t5 = Math.ceil(ld / 5);
                                $("#stats").append("<tr><td class='names'>" + eg.entities[i].name + "</td><td>" + ld +
                                    "<td>" + t3 + "</td><td>" + t4 + "</td><td>" + t5 + "</td>" +
                                    "</td><td>" + lf + "</td><td>" + fuelcost[lf - 1] + "</td></tr>");
                            }
                            $("#stats").show();
                        });
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
