{\rtf1\ansi\ansicpg1252\cocoartf1343\cocoasubrtf160
\cocoascreenfonts1{\fonttbl\f0\fswiss\fcharset0 Helvetica;\f1\fnil\fcharset0 Monaco;}
{\colortbl;\red255\green255\blue255;\red0\green45\blue153;}
\margl1440\margr1440\vieww13120\viewh14320\viewkind0
\deftab720
\pard\pardeftab720\sa240

\f0\b\fs36 \cf2 Motivation\
\pard\pardeftab720\sa240

\b0\fs28 \cf0 We are a registration site company so we often deal with building forms. Naturally we can\'92t hand code all the HTML/CSS/JS/PHP for each form so we build forms dynamically from one array. The below task is a very simplified version of what we do. Successful completion of this task ensures you are knowledgeable of HTML forms and PHP. \
\pard\pardeftab720\sa240

\b\fs36 \cf2 \
Your Task
\b0\fs24 \cf0 \
Create a PHP page that outputs a valid HTML form dynamically based on fields specified in the following array:
\f1\fs20 \
\pard\pardeftab720
\cf0 \
	$fields= array( //form fields\
		'fieldname' => array( //fieldname should be mapped to a field in a database\
			'type' => 'text', //type of form element, ie. text, select\
			'label' => 'field display name', //the label for the field\
		),\
		'fieldname2' => array( //fieldname should be mapped to a field in a database\
			'type' => 'select', //type of form element, ie. text, select\
			'label' => 'field 2 display name', //the label for the field\
			\'91items\'92 => array(\'93item1\'94, \'93item2\'94), //dropdown values for the field\
		),\
		'fieldname3\'92 => array( //fieldname should be mapped to a field in a database\
			'type' => \'91text\'92, //type of form element, ie. text, select\
			'label' => 'field 2 display name', //the label for the field\
			\'91items\'92 => array(\'93item1\'94, \'93item2\'94), //dropdown values for the field\
		)\
	);\
\

\f0\fs24 You should iterate the fields array above and print the 3 form fields and a submit button at the end.\
\
The first field should print something like this (feel free to tweak the html to your liking): <label>
\b field display name
\b0 </label> <input type=\'93text\'94 name=\'93
\b fieldname
\b0 \'94>\
\
\
Updating the field\'92s array\'92s label, field name should update the HTML printed on the page. If the type of field is select, it should print the items array as the options of the dropdown.\
\
\
\pard\pardeftab720\sa240

\b\fs36 \cf2 Submitting
\b0\fs24 \cf0 \
\pard\pardeftab720
\cf0 Please send your complete PHP code back to us and if you want, you can give us a URL where we can run it.\
\
\

\b Bonus points if you want to impress:\

\b0 1. Allow the user to submit the form and show them the recap of the data they submitted\
2. Make a more interesting form by modifying the array\
\
\
\pard\pardeftab720\sa240

\b\fs36 \cf2 Questions?\
\pard\pardeftab720

\b0\fs24 \cf0 Send us an email!\
}