var data = JSON.parse(document.querySelector('.js_user_data').innerText);
var state = {
    scrolled: 0,
};

var child = document.querySelector('.post-list').lastElementChild;
      while (child) {
        document.querySelector('.post-list').removeChild(child);
        child = document.querySelector('.post-list').lastElementChild;
      }

var i = 0;

data.posts.forEach(element => {
    // console.log(element);
    var new_post = document.createElement('a');
    new_post.classList.add('post');
    new_post.href = `/?another_pseudo=${element.pseudo}`;
    // new_post.classList.add('js_modal');
    // new_post.href = `#modal${element.id}`; //a modifier
    new_post.innerHTML = `
  <img src="${element.path}" alt="image">`;
    document.querySelector('.post-list').appendChild(new_post);
    i++;
  });


  document.querySelector('.post-list').insertAdjacentHTML('beforeend',
  `<div>

  </div>
  <div class="contain-loader">
    <div class="nb-spinner">
  
    </div>
  </div>
  <div>`);

//load following data
window.onscroll = function(ev) {
  if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight) {
    this.state.scrolled++;
    var request = new XMLHttpRequest();
    var id = this.state.scrolled;
    request.open("GET", `/?next_gallery=${id}`);
    request.onload = function() {
      if (request.status === 200) {
        //   console.log(request.responseText);
          var response = JSON.parse(request.responseText);
        // console.log(response);
        if (response !== 'end') {
            //delete loader again:
        var child = document.querySelector(".post-list").lastElementChild;
        for (var i = 0; i < 3; i++) {
          document.querySelector(".post-list").removeChild(child);
          child = document.querySelector(".post-list").lastElementChild;
        }
        response.posts.forEach(element => {
            // console.log(element);
            var new_post = document.createElement('a');
            new_post.classList.add('post');
            new_post.href = `/?another_pseudo=${element.pseudo}`;
            // new_post.classList.add('js_modal');
            // new_post.href = `#modal${element.id}`; //a modifier
            new_post.innerHTML = `
          <img src="${element.path}" alt="image">`;
            document.querySelector('.post-list').appendChild(new_post);
          });
          document.querySelector('.post-list').insertAdjacentHTML('beforeend',
            `<div>

            </div>
            <div class="contain-loader">
              <div class="nb-spinner">

              </div>
            </div>
            <div>`);
        } else {
            var child = document.querySelector(".post-list").lastElementChild;
            for (var i = 0; i < 3; i++) {
              document.querySelector(".post-list").removeChild(child);
              child = document.querySelector(".post-list").lastElementChild;
            }
            window.onscroll = function (){};
        }
        
      } else {
        console.log( "while fetching data error Hapennnnnnnned : status is " + request.status);
      }
    };
    request.send();
  }
};

if (document.querySelector('.js_takeMeToMe') !== null){
  document.querySelector('.js_takeMeToMe').addEventListener('click', () => {
    window.location.replace('/');
  });
}

document.querySelector('.take_me_to_editing').addEventListener('click', function () {
  var babtou = new XMLHttpRequest;
  babtou.open('GET', '/?set_montage=true');
  babtou.onload = () => {
    if (babtou.status === 200) {
      if (babtou.responseText === 'OK') {
        window.location.replace('/');
      } else {
        alert('an error happened');
      }
    } else {
      console.log('ERROR OCCURED while requesting from back with status ' + babtou.status);
    }
  };
  babtou.send();
});

/**
     * 
     * DISCONNECt
     */
    document.querySelector('.js_takeMeToConnect').addEventListener('click', function (){
      var request = new XMLHttpRequest();
      request.open('GET', '/?disconnect_me=true');
      request.onload = function () {
        if (request.status === 200) {
          if (request.responseText === 'OK'){
            window.location.replace('/');
          } else {
            alert('QQCH n\'a pas marche en tentant de vous deconnecter, veuillez reesayer');
          }
        } else {
          console.log('une petite erreur est survenue mon coquin ' + request.status);
        }
      };
      request.send();
    });