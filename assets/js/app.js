/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';


//adminLte https://github.com/kevinpapst/AdminLTEBundle/blob/5af0b6cb66f709504b529e96d3d27741336ca220/Resources/docs/extend_webpack_encore.md
require('../../vendor/kevinpapst/adminlte-bundle/Resources/assets/admin-lte.js');

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';
const $ = require('jquery');
require('jstree');
require('jstree/dist/themes/default/style.min.css');

var tree = true;
function initTree(treedata) {
    $('#tree').jstree({
        'core': {
            'data': treedata,
            "check_callback": true
        }
    }).bind("changed.jstree", function (e, data) {
        if (data.node) {
            document.location = data.node.a_attr.href;
        }
    });
}

initTree(treedata);
