<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Manage Systems</title>
        <style>
            #selection_menu {
                overflow-y: auto;
                width: 10%;
                height: 500px;
                border: white;
                border-style: solid;
                border-width: 1px;
                position: fixed;
                right: 0;
                bottom:  250px;
            }
            #context_menu {
                overflow-y: auto;
                width: 10%;
                height: 500px;
                border: white;
                border-style: solid;
                border-width: 1px;
                position: fixed;
                left: 0;
                bottom:  250px;
            }
            #system {
                width: 80%;
                height: 1000px;
                margin-top: 90px;
                margin-left: 10%;
                border: white;
                border-style: solid;
                border-width: 1px;
            }
            #results {
                width: 80%;
                margin-left: 10%;
            }
            .entity_img {
                margin: auto;
                width: 60%;
            }
            .system_item {
                position: absolute;
            }
            .entity_label {
                text-align: center;
            }
            td {
                text-align: center;
            }
            .names {
                text-align: left;
            }
        </style>
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jsPlumb/1.4.1/jquery.jsPlumb-1.4.1-all-min.js"></script>
        <script src="display_system.js"></script>
        <script src="modify_system.js"></script>
        <script src="context_menu.js"></script>
        <script src="/Utils/DistanceFuelCalc/Calculate.js"></script>
        <script src="/Utils/DistanceFuelCalc/Model/Connection.js"></script>
        <script src="/Utils/DistanceFuelCalc/Model/Entity.js"></script>
        <script src="/Utils/DistanceFuelCalc/Model/Globals.js"></script>
        <script src="/Utils/DistanceFuelCalc/Model/EntityGraph.js"></script>
        <script>
            //Prompts the user to enter a name for the new system and posts it to server
            function name_prompt() {
                var opt = prompt("Enter name of new system: ");
                if (opt != null) {
                    $.ajax(
                    {
                        url: "/DB_Access/new_system.php",
                        type: "POST",
                        data: {
                            name: opt
                        },
                        async: false,
                        success: function (data) {
                            $("#system_list").append("<option value=" + data + ">" + opt + "</option>");
                        }
                    });
                }
            }

            $(document).ready(function () {
                //Set up stat reporting stuff
                var eg = new EntityGraph();
                $(document).on("click", "#generate", function () { generate(eg); });
                $(document).on("change", "#source_select", function () {
                    var source = $("#source_select").val();
                    $.post("/Session_Manage/set_session_var.php", {
                        key: "source",
                        value: source
                    });
                });

                //Hide components until loaded
                $("#star_div").hide();
                $("#status").hide();
                $("#system_div").hide();
                $("#selection_menu").hide();
                clear_menu();

                //fired when entity deletion button is pressed in context list
                //only visible when entity is selected
                $(document).on("click", ".delete_entity", function () {
                    var id = $(this).attr("id").split("_");
                    var sys_id = $("#system_list").val();
                    id = id[id.length - 1];
                    $.post("/DB_Access/entity_delete.php", {
                        e_id: id,
                        sys_id: sys_id
                    }, function (data, status) {
                        var p_ids = data.split(",");
                        for (var i = 0; i < p_ids.length - 1; i++) {
                            delete_entity(p_ids[i], eg);
                        }
                    });
                    $("#menu_" + id).remove();
                    $("#label_" + id).remove();
                    clear_menu();
                });

                //Loads the current list of system contained within current profile
                //if there are no systems, prompts the user to enter a new one
                $("#system_list").load("/DB_Access/system_detail/system_list.php", function () {
                    $("#system_div").show();
                    if ($("#system_list option").length != 0) {
                        display(eg);
                    } else {
                        name_prompt();
                        display(eg);
                    }
                });
                //Fires when the new system button is pressed
                $("#create_new_sys").click(name_prompt);

                //goes to entity creation page with callback parameter to return here when done
                $("#create_new_entity").click(function () {
                    location.assign("entity_creator.php?cb=system_manage");
                });

                //fires when user selects a new system and redraws the display
                $("#system_list").on("change", function () {
                    display(eg);
                });

                //delete entity
                $(document).on("dblclick", ".system_item", function () {
                    delete_entity($(this).attr("id"), eg);
                });
                //Delete connector
                jsPlumb.bind("dblclick", function (connection, originalEvent) {
                    delete_connection(connection, eg);
                });

                //select image from entity list to place in system canvas
                $(document).on("click", ".entity_img", function () {
                    var temp = $(this).attr("id").split("_");
                    planet_selected(temp[1]);
                    $(document).on("click.systemclick", "#system", function (e) {
                        insert_planet(temp[1], e.pageX, e.pageY, $("#system_list").val(), eg);
                        clear_menu();
                        $(document).off("click.systemclick");
                    });
                });

                //fires when a user selects a connector type and allows them to create new connection
                var PID = {}; //a hack to hold data that needs to be passed into nested event handler
                $(document).on("click", ".connector_img", function () {
                    var sel = $(this).attr("id");
                    $(document).off("click.system");
                    menu_post(sel + " connector selected, choose source");
                    $(document).on("click.source", ".system_item", function () {
                        var p1 = $(this).attr("id");
                        menu_post("choose destination");
                        $(document).off("click.source");
                        $(document).on("click.dest", ".system_item", function () {
                            var p2 = $(this).attr("id");
                            create_new_connection(p1, p2, sel, eg);
                            $(document).off("click.dest");
                            $(document).on("click.system_item", ".system_item", function () {
                                clear_menu();
                                PID.p_id = $(this).attr("id");
                                menu_post_html("<label for='resize'>Enter new size:</label><input id='resize' type='text'>" +
                                    "<button id='resize_button'>Resize</button>");
                                $(document).on("click", "#resize_button", function () {
                                    var size = $("#resize").val();
                                    $("#" + PID.p_id + " img").attr("width", size);
                                    $("#" + PID.p_id + " img").attr("height", size);
                                    $(document).off("#resize_button");
                                    clear_menu();
                                    $.post("/DB_Access/system_updates/entity_resize.php", {
                                        p_id: PID.p_id,
                                        size: size
                                    });
                                });
                            });
                            clear_menu();
                        });
                    });
                });

                //brings up an entity in the context menu to allow for resizing or deleting
                $(document).on("click.system_item", ".system_item", function () {
                    clear_menu();
                    PID.p_id = $(this).attr("id");
                    menu_post_html("<label for='resize'>Enter new size:</label><input id='resize' type='text'>" +
                        "<button id='resize_button'>Resize</button>");
                    $(document).on("click", "#resize_button", function () {
                        var size = $("#resize").val();
                        $("#" + PID.p_id + " img").attr("width", size);
                        $("#" + PID.p_id + " img").attr("height", size);
                        $(document).off("#resize_button");
                        clear_menu();
                        $.post("/DB_Access/system_updates/entity_resize.php", {
                            p_id: PID.p_id,
                            size: size
                        });
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
        <button id="create_new_sys">Create New System</button>
        <button id="create_new_entity">Create New Entity</button><br><br>
        <div id="system_div">
            <label for="system_list">Select System: </label>
            <select id="system_list"></select><br>
        </div>
        <p id="status">Loading...</p>
        <div id="star_div">
            <label for="star_select">Select Star for this System</label>
            <select id="star_select"></select>
            <button id="star_submit">Select</button>
        </div>
        <div id="context_menu"></div>
        <div id="selection_menu">
            <button id="entities">Entities</button>
            <button id="connectors">Connectors</button>
            <div id="entity_menu"></div>
        </div>
        <div id="system"></div>
        <div id="results">
        <div id="source"><select id="source_select"></select><button id="generate">Generate</button></div><br>
        <table id="stats">
            <tr class="head">
                <td class="names">Destination</td>
                <td>Least Distance</td>
                <td>Turns at speed 3</td>
                <td>Turns at speed 4</td>
                <td>Turns at speed 5</td>
                <td>Tank Cost</td>
                <td>Fuel Cost</td>
            </tr>
        </table>
        </div>
    </body>
</html>
