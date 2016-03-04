function display() {
    var sys_id = $("#system_list").val();
    if (!has_star(sys_id)) {
        $("#star_submit").click(function () {
            var star = $("#star_select").val();
            $("#star_div").hide();
            $("#system").hide()
            $("#status").show();
            console.log(sys_id + " " + star);
            $.post("/DB_Access/system_updates/set_star.php", {
                sys_id: sys_id,
                star: star
            }, function () {
                $("#status").hide();
                display_system(sys_id);
            });

        });
        $("#star_select").load("/DB_Access/entity_list.php", function () {
            $("#star_div").show();
        });
    } else {
        //$("#status").show();
        display_system(sys_id);
    }
}

function display_system(sys_id) {
    $("#system").hide();
    $("#status").show();
    $("#entities").click(function () {
        $("#entity_menu").empty();
        $("#entity_menu").load("/DB_Access/system_detail/entity_img_list.php");
    });
    $("#connectors").click(function () {
        $("#entity_menu").empty();
        $("#entity_menu").append("<img src='/Assets/solid.png' id='solid' class='connector_img'/><br>");
        $("#entity_menu").append("<img src='/Assets/dotted.png' id='dotted' class='connector_img'/>");
    });
    $("#system").load("/DB_Access/system_detail/system_view.php?sys_id=" + sys_id, function (data, status) {
        $("#selection_menu").show();

        //get connection details
        $.get("/DB_Access/system_detail/connection_info.php?sys_id=" + sys_id, function (data, status) {

            var temp = data.split("|");
            var plist = temp[0].split(",");
            var details = temp[1].split(";");
            for (var i = 0; i < details.length - 1; i++) {
                var values = details[i].split(",");
                var dashstyle = "4 2";
                if (values[3] === "IP") {
                    dashstyle = "1 0";
                }

                jsPlumb.connect({
                    source: values[1],
                    target: values[2],
                    parameters: { "c_id": values[0] },
                    anchor: "AutoDefault",
                    connector: ["Bezier", {curviness:30}],
                    endpoint: "Blank",
                    paintStyle: { dashstyle: dashstyle, lineWidth: 5, strokeStyle: "white" }
                });
            }
            //$("._jsPlumb_endpoint").remove();
            for (var i = 0; i < plist.length - 1; i++) {
                var id = plist[i];

                jsPlumb.draggable(id, {
                    stop: function (e, ui) {
                        var x = ui.position.left;
                        var y = ui.position.top;
                        var id = $(this).attr("id");
                        $.post("/DB_Access/system_updates/position_update.php", {
                            p_id: id,
                            x: x,
                            y: y
                        });
                    }
                });
            }

        });
        $("#status").hide();
        $("#system").show();
    });
    
}

function has_star(sys_id) {
    var response = $.ajax({
        type: "GET",
        url: "/DB_Access/has_star.php?sys_id=" + sys_id,
        async: false
    }).responseText;
    if (response === "true") {
        return true;
    } 
    return false;
}