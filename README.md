# KSUtilities
Some helpful utilities to aid in the production of the Kesslar Syndrome Board Game

This project is currently a WIP. Unimplemented components have been disabled.
**Note, as the project has progressed past the stage requiring rapid and broad changes, 
KSUtilities has been shelved indefinitely.

In the most recent version, all the primary functional components have been rolled into one page

To see it at work, enter a profile name when prompted to display the home page. 
From here, under the Manage category, select System Manage and hit Go

If no systems exist, it will prompt you for a new system name and to select a star for it. Otherwise it will select and display the 
first system in the list. 
To add new entities (planets/moons), in the right menu, click entities and click on the one you want, 
following the instructions in the left context menu. 
Same idea for adding connections between entities
To resize an entity, click on it in the canvas and a resize input wil show up in the context
menu. Enter a size in px and click resize. You may need to move the planet/moon slightly to get the
connections to update. 
To delete anything, double click on it in the canvas. Note, known bug here where stuff doesn't vanish properly
it should still delete from the DB so a page refresh should fix it. 

Below the canvas is the stats table. Select a source (Kerbin Surface most likely) and click generate
At this point, every change you make will perform live updates to this table, recomputing all paths
and costs as it goes. 
