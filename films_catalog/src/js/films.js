/* PAGE SETTINGS */
/* variables for clicked buttons */
var all = 0;
var news = 0;
var top100 = 0;

/* countries and genres */
var sett = 0;
var count_films = 0;
var count_boost = 30;

/* css settings */
var height = 2300;
var height_start = 2300;
var height_boost = 1910;

// if(sessionStorage.length > 0) {
//   if(sessionStorage.top) {
//     elem.classList.remove('button-3-unclicked');
//     elem.classList.add('button-3-clicked');
//     let child = document.querySelector('.button-3-clicked > div');
//     child.classList.add('button-3-clicked-clicked');
//   }
//   if(sessionStorage.new) {
//     elem.classList.remove('button-2-unclicked');
//     elem.classList.add('button-2-clicked');
//     let child = document.querySelector('.button-2-clicked > div');
//     child.classList.add('button-2-clicked-clicked');
//   }
//   if(sessionStorage.attr) {

//   }
// }

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
var url = 'php/api.php?name=' + params['name'] + '&contains=1';
if(params['name'] != "" && params['name'] != undefined) {
  all = 1;
  $.ajax(url, {
    success: function (data) {
      if (!data.includes("<br />")) {
        $(".film-section").each(function() {
          this.remove();
        });
        createCard(data);
      }
    }
  });
}


/* create film-card on the page */
function createCard(data) {
  let data_mas = JSON.parse(data);
  let card_list = document.querySelector('.films-list');
  data_mas.forEach(key => {
    /* <div class="film-section"> */
    let item = document.createElement('div');
    let info = key['orig-title'];
    item.classList.add('film-section');
    item.onclick = function () {
      location.href = 'film-single.html?name=' + info;
    }
    card_list.appendChild(item);
    /* <img class="film-card"> */
    let image = document.createElement('img');
    image.classList.add('film-card');
    image.src = key['Poster'];
    item.appendChild(image);
    /* <div class="film-descr"> */
    let descr = document.createElement('div');
    descr.classList.add('film-descr');
    item.appendChild(descr);
    /* <div class="line-vert"> */
    let line = document.createElement('div');
    line.classList.add('line-vert');
    descr.appendChild(line);
    /* <div class="film-name-year"> */
    let ny = document.createElement('div');
    ny.classList.add('film-name-year');
    descr.appendChild(ny);
    /* <div class="film-name"> */
    let fname = document.createElement('div');
    fname.classList.add('film-name');
    fname.innerHTML = key['ru-title'];
    ny.appendChild(fname);
    /* <div class="film-year"> */
    let fyear = document.createElement('div');
    fyear.classList.add('film-year');
    fyear.innerHTML = key['Year'] + ", " + key['Genre'];
    ny.appendChild(fyear);
  });
}

function checkEmpty(data) {
  return empty(data);
}

/* create cards on page load */
if(all == 0) {
  $.ajax('php/api.php?all=1', {
    success: function (data) {
      createCard(data);
      count_films += count_boost;
    }
  });
}
else {
  $("button#b-all")[0].classList.remove('button-1-clicked');
  $("button#b-all")[0].classList.add('button-1-unclicked');
  $(".button-1-unclicked>div")[0].classList.remove('button-1-clicked-clicked');
}

/* check clicked buttons and setted parameters */
function checkClicked(more = null) {
  if (more == null) {
    height = height_start;
    if($("button").is(".more-films") === false) {
      $(".more-button").append("<button class='more-films' onclick='moreClicked()'>ЗАГРУЗИТЬ ЕЩЕ</button>");
    }
    $(".films-field").css("height", height + "px");
    count_films = 0;
    var data = 'click=1&new=' + news + '&top=' + top100;
  } else {
    var data = 'click=1&more=' + count_films + '&new=' + news + '&top=' + top100;
  }
  if ($("div").is(".add-button")) {
    $(".add-button").each(function () {
      data += '&' + this.id + '=1';
    });
  }
  $.ajax('php/api.php', {
    data: data,
    success: function (data) {
      if (data.includes("<br />")) {
        $(".more-films").remove();
      } 
      else {
        if (more == null) {
          $(".film-section").each(function () {
            this.remove();
          });
          createCard(data);
          count_films += count_boost;
        }
        else {
          createCard(data);
          count_films += count_boost;
          height += height_boost;
          $(".films-field").css("height", height + "px");
        }
      }
    }
  });
}

/* "More" button clicked */
function moreClicked() {
  checkClicked(1);
  count_films += count_boost;
}

/* button "top-100" clicked */
function top100Click(elem) {
  if (elem.classList.contains("button-3-unclicked")) {
    elem.classList.remove('button-3-unclicked');
    elem.classList.add('button-3-clicked');
    let child = document.querySelector('.button-3-clicked > div');
    child.classList.add('button-3-clicked-clicked');
    top100 = 1;
    sessionStorage.top = 1;
    /* uncluck all */
    $("button#b-all")[0].classList.remove('button-1-clicked');
    $("button#b-all")[0].classList.add('button-1-unclicked');
    $("div#all")[0].classList.remove('button-1-clicked-clicked');
    checkClicked();
  } else {
    elem.classList.remove('button-3-clicked');
    elem.classList.add('button-3-unclicked');
    let child = document.querySelector('.button-3-unclicked > div');
    child.classList.remove('button-3-clicked-clicked');
    top100 = 0;
    delete sessionStorage.top;
    checkClicked();
  }
}
/* button "new" clicked */
function newsClick(elem) {
  if (elem.classList.contains("button-2-unclicked")) {
    elem.classList.remove('button-2-unclicked');
    elem.classList.add('button-2-clicked');
    let child = document.querySelector('.button-2-clicked > div');
    child.classList.add('button-2-clicked-clicked');
    news = 1;
    sessionStorage.new = 1;
    /* uncluck all */
    $("button#b-all")[0].classList.remove('button-1-clicked');
    $("button#b-all")[0].classList.add('button-1-unclicked');
    $("div#all")[0].classList.remove('button-1-clicked-clicked');
    /* check attr */
    checkClicked();
  } else {
    elem.classList.remove('button-2-clicked');
    elem.classList.add('button-2-unclicked');
    let child = document.querySelector('.button-2-unclicked > div');
    child.classList.remove('button-2-clicked-clicked');
    news = 0;
    delete sessionStorage.new;
    checkClicked();
  }
}
/* button "all" clicked */
function allClick(elem) { // кнопка ВСЕ
  if (elem.classList.contains("button-1-unclicked")) {
    elem.classList.remove('button-1-unclicked');
    elem.classList.add('button-1-clicked');
    let child = document.querySelector('.button-1-clicked > div');
    child.classList.add('button-1-clicked-clicked');
    all = 1;
      /* uncluck new */
      if($("button#b-new")[0].classList.contains("button-2-clicked")) {
        $("button#b-new")[0].classList.remove("button-2-clicked");
        $("button#b-new")[0].classList.add("button-2-unclicked");
        $("div#new")[0].classList.remove("button-2-clicked-clicked");
      }
      if($("button#b-top100")[0].classList.contains("button-3-clicked")) {
        $("button#b-top100")[0].classList.remove("button-3-clicked");
        $("button#b-top100")[0].classList.add("button-3-unclicked");
        $("div#top100")[0].classList.remove("button-3-clicked-clicked");
      }
      $("div.add-button").each(function() {
        this.remove();
        let id = this.id;
        $("li#"+id)[0].disabled = false;
      });
      // $("button#b-all")[0].classList.remove('button-1-clicked');
      // $("button#b-all")[0].classList.add('button-1-unclicked');
      // $("div#all")[0].classList.remove('button-1-clicked-clicked');
    checkClicked();
  } else {
    // elem.classList.remove('button-1-clicked');
    // elem.classList.add('button-1-unclicked');
    // let child = document.querySelector('.button-1-unclicked > div');
    // child.classList.remove('button-1-clicked-clicked');
    // all = 0;
    // checkClicked();
  }
}

function but_over(event) { // hover на кнопку 
  if (event.relatedTarget.className != "dropdown-content") {
    let a = document.getElementsByClassName("dropdown-list");
    a[0].classList.remove('disable-hover');
    a[0].classList.add('active-hover');
  }
}

function but_out(event) { // out hover с кнопки
  if (event.target.className != "dropdown-content") {
    let a = document.getElementsByClassName("dropdown-list");
    a[0].classList.remove('active-hover');
    a[0].classList.add('disable-hover');
  }
}

function but_over1(event) { // hover на кнопку 
  if (event.relatedTarget.className != "dropdown-content") {
    let a = document.getElementsByClassName("dropdown-list");
    a[1].classList.remove('disable-hover');
    a[1].classList.add('active-hover');
  }
}

function but_out1(event) { // out hover с кнопки
  if (event.target.className != "dropdown-content") {
    let a = document.getElementsByClassName("dropdown-list");
    a[1].classList.remove('active-hover');
    a[1].classList.add('disable-hover');
  }
}

function onover(event) { // hover на элементы списка
  if (event.relatedTarget.className != "hidden-content") {
    if (event.relatedTarget.className != "dropdown-content") {
      event.target.parentElement.classList.remove('active-hover');
      event.target.parentElement.classList.add('disable-hover');
    }
  }
}

function onout(event) { // hover out с элементов списка
  if (event.relatedTarget.className != "hidden-content") {
    if (event.relatedTarget.className != "dropdown-content") {
      event.target.parentElement.classList.remove('active-hover');
      event.target.parentElement.classList.add('disable-hover');
    }
  }
}

// var films = document.getElementsByClassName(".film-card");
// for(let i=0; i<films.length; i++) {
//   films[i].onclick = function() {

//   }
// }
//////////////////////////////////////////////////////
// click on dropdown-content
var fullLength = 0;
var maxWidth = 950;

function contentClick(event) {
  if(!event.disabled) {
    var elem = document.createElement("div");
    elem.className = "add-button";
    elem.innerText = event.innerText;
    elem.id = event.id;
    var but = document.createElement("button");
    but.className = "front-img";
    but.setAttribute("onclick", "deleteClick(this)");
    but.id = event.id;
    elem.appendChild(but);
    var image = document.createElement("img");
    image.className = "back-img";
    image.src = "img/add_cross.png";
    but.appendChild(image);
    var block = document.getElementsByClassName("add-buttons");
    block[0].appendChild(elem);
    fullLength += elem.offsetWidth;
    event.disabled = true;
    /* uncluck all */
    $("button#b-all")[0].classList.remove('button-1-clicked');
    $("button#b-all")[0].classList.add('button-1-unclicked');
    $("div#all")[0].classList.remove('button-1-clicked-clicked');
    sessionStorage.attr = 1;
    checkClicked();
  }
}

function deleteClick(event) {
  fullLength -= event.parentElement.offsetWidth;
  let id = event.id;
  event.parentElement.remove(event); 
  let label = document.querySelector("li#"+id);
  label.disabled = false;
//   $("#"+id).css("disabled", "false");
  delete sessionStorage.attr;
  checkClicked();
}