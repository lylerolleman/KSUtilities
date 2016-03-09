function EntityGraph() {
    this.entities = [];
    this.getEntityByName = function(name) {
        for (i in this.entities) {
            if (this.entities[i].name == name) {
                return this.entities[i];
            }
        }
        return null;
    };
    this.resetGraph = function () {
        for (i in this.entities) {
            this.entities[i].visited = false;
        }
    }
    this.clearGraph = function () {
        this.entities.length = 0;
        console.log(this.entities.length);
        console.log(this.entities);
    }

    //Checks to see if the graph already has that entity, and adds it if it doesn't
    //Creates an undirected connection with two directed connections
    this.addConnectedPair = function (n1, n2, type) {
        var e1 = this.getEntityByName(n1);
        var e2 = this.getEntityByName(n2);
        if (e1 == null) {
            e1 = new Entity(n1);
            this.entities.push(e1);
        }
        if (e2 == null) {
            e2 = new Entity(n2);
            this.entities.push(e2);
        }
        e1.addConnection(e2, type);

        //hack to handle atmosphere costs
        if (type == "A") {
            type = "NA";
        }
        e2.addConnection(e1, type);
    };

    //TODO: Roll the recurive function back into this one
    //no longer need global reset
    this.leastFuelCost = function (e1, e2) {
        var lfc = leastFuelCost_r(e1, e2);
        this.resetGraph();
        return lfc;
    };
    //see least distance below
    var leastFuelCost_r = function (e1, e2) {
        e1.visited = true;
        var fuelcosts = [];
        for (i in e1.connections) {
            if (e1.connections[i].dest.visited) {
                continue;
            }
            if (e1.connections[i].dest.name === e2.name) {
                fuelcosts.push(e1.connections[i].fuelcost);
            } else {
                fuelcosts.push(e1.connections[i].fuelcost + leastFuelCost_r(e1.connections[i].dest, e2));
            }
        }
        e1.visited = false;
        //console.log(e1.name + " " + e2.name + " " + fuelcosts);
        return Math.min.apply(Math, fuelcosts);
    }

    //TODO: Roll the recurive function back into this one
    //no longer need global reset
    this.leastDistance = function (e1, e2) {
        var ld = leastDistance_r(e1, e2);
        this.resetGraph();
        return ld;
    }

    /*
    * In short: basically a variant of dijkstra's algorithm that doesn't care
    * about a global knowledge of the graph, only needs a start and end node
    * 
    * In long: starts by setting it's current location to visited to prevent infinite loops
    * creates an empty array of the current set of transit costs for that node along each path to end node
    * Proceeds to loop along every neighbour that node has, skipping it if it's seen it before
    * if it has a connection to the end node, it pushes the cost and moves on to the next iteration. Note, it DOES NOT
    * set visited, as this would prevent it from finding other (possibly better) paths. 
    * It then pushes the cost to the next node plus the least distance cost to destination of the next node (recursive)
    * on the backtrace, it resets the visited flag. This prevents seeing a "bad" path from interfering with a "good" one
    * returns the minimum distance to target it has seen
    */
    var leastDistance_r = function (e1, e2) {
        e1.visited = true;
        var transitcosts = [];
        for (i in e1.connections) {
            if (e1.connections[i].dest.visited) {
                continue;
            }
            if (e1.connections[i].dest.name === e2.name) {
                transitcosts.push(e1.connections[i].transitcost);
            } else {
                transitcosts.push(e1.connections[i].transitcost + leastDistance_r(e1.connections[i].dest, e2));
            }
        }
        e1.visited = false;
        //console.log(e1.name + " " + e2.name + " " + transitcosts);
        return Math.min.apply(Math, transitcosts);
    }
}