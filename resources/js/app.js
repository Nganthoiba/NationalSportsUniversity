import './bootstrap';
import $ from 'jquery';
//  import './bengaliscript';
// import './hindiscript';

//import XLSX
//import * as XLSX from 'xlsx';

// Import DataTables plugin and its CSS
import DataTable from 'datatables.net';
import './custom.js';
import './hindi-input.js';
//import './excel/xlsx.full.min.js';
import 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';
import IMask from 'imask';
import Swal from 'sweetalert2';
// or via CommonJS
window.Swal = Swal;

/** This ensures jQuery is globally accessible ($ and jQuery), like in older Laravel Mix setups. */
window.$ = window.jQuery = $;

// Register the plugin — this is crucial

//DataTable(window, $);