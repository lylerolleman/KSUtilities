function Connection(e_dest, type) {
    this.dest = e_dest;
    switch (type) {
        case "IP":
        case "NA":
            this.fuelcost = 1;
            this.transitcost = 1;
            break;
        case "IM":
        case "A":
            this.fuelcost = 0;
            this.transitcost = 1;
            break;
    }
}