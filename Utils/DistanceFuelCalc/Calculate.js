function load_systems() {
    $("#system_list").load("/DB_Access/system_detail/system_list.php");
}

function append_sources(eg) {
    $("#source_select").empty();
    $.ajax({
        url: "/Session_Manage/get_session_var.php",
        type: "GET",
        data: {key:"source"},
        async: false,
        success: function (data, status) {
            for (var i in eg.entities) {
                $("#source_select").append("<option value='" + eg.entities[i].name + "'>" + eg.entities[i].name + "</option>");
            }
        $("#source_select").val(data);
        }
    });
}

function build_graph(eg, data) {
    eg.clearGraph();
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
     }    
}

function generate(eg) {
        $("#stats tr").not(".head").remove();
        var source = $("#source_select").val();
        
        var e_source = eg.getEntityByName(source);
        
        for (var i in eg.entities) {
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
}
