/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

require('jquery');
jQuery.fn.load = function(callback){ $(window).on("load", callback) };
require('./theme/bootstrap.min');

const Swiper = require('swiper').default;
window.Swiper = Swiper;

require('tilt.js');
const WOW_ = require('wowjs');
window.WOW = WOW_.WOW;

import Player from '@vimeo/player';
window.Player = Player;

require('./theme/jquery.typewriter');
require('./theme/scripts');
