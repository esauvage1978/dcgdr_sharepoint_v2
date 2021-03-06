/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
import 'icheck/skins/all.css';
import 'summernote/dist/summernote-bs4.min.css';
import 'select2/dist/css/select2.min.css';
import 'select2-bootstrap-theme/dist/select2-bootstrap.min.css';

import "hover.css";

//adminLte https://github.com/kevinpapst/AdminLTEBundle/blob/5af0b6cb66f709504b529e96d3d27741336ca220/Resources/docs/extend_webpack_encore.md
require('../../vendor/kevinpapst/adminlte-bundle/Resources/assets/admin-lte');

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
const $ = require('jquery');


//iCheck
require('icheck');

$('input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' // optional
});


// Summernote
require('summernote');

$('.textarea').summernote({
    lang: 'fr-FR'
});


//JSTREE
require('jstree');
require('jstree/dist/themes/default/style.min.css');

var tree = true;


// DATATABLES
require('datatables.net-bs4');
require('datatables.net-bs4/css/dataTables.bootstrap4.min.css');


// SELECT2
require('select2');
$('.select2')
    .removeClass('form-control')
    .select2({
        language: "fr",
        width: "100%"
    }).addClass('col');


