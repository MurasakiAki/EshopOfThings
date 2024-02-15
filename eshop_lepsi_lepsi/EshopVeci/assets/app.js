import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './styles/header.css';
import './styles/main.css';


//console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Burger menu controller
let categories = document.getElementsByClassName("header-categories")[0];
document.getElementById("brgr").addEventListener("click", function(event) {
    event.preventDefault(); //Prevent refresh
    if (categories.style.display === "none") categories.style.display = "block";
    else categories.style.display = "none";
});

document.getElementById("brgr").click();

window.addEventListener('resize', function() {
    if (window.innerWidth > 760) categories.style.display = "block";
    else categories.style.display = "none";
});

