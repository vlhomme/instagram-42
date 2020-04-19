// (function() {

var state = {
    picture_taken: false,
    video: false,
    upload: false
};

function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
      if ((new Date().getTime() - start) > milliseconds){
        break;
      }
    }
  }

document.querySelector('#deco').addEventListener('click', () => {
    requesto = new XMLHttpRequest();
    requesto.open('GET', '/?disconnect_me=true');
    requesto.onload = function () {
        if (requesto.status === 200) {
            window.location.replace("/");
        } else {
            alert('Probleme de connexion avec le serveur veuillez reessayer');
        }
    };
    requesto.send();
});
    // var video = document.getElementById("video");
    // var VendorUrl = window.URL || window.webkitURL;

    // navigator.getMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
    // navigator.getMedia ({
    //     video: true,
    //     audio: false
    // }, function (stream) {
    //     video.src = VendorUrl.createObjectURL(stream);
    //     video.play();
    // }, function(error) {
    //     //an error occured
    // });
    navigator.mediaDevices.getUserMedia({video:{ width: 640, height: 480 }, audio: false})
    .then(stream => {
        var video = document.getElementById("video");
        video.srcObject = stream;
        video.onloadedmetadata = function(e) {
            video.play();
            state.video = true;
          };
    })
    .catch((e) => {
        // console.log(e.name + ": " + e.message);
        // document.getElementById("video").remove();
        document.querySelector('.picture_preview').insertAdjacentHTML('beforeend', `
        <form enctype="multipart/form-data" action="/" method="post" name="fileupload"><input type="file" name="fichier" class="js_file_upload"><input type="submit" style="display:none" name="valider"></form>
        `);

        document.querySelector('.js_file_upload').addEventListener('input', function(e) {
            // console.log(document.querySelector('.js_file_upload').value);
            state.upload = true;
        });
        //mettre en place des trucs pour que ca marche
    });

// img/ico/sticker_unicorn.png

var list_of_shit = document.querySelectorAll('.filter');
list_of_shit.forEach(element => {
    // console.log(element.parentElement.innerText);
    if (element.parentElement.innerText !== 'aucun') {
        var path_to_sticker = `img/ico/sticker_${element.parentElement.innerText}.png`;
        element.addEventListener('click', () => {
            var tmp = document.querySelectorAll('.sticker');
            tmp.forEach((element, i) => {
                if (i === 1) {
                    if (canvas.style.display !== 'none') {
                        element.innerHTML = `<img src="${path_to_sticker}">`;
                    }
                } else {
                element.innerHTML = `<img src="${path_to_sticker}">`;
                }
            });
        })
    } else {
        element.addEventListener('click', () => {
            document.querySelector('.sticker').innerHTML = ``;
        })  
    }
});

canvas =  document.getElementById('canvas');
context = canvas.getContext('2d');

document.getElementById('capture').addEventListener('click', function(){
    if (state.video === false && state.upload === false){
        alert('Verifiez que votre webcam fonctionne ou veuillez choisir un fichier à upload');
        return;
    }

    if (state.video) {
        state.picture_taken = true;
        context.drawImage(video, 0, 0, 800, 600);
        canvas.style.display = 'initial';
        var tmp = document.querySelectorAll('.sticker');
        if (tmp[0].innerHTML !== '') {
            // tmp[1].innerHTML = tmp[0].innerHTML;
            document.querySelector('#capture').insertAdjacentHTML('afterend', `
            <div class='sticker'>
                <img style="display: none" src="">
            </div>
            `);
            tmp = document.querySelectorAll('.sticker');
            tmp[1].innerHTML = tmp[0].innerHTML;
        }
    } else {

        let formData = new FormData(document.forms.fileupload);

        // console.log(formData);
        request = new XMLHttpRequest();
        request.open('POST', '/');
        request.onload = () => {
            // console.log('caca');
            // console.log(r)
            if (request.status === 200) {
                var response = request.responseText;
                if (response.substr(0, 4) === '/img') {
                    // console.log(response);
                    // document.getElementById("video").insertAdjacentHTML('afterend', `<img class="capturer_limage" src="${response}"/>`)
                    // document.getElementById("video").remove();
                    if (document.getElementById("video")){
                        // document.getElementById("video").insertAdjacentHTML('afterend', `<img class="capturer_limage" src="${response}"/>`);
                        document.getElementById("video").remove();
                        document.querySelector('.sticker').insertAdjacentHTML('afterend', `<img id="capturer_limage" src="${response}"/>`);
                        var yolo = document.getElementById('capturer_limage');
                        yolo.onload = function () {
                            var imgWidth = yolo.width;
                            var imgHeight = yolo.height;
                            canvas.width = imgWidth;
                            canvas.height = imgHeight;
                            context.drawImage(yolo, 0, 0, imgWidth, imgHeight);
                            // canvas.style.display = 'initial';
                            state.picture_taken = true;
                        };
                    } else {
                        // context.canvas.width = yolo.innerWidth;
                        // context.canvas.height = yolo.innerHeight;
                        // context.drawImage(yolo, 0, 0);
                        // document.querySelector('.capturer_limage').remove();
                        // document.querySelector('.sticker').insertAdjacentHTML('afterend', `<img class="capturer_limage" src="${response}"/>`);
                        document.getElementById('capturer_limage').remove();
                        document.querySelector('.sticker').insertAdjacentHTML('afterend', `<img id="capturer_limage" src="${response}"/>`);
                        var yolo = document.getElementById('capturer_limage');
                        yolo.onload = function () {
                        var imgWidth = yolo.width;
                        var imgHeight = yolo.height;
                        canvas.width = imgWidth;
                        canvas.height = imgHeight;
                        context.drawImage(yolo, 0, 0, imgWidth, imgHeight);
                        // canvas.style.display = 'initial';
                        state.picture_taken = true;
                    };
                    }
                    // sleep(3000);
                    

                } else {
                    alert('Un erreur est survenue, veuillez verifier que votre fichier est bien une image de moins de 2MO');
                }
            } else {
                console.log('error : status returned = ' + request.status);
            }

        };
        request.send(formData);

    }

});

document.getElementById('suivant').addEventListener('click', function() {
        // we need to send two things
    // 1. la photo
    // 2. le filtre
    // console.log(canvas.toDataURL());
    // ?filter=${canvas.toDataURL()}
    if (state.picture_taken !== true){
        alert('vous devez d\'abord prendre une photo !');
        return;
    }


    var new_truc = document.createElement('section');
            new_truc.classList.add('post-list');
            new_truc.innerHTML = document.querySelector('.post-list').innerHTML;

    let formData = new FormData();
    formData.append('filter', canvas.toDataURL());
    if (document.querySelector('.sticker').innerHTML === ''){
        formData.append('sticker', '');
    } else {
        formData.append('sticker', document.querySelector('.sticker').firstElementChild.src);
    }
    document.querySelector('body').innerHTML = `
    <div class="wrapper">
    <div class="magic_wand">
                        <img src="img/ico/magic_wand.png" class="ico_magic_wand">
                    </div>
    <div class="cubes">
    <div class="sk-cube sk-cube1"></div>
    <div class="sk-cube sk-cube2"></div>
    <div class="sk-cube sk-cube3"></div>
    <div class="sk-cube sk-cube4"></div>
    <div class="sk-cube sk-cube5"></div>
    <div class="sk-cube sk-cube6"></div>
    <div class="sk-cube sk-cube7"></div>
    <div class="sk-cube sk-cube8"></div>
    <div class="sk-cube sk-cube9"></div>
   </div>
  </div>
  </div>`;
    var request = new XMLHttpRequest();


    request.open('POST', `/`);
    request.onload = function () {
        if (request.status === 200){
            console.log(request.responseText);

            var respoooonse = JSON.parse(request.responseText);
            // console.log(respoooonse);         

            //edit some css

            // var buff = document.querySelector('.post-list').innerHTML;

            


            // sleep(3000);
            document.querySelector('body').innerHTML = `
                <div class="wrapper">
                    <div class="magic_wand">
                        <img src="img/ico/magic_wand.png" class="ico_magic_wand">
                    </div>
                    <div class="picture_preview">
                        
                    </div>
                    <a href="#" id="precedent" class="booth-capture-button">précedent</a>
                    <a href="#" id="suivant2" class="booth-capture-button">suivant</a>
                    <a href="/?go_back=true" id="tout_annuler" class="booth-capture-button">revenir à la navigation</a>
                    <a href="/?disconnect_me=true" id="deco" class="booth-capture-button">Déconnexion</a>
                    <div class="filter_list">
            
                    </div>

                </div>
            `;
            document.querySelector('.filter_list').insertAdjacentElement('afterend', new_truc);
            document.querySelector('.picture_preview').insertAdjacentHTML('afterbegin', `<img src="${respoooonse.limage}">`);
            respoooonse.lesfiltres.forEach((element, i) => {
                // var NAME = 'img/' + element + '.jpg';
                if (i === 0) {
                    document.querySelector('.filter_list').insertAdjacentHTML('afterbegin', `<div class="filter-wrap">
                    <p class="filtername">
                        original
                    </p>
                    <div class="filter">
                        <img src="${respoooonse.limage}">
                    </div>
                </div>
                    `);
                }
                var regex = /_/gi;
                var name = element.substr(4).slice(0,-4).replace(regex, ' ');
                document.querySelector('.filter_list').insertAdjacentHTML('beforeend', `
                <div class='filter-wrap'>
                    <p class='filtername'>${name}</p>
                    <div class="filter"><img src="${element}"></div>
                </div>
                `);
            });
            var filtres = document.querySelectorAll('.filter');
            filtres.forEach(filtre => {
                filtre.addEventListener('click', () => {
                    document.querySelector('.picture_preview').firstElementChild.src = filtre.firstElementChild.src;
                })
            });

            var bla = document.querySelectorAll('.filter-wrap');
            bla.forEach(element => {
                element.style.width = 'unset';
            });

            bla = document.querySelectorAll('.filter');
            bla.forEach(element => {
                element.style.width = 'unset';
            });

            bla = document.querySelectorAll('.filter img');
            bla.forEach(element => {
                element.style.width = 'unset';
            });

            document.querySelector('#precedent').addEventListener('click', () => {
                document.querySelector('html').innerHTML = '';
                window.location.replace("/");
            });
            document.querySelector('#deco').addEventListener('click', () => {
                requesto = new XMLHttpRequest();
                requesto.open('GET', '/?disconnect_me=true');
                requesto.onload = function () {
                    if (requesto.status === 200) {
                        window.location.replace("/");
                    } else {
                        alert('Probleme de connexion avec le serveur veuillez reessayer');
                    }
                };
                requesto.send();
            });
            document.querySelector('#suivant2').addEventListener('click', () => {
                // console.log('u');
                request = new XMLHttpRequest;

                var path_to_picture = document.querySelector('.picture_preview').firstChild.src.substr(22);

                request.open('GET', `/?publish_picture=${path_to_picture}`);
                request.onload = () => {
                    //in the back what happens ? \/
                    // 1 . publish the picture
                    // 2 . unset session['montage']
                    // 3 . send 'OK' to front if everything went fine
                    console.log(request.responseText);
                    window.location.replace('/');
                };
                request.send();
            });

        } else {
            alert('Request failed.  Returned status of ' + request.status);
        }
    };
    request.send(formData);
});
// })();