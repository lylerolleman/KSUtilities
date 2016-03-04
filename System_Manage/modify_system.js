function delete_entity(p_id) {
    jsPlumb.remove($("#" + p_id));
    var sys_id = $("#system_list").val();
    $.post("/DB_Access/system_updates/entity_delete.php", {
        p_id: p_id,
        sys_id: sys_id
    });
}

function delete_connection(connection) {
    var c_id = connection.getParameter("c_id");
    try {
        jsPlumb.detach(connection);
    } catch (err) {
        console.log("jsPlumb is not happy: " + err); //suppress useless error (seems to work fine in spite of it)
    }
    
    $.post("/DB_Access/system_updates/connector_delete.php", {
        c_id: c_id
    });
}

function create_new_connection(p1, p2, type) {
    switch (type) {
        case "solid":
            type = "IP";
            break;
        case "dotted":
            type = "IM";
            break;
    }
    var sys_id = $("#system_list").val();
    $.post("/DB_Access/system_updates/connector_insert.php", {
        p1: p1,
        p2: p2,
        type: type,
        sys_id: sys_id
    }, function(c_id) {
        var dashstyle = "4 2";
        if (type === "IP") {
            dashstyle = "1 0";
        }
        jsPlumb.connect({
            source: p1,
            target: p2,
            parameters: { "c_id": c_id },
            anchor: "AutoDefault",
            connector: ["Bezier", {curviness:30}],
            endpoint: "Blank",
            paintStyle: { dashstyle: dashstyle, lineWidth: 5, strokeStyle: "white" }
        });
    });
}

function insert_planet(e_id, x, y, sys_id) {
    $.post("/DB_Access/system_updates/entity_insert.php", {
        e_id: e_id,
        x: x,
        y: y,
        sys_id: sys_id
    }, function (data, status) {
        var p_id = data;
        console.log(p_id);
        var name = $("#label_" + e_id).text();
        var style = "'left:" + x + "px;top:" + y + "px;'";
        var img_url = $("#menu_" + e_id).attr("src");
        $("#system").append("<div id='" + p_id + "' class='system_item' style=" + style + "><img src='" + img_url + "' height='50' width='50'/><p class='entity_label'>" + name + "</p></div>");
        jsPlumb.draggable(p_id, {
            stop: function (e, ui) {
                var nx = ui.position.left;
                var ny = ui.position.top;
                var n_id = $(this).attr("id");
                $.post("/DB_Access/system_updates/position_update.php", {
                    p_id: n_id,
                    x: nx,
                    y: ny
                });
            }
        });
    });
}