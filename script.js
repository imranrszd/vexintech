let loader = document.getElementById('preloader');

window.addEventListener('load', function () {
    loader.style.display = 'none';
    loader.style.transition = '.5s';
})

// search bar
var searchInput = document.querySelector('.search-container');
let searchContainer = document.getElementById('scontain');
let body = document.getElementById('body');

function toggleSearch() {
    searchInput.classList.toggle('active');
    if (view.classList.contains('active')) {
        viewiPhone();
    } if (cartSbar.classList.contains('active')) {
        viewCart();
    } if (profile.classList.contains('active')) {
        viewProfile();
    }
    searchContainer.style.backdropFilter = 'blur(2px)';
    body.style.overflow = body.style.overflow === 'hidden' ? 'visible' : 'hidden';
}

var search = document.querySelector('.search');
var result = document.getElementById('sresult');
var searchClose = document.getElementById('close');
var searchNoInput = document.getElementById('no-input');

search.addEventListener('click', function () {
    result.style.height = '60%';
    searchNoInput.style.opacity = '1';
})
search.addEventListener('input', function () {
    searchNoInput.style.visibility = 'hidden';
})
searchClose.addEventListener('click', function () {
    result.style.height = '0';
    searchNoInput.style.visibility = 'visible';
    searchNoInput.style.opacity = '0';
})

// slide under nav //

var view = document.getElementById('view');
var viewBlur = document.getElementById('viewBlur');
var cartSbar = document.getElementById('cart-sbar');
let profile = document.getElementById('prbox');

function viewiPhone() {
    view.classList.toggle('active');
    if (cartSbar.classList.contains('active')) {
        viewCart();
    } if (profile.classList.contains('active')) {
        viewProfile();
    }
    viewBlur.classList.toggle('blur');
    viewBlur.style.cursor = 'crosshair';
    body.style.overflow = body.style.overflow === 'hidden' ? 'visible' : 'hidden';
}


function viewCart() {
    cartSbar.classList.toggle('active');
    if (view.classList.contains('active')) {
        viewiPhone();
    } if (profile.classList.contains('active')) {
        viewProfile();
    }
    viewBlur.classList.toggle('blur');
    body.style.overflow = body.style.overflow === 'hidden' ? 'visible' : 'hidden';
}


function viewProfile() {
    profile.classList.toggle('active');
    if (view.classList.contains('active')) {
        viewiPhone();

    } if (cartSbar.classList.contains('active')) {
        viewCart();
    }
    viewBlur.classList.toggle('blur');
    body.style.overflow = body.style.overflow === 'hidden' ? 'visible' : 'hidden';
}