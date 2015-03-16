Motivation
We are a registration site company so we often deal with building forms. Naturally we can’t hand code all the HTML/CSS/JS/PHP for each form so we build forms dynamically from one array. The below task is a very simplified version of what we do. Successful completion of this task ensures you are knowledgeable of HTML forms and PHP. 

Your Task
Create a PHP page that outputs a valid HTML form dynamically based on fields specified in the following array:

	$fields= array( //form fields
		'fieldname' => array( //fieldname should be mapped to a field in a database
			'type' => 'text', //type of form element, ie. text, select
			'label' => 'field display name', //the label for the field
		),
		'fieldname2' => array( //fieldname should be mapped to a field in a database
			'type' => 'select', //type of form element, ie. text, select
			'label' => 'field 2 display name', //the label for the field
			‘items’ => array(“item1”, “item2”), //dropdown values for the field
		),
		'fieldname3’ => array( //fieldname should be mapped to a field in a database
			'type' => ‘text’, //type of form element, ie. text, select
			'label' => 'field 2 display name', //the label for the field
			‘items’ => array(“item1”, “item2”), //dropdown values for the field
		)
	);

You should iterate the fields array above and print the 3 form fields and a submit button at the end.

The first field should print something like this (feel free to tweak the html to your liking): <label>field display name</label> <input type=“text” name=“fieldname”>


Updating the field’s array’s label, field name should update the HTML printed on the page. If the type of field is select, it should print the items array as the options of the dropdown.


Submitting
Please send your complete PHP code back to us and if you want, you can give us a URL where we can run it.


Bonus points if you want to impress:
1. Allow the user to submit the form and show them the recap of the data they submitted
2. Make a more interesting form by modifying the array


Questions?
Send us an email!
