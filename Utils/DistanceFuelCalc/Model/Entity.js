function Entity(e_name) {
    this.name = e_name;
    this.connections = [];
    this.visited = false;
    this.addConnection = function (dest, type) {
        this.connections.push(new Connection(dest, type));
    };
}