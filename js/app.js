var testArray = ['test', 'test', 'test', 'test'];


/*
	Function creates a new row in to use based on the elements in the
	array that is passed to it.
 */
var createRow = function ( elements )
{
	//Start off with the opening tag to the row.
	var newRow = "<tr>";
	
	//Create the row and add each column data to it.
	for( var i = 0; i < elements.length; i++ )
	{
		newRow = newRow + "<td>" + elements[i]  + "</td>";
	}
	//Close off the row.
	newRow = newRow + "</tr>"
	
	return newRow;
}

/* Adds a new row to the currentPantry table */
var addItem = function ()
{
	$("#currentPantry tr:last").after( createRow(testArray) );
	$("#currentPantry tr:last").hide().fadeIn("slow");
};

/* Fades the row out, and then returns the promise object */
var removeItem = function ()
{
	$("#currentPantry tr:last").fadeOut("slow");
};

/* Attaches the click method to the add button */
$("#add").click(addItem);

/* Attaches the click method to remove button */
$("#remove").click
(
	removeItem().done
	(
		function()
		{
			$("#currentPantry tr:last").remove();
		}
	)
);
