import $ from 'jquery';
window.$ = window.jQuery = $;

require('./bootstrap');
const axios = require('axios');
window.Vue = require('vue').default;
window.toastr = require('toastr');

require('jquery-mousewheel');
require('malihu-custom-scrollbar-plugin');
require('malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js');

import VueCookies from 'vue-cookies';
Vue.use(VueCookies, { expire: '7d'});

import 'bootstrap';
