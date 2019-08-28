/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
//const $ = require('jquery');

const $ = require('jquery');
//require('jquery-ui');

//require('jquery-mobile');

//AK pokus require("jquery-mobile/js/jquery.mobile.js");

// the bootstrap module doesn't export/return anything
require('bootstrap');
require('bootstrap/dist/css/bootstrap.css');
require('bootstrap-datepicker/dist/js/bootstrap-datepicker.js');
require('bootstrap-datepicker/dist/css/bootstrap-datepicker3.css');
require('bootstrap-datepicker/js/locales/bootstrap-datepicker.sk.js');

//require('autocomplete.js/dist/autocomplete.jquery.min.js');
//require('autocomplete.js/dist/algolia-autocomplete.css');
//require('algolia-autocomplete.js');
require('datatables-bootstrap/js/dataTables.bootstrap.min.js'); 
//require('datatables/media/js/jquery.dataTables.js'); 
var strankuj = require('./pager');
console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
