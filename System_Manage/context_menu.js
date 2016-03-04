function planet_selected(id) {
    var name = $("#label_" + id).text();
    clear_menu();
    menu_post(name + " selected. choose location");
    menu_post_html("<br><br><button id='e_id_" + id + "' class='delete_entity'>Delete " + name + "</button>")
}

function clear_menu() {
    $("#context_menu").empty();
}

function menu_post(item) {
    $("#context_menu").append("<p>" + item + "</p>");
}

function menu_post_html(text) {
    $("#context_menu").append(text);
}