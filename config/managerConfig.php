<?php
//Set parameters for dissplay errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Default OPTIONS for sort function
const MAIN_COLUMN_FOR_SORT			  = 'last';
const SECONDARY_COLUMN_FOR_SORT		  = 'first';
const MAIN_COLUMN_SORT_DIRECTION 	  = 'ASC';
const SECONDARY_COLUMN_SORT_DIRECTION = 'ASC';

//Default variables for pagination function
const OFFSET 					   = 0; //offcet for mysql_query
const ROWS_ON_PAGE 				   = 4; //number rows
const NUMBER_DISPLAYED_PAGES_LINKS = 2; //the maximum number of displayed pages
const DEFAULT_PAGE 				   = 1; //default page