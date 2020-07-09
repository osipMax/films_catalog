/* load page by $_GET['id'] */
var params = window
    .location
    .search
    .replace('?', '')
    .split('&')
    .reduce(
        function (p, e) {
            var a = e.split('=');
            p[decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
            return p;
        }, {}
    );
console.log(params['name']);
var url = 'php/api.php?name=' + params['name'];
$.ajax(url, {
    success: function (data) {
        let data_mas = JSON.parse(data)[0];
        /* poster */
        let poster = document.querySelector('img.film-descr-img');
        poster.src = data_mas['Poster'];
        /* ru-title */
        let ru_title = document.querySelector('p.film-title');
        ru_title.innerHTML = data_mas['ru-title'];
        /* orig-title */
        let orig_title = document.querySelector('p.film-mini-title');
        orig_title.innerHTML = params['name'];
        /* time */
        let time = document.querySelector('p.time');
        time.innerHTML = data_mas['Duration'];
        /* year */
        let info = document.querySelectorAll('div.info-data');
        info[0].innerHTML = data_mas['Year'];
        /* country */
        info[1].innerHTML = data_mas['Country'];
        /* genre */
        info[2].innerHTML = data_mas['Genre'];
        /* director */
        info[3].innerHTML = data_mas['Director'];
        /* production */
        info[4].innerHTML = data_mas['Production'];
        /* premier */
        info[5].innerHTML = data_mas['Premier'];
        /* boxOffice */
        info[6].innerHTML = data_mas['BoxOffice'];
        /* description */
        /* <div class="film-descr"> */
        let descr = document.querySelector('div.film-description');
        url = 'php/api.php?name=' + params['name'] + '&descr=1';
        $.ajax(url, {
            success: function (data) {
                let data_mas = JSON.parse(data);
                descr.innerHTML = data_mas;
            }
        });
    }
}).then(function () {
    /* trailer-button active */
    let place = document.querySelector('.yt');
    $.ajax('php/api.php?type=1&name=' + params['name'], {
        success: function (data) {
            place.outerHTML = "<iframe class='yt' width='790' height='450' src=" + data;
        }
    });
});

function ytTrailerClick(event) {
    if (!event.classList.contains("yt-trailer-clicked")) {
        event.classList.add("yt-trailer-clicked");
        event.parentElement.childNodes[3].classList.remove("yt-watch-clicked");
        let place = document.querySelector('.yt');
        $.ajax('php/api.php?type=1&name=' + params['name'], {
            success: function (data) {
                place.outerHTML = "<iframe class='yt' width='790' height='450' src=" + data;
            }
        });
    }
}

function ytWatchClick(event) {
    if (!event.classList.contains("yt-watch-clicked")) {
        event.classList.add("yt-watch-clicked");
        event.parentElement.childNodes[1].classList.remove("yt-trailer-clicked");
        let place = document.querySelector('.yt');
        $.ajax('php/api.php?type=2&name=' + params['name'], {
            success: function (data) {
                place.outerHTML = "<iframe class='yt' width='790' height='450' src=" + data;
            }
        });
    }
}




// MINI-SLIDER

var width = 80;
var slides = document.querySelectorAll('.slide');
var slides_count = slides.length;
let slider = [];

for (let i = 0; i < slides.length; i++) {
    slider[i] = slides[i].src;
    slides[i].remove;
}

let step = 0;
let offset = 4;

function draw() {
    let img = document.createElement('img');
    img.src = slider[step];
    img.classList.add('slide');
    img.style.zIndex = step;
    img.innerHTML = '';
    img.style.left = offset * width + 'px';

    let mas = document.querySelector('.mini-slider');

    mas.appendChild(img);
    if (step + 1 == slider.length) {
        step = 0;
    } else {
        step++;
    }
    offset = 4;
}

var butt = document.querySelector('.slider-arrow');
butt.onclick = left;


function left() {
    let slides3 = document.querySelectorAll('.slide');
    let offset2 = 0;
    for (let i = 0; i < slides3.length; i++) {
        slides3[i].style.left = offset2 * width - width + 'px';
        offset2++;
    }
    slides3[0].remove();
    draw();
}

draw();
draw();
butt.onclick = left;