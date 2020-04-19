/* recuperer les donnees de l'utilisateur actuel */
var user_actually_connected;
babtoo = new XMLHttpRequest();
babtoo.open('GET', '/?IWANTMYINFO=true');
babtoo.onload = () => {
  if (babtoo.status === 200) {
    user_actually_connected = JSON.parse(babtoo.responseText);
    // console.log(user_actually_connected);

    // public_info
    var user;

    var global_fix = new XMLHttpRequest();
    if (document.querySelector('.is_not_me_uglyfix') !== null && document.querySelector('.is_not_me_uglyfix').innerText.substr(0, 2) === 'NO'){
      valeur = document.querySelector('.is_not_me_uglyfix').innerText.substr(2);
      // console.log(valeur);
      global_fix.open('GET', '/?public_info=' + valeur);
      // global_fix.open('GET', '/?public_info=true');

    } else {
      global_fix.open('GET', '/?public_info=z');
      // console.log('salut');
    }
    global_fix.onload = () => {

      // console.log(global_fix.responseText);
      if (global_fix.responseText === 'User doesn\'t exist') {
        window.location.replace('/?public_info=' + valeur);
      } else {
      var big_brain;
      big_brain = JSON.parse(JSON.parse(global_fix.responseText));
      // console.log(big_brain);
      // console.log(JSON.parse(big_brain));
    var user = big_brain;
      // var user = JSON.parse(document.querySelector('.js_user_data').innerText);
    // var user = JSON.parse(document.querySelector('.js_user_data').innerText);
    // console.log(user);

    if (user.pseudo === user_actually_connected.pseudo) {
      var this_is_me = true;
    }

    /* redirect to connected profile */
    document.querySelector('.js_takeMeToMe').addEventListener('click', () => {
      window.location.replace('/');
    });

    /*update dom with user data*/
    function updateDomWithUserData(user, user_actually_connected) {
      if (user.pseudo === user_actually_connected.pseudo) {
        var this_is_me = true;
      }
      //sabonner
      if (this_is_me) {

        // console.log(user.notif);
        var notif;
        if (user.notif == 1) {
          notif = 'off';
        } else {
          notif = 'on';
        }

        const notification_toggle = function (e) {
          e.preventDefault();
          var value ;
          // console.log('yo');
          document.querySelector('.js_notif').removeEventListener('click', notification_toggle);
          if (e.target.src.includes('on.png')) {
            value = 'nowoff';
          } else {
            value = 'nowon';
          }

          var request = new XMLHttpRequest();
          request.open('GET', '/?notification_toggle=' + value);
          request.onload = function () {
            if (request.status === 200) {
              if (request.responseText === 'OK') {
                if (e.target.src.includes('on.png')) {
                  e.target.src = "img/ico/off.png";
                } else {
                  e.target.src = "img/ico/on.png";
                }
                document.querySelector('.js_notif').addEventListener('click', notification_toggle);
              }
            } else {
              alert('request failed' + request.status);
            }
          };
          request.send();
          
        }

        // document.querySelector('.modif_bouton').remove();
        document.querySelector('.modif_bouton').classList.add('js_change_info');
        document.querySelector('.bouton_style').innerText = 'mes infos';
        // classList.add('bouton_style')
        document.querySelector('.modif_bouton').insertAdjacentHTML('afterend', `<div class="proutttt">
        <p>Notifications :</p>
        <img class="js_notif" src="/img/ico/${notif}.png"/>
      </div>`);
       document.querySelector('.js_notif').addEventListener('click', notification_toggle);
      }

      //pseudo
      var list_pseudo = document.querySelectorAll('.js_pseudo');
      for (var i = 0; i < list_pseudo.length; i++) {
        list_pseudo[i].innerHTML = user.pseudo;
      }

      //prenom

      //nom

      //bio
      var list_bio = document.querySelectorAll('.js_bio');
      for (var i = 0; i < list_bio.length; i++) {
        list_bio[i].innerHTML = user.bio;
      }

      //num posts
      var list_publications = document.querySelectorAll('.js_publications');
      for (var i = 0; i < list_publications.length; i++) {
        list_publications[i].textContent = user.posts.length;
      }

      //profile_pic
      var list_ppic = document.querySelectorAll('.js_profile_pic');
      for (var i = 0; i < list_ppic.length; i++) {
        list_ppic[i].src = user.profile_pic.path;
      }

      //posts
      //remove loader and clear post-list first
      var child = document.querySelector('.post-list').lastElementChild;
      while (child) {
        document.querySelector('.post-list').removeChild(child);
        child = document.querySelector('.post-list').lastElementChild;
      }
      //create all posts
      user.posts.forEach(element => {
        // console.log(element);
        var new_post = document.createElement('a');
        new_post.classList.add('post');
        new_post.classList.add('js_modal');
        new_post.href = `#modal${element.id}`; //a modifier
        new_post.innerHTML = `
      <img src="${element.path}" alt="image">
    <span class="post-overlay">
      <p>
        <span class="post-likes post-likes${element.id}">${element.likes}</span>
        <!-- <span class=post-comments>${element.comment}</span> -->
      </p>
    </span>`;
        document.querySelector('.post-list').appendChild(new_post);

        document.querySelector('.post-list').insertAdjacentHTML('beforebegin', `
  <aside id="modal${element.id}" class="modal" aria-hidden="true" role="dialog" aria-labelledby="titlemodal" aria-modal="false"
      style="display: none">
      <div class="modal-wrapper">
        <div class="transparent-grey">
          <img class="cross_ico" src="/img/ico/cross2.png" />
          <div class="js_stop_propa_modal post_section post_section${element.id}">
            <div class="temporary">
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
            
          </div>
        </div>
      </div>
    </aside>
  `);
      });



      console.log('dom updated');
    };
    updateDomWithUserData(user, user_actually_connected);










    // EVERYTHING FOR POST APPARITION
    //publish comment
    var publishComment = function (e) {
      e.preventDefault();
      var id___post = parseInt(e.target.parentNode.className.substr(48));
      // console.log(id___post);
      var text = document.querySelector('.js_textarea_comment' + id___post).value;
      document.querySelector('.js_textarea_comment' + id___post).value = '';
      // console.log(text);
      // //TO UPDATE : user id
      // var user__id = 3;

      var request = new XMLHttpRequest();
      request.open('GET', `/?comment_post_id=${id___post}&publish_comment=${text}`);
      request.onload = function () {
        if (request.status === 200) {
          // console.log(request.responseText);
          // alert(request.responseText);
          document.querySelector(`.comment_section${id___post}`).innerHTML = `
      <div class="contain-loader">
                  <div class="nb-spinner">
      
                  </div>
                </div>
      `;
          loadComment(id___post);
        } else {
          alert('error, request returned status of ' + request.status);
        }
      };
      request.send();
    }

    //like post
    var likePost = function (e) {
      // var id_post = e.target.parentNode.parentNode.parentNode.className.substr(45);
      var id_post = e.target.className.substr(27);
      var firstparam = document.querySelector('.js_click_like_post' + id_post).getAttribute('src') === 'img/ico/heart.png' ? false : true;
      //supprimer event listener
      document.querySelector('.js_click_like_post' + id_post).removeEventListener('click', likePost);
      //mettre un loader sur le coeur likes
      document.querySelector('.nb_of_like' + id_post).innerHTML = `
  <div class="contain-loader-short">
    <div class="nb-spinner-short">

    </div>
  </div>`;
      var request = new XMLHttpRequest();
      request.open('GET', `/?comment_post_id=${firstparam}&id__post=${id_post}`);
      request.onload = function () {
        if (request.status === 200) {
          // alert(request.responseText);
          number_oflike = JSON.parse(request.responseText);
          // console.log(number_oflike);

          //changer le coeur
          if (firstparam === false) {
            document.querySelector('.js_click_like_post' + id_post).setAttribute('src', "img/ico/heart_red.png");
          } else {
            document.querySelector('.js_click_like_post' + id_post).setAttribute('src', "img/ico/heart.png");
          }

          //changer le nombre de like sur le post et aussi dans le over
          document.querySelector('.nb_of_like' + id_post).innerHTML = '';
          document.querySelector('.nb_of_like' + id_post).innerText = number_oflike.likes + ' mention' + `${(number_oflike.likes) > 1 ? 's' : ''}` + ' J\'aime';
          document.querySelector('.post-likes' + id_post).innerText = number_oflike.likes;

          //remettre event listener
          document.querySelector('.js_click_like_post' + id_post).addEventListener('click', likePost);

        } else {
          alert(request.status);
        }
      }
      request.send();
    }

    //like comment
    var updateComment = function (e) {
      var id = parseInt(e.target.className.substr(17));
      var isliked;
      if (document.querySelector(`.comment_id_${id}`).getAttribute('src') === 'img/ico/heart.png') {
        isliked = false;
        document.querySelector(`.comment_id_${id}`).setAttribute("src", "img/ico/heart_red.png");
      } else {
        isliked = true;
        document.querySelector(`.comment_id_${id}`).setAttribute("src", "img/ico/heart.png");
      }

      // console.log(id);
      var xhr3 = new XMLHttpRequest();

      xhr3.open('GET', `/?comment_post_id=${isliked}&comment_id=${id}`);
      xhr3.onload = function () {
        if (xhr3.status === 200) {
          // alert(xhr3.responseText);
          var post_num = parseInt(document.querySelector(`.comment_id_${id}`).parentNode.parentNode.parentNode.className.substr(31));
          document.querySelector(`.comment_id_${id}`).parentNode.parentNode.parentNode.innerHTML = `
      <div class="comment_section comment_section${post_num}">
                <div class="contain-loader">
                  <div class="nb-spinner">
      
                  </div>
                </div>
              </div>`;
          loadComment(post_num); //problem->it recharges the comment and we don't have any way of seing if user as already liked so it puts back the blank heart
        }
        else {
          alert('Request failed.  Returned status of ' + xhr3.status);
        }
      }
      xhr3.send();
    }

    // LOAD COMMENT
    const loadComment = function (targeted_post) {
      // console.log('hihi ' + targeted_post);
      var xhr2 = new XMLHttpRequest();
      xhr2.open('GET', `/?comment_post_id=${targeted_post}`);
      xhr2.onload = () => {
        if (xhr2.status === 200) {
          //delete loader
          // console.log('inside' + targeted_post);
          document.querySelector('.comment_section' + targeted_post).innerHTML = '';

          //charge comments with loop
          // alert(xhr2.responseText);
          // console.log(xhr2.responseText);
          var comment_arr = JSON.parse(xhr2.responseText);
          // console.log(comment_arr);


          //sort array
          // const amazing_stupid_sort = function (a, b){
          //   return new Date(a.date.substr(0,4), a.date.substr(5,2), a.date.substr(8,2), a.date.substr(11,2), a.date.substr(14,2), a.date.substr(17,2)) - new Date(b.date.substr(0,4), b.date.substr(5,2), b.date.substr(8,2), b.date.substr(11,2), b.date.substr(14,2), b.date.substr(17,2));
          // };
          // comment_arr.sort(amazing_stupid_sort);

          comment_arr.forEach(element => {

            // console.log(element);
            var the_date = [element.date.substr(0, 4), element.date.substr(5, 2), element.date.substr(8, 2), element.date.substr(11, 2), element.date.substr(14, 2), element.date.substr(17, 2)];
            var date_creation_post = new Date(the_date[0], (the_date[1] - 1), the_date[2], the_date[3], the_date[4], the_date[5]);

            if (element.isLiked === true) {
              var heart_path = 'img/ico/heart_red.png';
            } else {
              var heart_path = 'img/ico/heart.png';
            }
            var bitou = `<img class="suppress_comment suppress_comment${element.id}" src="img/ico/cross.png" />`;
            document.querySelector('.comment_section' + targeted_post).insertAdjacentHTML('beforeend', `
            <div class="comment">
                  <div class="container_mini_profile_pic_float">
                    <img class="mini_profile_pic" src="${element.image}" />
                  </div>
                  <div class="comment_content">
                    <p>
                      <a href="/?another_pseudo=${encodeURI(element.pseudo.replace(/;/g, '%3B'))}">${element.pseudo}</a>
                      ${element.content}
                    </p>
                    <div class="stat_comment">
                      <p>
                      ${display_date(date_creation_post)}
                      </p>
                      <p>
                        ${element.likes + ' mention' + `${(element.likes) > 1 ? 's' : ''}` + ' J\'aime'}
                      </p>
                    </div>
                  </div>
                  <div class="comment_button">
                    <img class="heart comment_id_${element.id}" src="${heart_path}" />
                    ${ user_actually_connected.pseudo === element.pseudo ? bitou : ''}
                  </div>
                </div>
            `);
            function callbackhihi(element, targeted_post) {
              return function() {
                // console.log(element);
                // console.log(user_actually_connected);
                var request = new XMLHttpRequest;
                request.open('GET', '/?suppress_comment=' + element.id);
                request.onload = function () {
                  if (request.status === 200) {
                    if (request.responseText === 'OK'){
                      console.log(targeted_post);
                      loadComment(targeted_post);
                    } else {
                      alert("qqch n'a pas marche, veuillez reessayer");
                    }
                  } else {
                    alert('qqch n a pas marche avec la suppression du commentaire : statut retourne = ' + request.status);
                  }
                }
                request.send();
              }
            }
            if (user_actually_connected.pseudo === element.pseudo){
              document.querySelector(`.suppress_comment${element.id}`).addEventListener('click', callbackhihi(element, targeted_post));
            }
            document.querySelector(`.comment_id_${element.id}`).addEventListener('click', updateComment);
          });
        }
        else {
          alert('Request failed.  Returned status of ' + xhr2.status);
        }
      };
      xhr2.send();
    }


    // PROPAGATION
    const stopPropa = (e) => {
      e.stopPropagation();
    };


    // INITIATE VARIABLES
    var data_quote = [];
    let modal = null;

    const openModal = async (e) => {
      //make post appear
      e.preventDefault();
      // console.log(e);
      if (e.target.parentNode.getAttribute('href') !== null) {
        modal = document.querySelector(e.target.parentNode.getAttribute('href'));
        var targeted_post = parseInt(e.target.parentNode.getAttribute('href').substr(6));
        // console.log(targeted_post);
      } else if (e.target.parentNode.parentNode.getAttribute('href') !== null) {
        modal = document.querySelector(e.target.parentNode.parentNode.getAttribute('href'));
        var targeted_post = parseInt(e.target.parentNode.parentNode.getAttribute('href').substr(6));
        // console.log(targeted_post);

      } else {
        modal = document.querySelector(e.target.parentNode.parentNode.parentNode.getAttribute('href'));
        var targeted_post = parseInt(e.target.parentNode.parentNode.parentNode.getAttribute('href').substr(6));
      }
      modal.style.display = null;
      modal.removeAttribute('aria-hidden');
      modal.setAttribute('aria-modal', 'true');

      //charge user data
      setTimeout(() => {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', `/?post_id=${targeted_post}`);
        xhr.onload = () => {
          if (xhr.status === 200) {
            // console.log(xhr.responseText);
            var data = JSON.parse(xhr.responseText);
            // console.log(data);
            //delete loader
            document.querySelector(`.post_section${targeted_post}`).innerHTML = '';
            //format date
            var the_date = [data.post.created.substr(0, 4), data.post.created.substr(5, 2), data.post.created.substr(8, 2), data.post.created.substr(11, 2), data.post.created.substr(14, 2), data.post.created.substr(17, 2)];
            var date_creation_post = new Date(the_date[0], (the_date[1] - 1), the_date[2], the_date[3], the_date[4], the_date[5]);
            //check if user has liked the picture
            if (data.is_liked === true) {
              var heart_path = 'img/ico/heart_red.png';
            } else {
              var heart_path = 'img/ico/heart.png';
            }
            // console.log(document.querySelector(`.post_section${targeted_post}`));
            //and charge modal code
            var sabonner = `<div class="lien_abonnement"> • <a href="">S'abonner</a> </div>`;
            var supprimer = `<div class="second_additional_shit second_additional_shit${targeted_post}">
            <span>supprimer la publication</span>
            <img class="supress_publication" src="img/ico/cross.png" alt="supprimer la publication">
            </span>
            </div>`;
            document.querySelector(`.post_section${targeted_post}`).innerHTML = `
        <div class="media_post_section">
              <img class="post_image" src="${data.post.path}" />
            </div>
            <div class="comment_post_section ArchivoFont">
              <div class="media_owner">
                <div class="container_mini_profile_pic">
                  <img class="mini_profile_pic js_profile_pic" src="${user.profile_pic.path}" />
                </div>
                <div class="pseudo_profile RailewayFont">
                  <a href="/?another_pseudo=${encodeURI(user.pseudo.replace(/;/g, '%3B')) }" class="js_pseudo">${user.pseudo.length >= 12 ? user.pseudo.substr(0, 12) + '...' : user.pseudo}</a>
                </div>
                ${user.pseudo === user_actually_connected.pseudo ? '' : sabonner}
              </div>
              <hr class="separate_comment">
              <div class="comment_section comment_section${targeted_post}">
                <div class="contain-loader">
                  <div class="nb-spinner">
      
                  </div>
                </div>
              </div>
              <hr class="separate_comment">
              <div class="post_stat_section">
                <div class="additional_shit">
                  <img class="ico_post js_click_like_post${targeted_post}" src="${heart_path}" />
                  <img class="ico_post" src="img/ico/comment.png" />
                  <img class="ico_post" src="img/ico/link.png" />
                  <p class="nb_of_like nb_of_like${targeted_post}">
                    ${data.post.likes + ' mention' + `${(data.post.likes) > 1 ? 's' : ''}` + ' J\'aime'}
                  </p>
                  <p class="date_of_post">
                    ${display_date(date_creation_post)}
                  </p>
                </div>
                ${user.pseudo === user_actually_connected.pseudo ? supprimer : ''}
              </div>
              <hr class="separate_comment">
              <div class="">
                <form class="new_comment_container" action="" method="POST">
                  <div class="textarea_publier_commentaire">
                    <textarea name="publier_commentaire" class="textarea_form_publier_commentaire js_textarea_comment${targeted_post}"
                      placeholder="Ajouter un commentaire..."></textarea>
                  </div>
                  <div class="bouton_ou_truc_pour_poster_le_commentaire js-btn${targeted_post}">
                    <button class="bouton_style">Publier</button>
                  </div>
                </form>
              </div>
            </div>
        `;
            //Create a new Request for comment

            if (user.pseudo === user_actually_connected.pseudo) {

            }
            // document.querySelector('.second_additional_shit' + targeted_post).addEventListener('click', suppress_post(data))
            function callbackyo(data){
              return function () {
                // console.log(data);
                var request =  new XMLHttpRequest();
                request.open('GET', `/?suppress_post=${data.post.id}`);
                request.onload = function () {
                  if (request.status === 200) {
                    console.log(request.responseText);
                    if (request.responseText === 'OK'){
                      window.location.replace('/');
                    } else {
                      alert('il y a eu une erreur en tentant de supprimer le post');
                    }
                  } else {
                    alert('erreur en tentant de supprimer le post : statut retourné = ' + request.status);
                  }
                }
                request.send();
              }
            }
            if (user.pseudo === user_actually_connected.pseudo){
            document.querySelector('.second_additional_shit' + targeted_post).addEventListener('click', callbackyo(data))
            }
            document.querySelector('.js_click_like_post' + targeted_post).addEventListener('click', likePost);
            document.querySelector('.js-btn' + targeted_post).addEventListener('click', publishComment);

            loadComment(targeted_post);
          }
          else {
            alert('Request failed.  Returned status of ' + xhr.status);
          }
        };
        xhr.send();
      }, 1000);


      //close modal
      modal.addEventListener('click', closeModal);
      modal.querySelector('.js_stop_propa_modal').addEventListener('click', stopPropa);
    }

    const closeModal = (e) => {
      if (modal === null) return;
      e.preventDefault();
      modal.style.display = "none";
      modal.removeAttribute('aria-hidden');
      modal.setAttribute('aria-modal', 'true');
      modal.removeEventListener('click', closeModal);
      modal = null;
    }

    document.querySelectorAll('.js_modal').forEach(a => {
      a.addEventListener('click', openModal)
    })




    /**
     * 
     * go to montage part
     */
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
     * TAKE ME TO GALLERY 
     */
    document.querySelector('.take_me_to_gallery').addEventListener('click', function() {
      window.location.replace('/?gallery=true');
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


    /**
     * 
     * UPDATE USER DATA
     */

    const stopPropagation = function (e) {
      e.stopPropagation();
  }

  var closeModal2 = () => {
    document.querySelector('.modal1').style.display = 'none';
    document.querySelector('.modal1').removeEventListener('click',closeModal);
    window.location.replace('/');
};


const send_new_pass_oui = function (e) {
  e.preventDefault();
  if (document.querySelector('.alert_new_pass_oui') !== null){
    document.querySelector('.alert_new_pass_oui').remove();
}
if ((document.querySelector('.changer_mon_mdp_new_pass1').value === '' || document.querySelector('.changer_mon_mdp_new_pass2').value === '') || (document.querySelector('.changer_mon_mdp_new_pass1').value !== document.querySelector('.changer_mon_mdp_new_pass2').value)){
  document.querySelector('.changer_mon_mdp_form').insertAdjacentHTML('beforebegin', `<p class="alert_new_pass_oui">Vous devez remplir les deux champs avec une valeur identique</p>`);
  return;
}
let formData = new FormData(document.forms.changer_mon_mdp);
// console.log(formData);
var request = new XMLHttpRequest();
request.open('POST', '/');

request.onload = () => {
  if (request.status === 200) {
    var raiponse = request.responseText;
    if (raiponse === 'OK') {
      // console.log(raiponse);
      document.querySelector('.message_info_temp').innerHTML = '';
      document.querySelector('.message_info_temp').innerHTML = '<p style="padding-top:100px;padding-bottom:100px">Votre mot de passe a ete mis à jour</p>';
      document.querySelector('.js-modal-stop').removeEventListener('click', stopPropagation);
    } else {
      document.querySelector('.changer_mon_mdp_form').insertAdjacentHTML('beforebegin', `<p class="alert_new_pass_oui">${raiponse}</p>`);
      return;
    }
  } else {
    alert('connection failed, please try again');
  }

};

request.send(formData);

};

  const send_new_pass = function (e) {
    e.preventDefault();
    if (document.querySelector('.alert_mes_infos_standard_form') !== null){
      document.querySelector('.alert_mes_infos_standard_form').remove();
  }
  // console.log('prout');
  document.querySelector('.message_info_temp').innerHTML = '';
  document.querySelector('.message_info_temp').innerHTML = `
  <div class="form">
                  <form name="changer_mon_mdp" method="post" action="index.php" class="changer_mon_mdp_form">
                      <p>NOUVEAU MOT DE PASSE <input required type="password" name="new_pass1" class="changer_mon_mdp_new_pass1" value=""></p>
                      <p>CONFIRMER <input required type="password" name="new_pass2" class="changer_mon_mdp_new_pass2" value=""></p>
                      <p class="input"><input class="changer_mot_de_passe_oui" type="submit" value="METTRE À JOUR" /></p>
                  </form>
  `;
  document.querySelector('.changer_mot_de_passe_oui').addEventListener('click', send_new_pass_oui);
  };

  const mettreAJourInfoStandard = function (e) {
    e.preventDefault();
    if (document.querySelector('.alert_mes_infos_standard_form') !== null){
      document.querySelector('.alert_mes_infos_standard_form').remove();
  }
  var mail = document.querySelector('.mail_html_input_mes_infos_standard').value;
  var prenom = document.querySelector('.prenom_html_input_mes_infos_standard').value;
  var pseudo = document.querySelector('.pseudo_html_input_mes_infos_standard').value;
  var nom = document.querySelector('.nom_html_input_mes_infos_standard').value;
  var bio = document.querySelector('.bio_html_input_mes_infos_standard').value;


//   function htmlEntities(str) {
//     return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
// }

  // var bio = htmlEntities(bio);
  console.log(bio);

  var info = {
    'pseudo': pseudo,
    'prenom': prenom,
    'nom': nom,
    'mail': mail,
    'bio': bio
  }
  
  // console.log(info);

  info = JSON.stringify(info);
  console.log(info);

  var request = new XMLHttpRequest();
  request.open('GET', `/?update_standard_info=${info}`);
  request.onload = () => {
    if (request.status === 200) {
      // console.log(request.responseText);
      if (request.responseText === 'OK'){
        document.querySelector('.message_info_temp').innerHTML = '';
        document.querySelector('.message_info_temp').innerHTML = '<p style="padding-top:100px;padding-bottom:100px">Vos infos ont etes mises à jour</p>';
        document.querySelector('.js-modal-stop').removeEventListener('click', stopPropagation);
      } else {
        document.querySelector('.mes_infos_standard_form').insertAdjacentHTML('beforebegin', `<p class="alert_mes_infos_standard_form">${request.responseText}</p>`);
        return;
      }
    } else {
      console.log('problemmmm, request failed u r probably offline');
    }
  };
  request.send();
  }


    var openmodal2 = () => {
      //for displaying the form for changing password
      // console.log(user_actually_connected);
      var get_mail = new XMLHttpRequest();
      var got_mail;
      get_mail.open('GET', `/?get_mail=${user_actually_connected.id}`);
      get_mail.onload = () => {
        if (get_mail.status === 200){
          got_mail = get_mail.responseText;
          // console.log(got_mail);
          document.querySelector('.message_info_temp').innerText = '';
          document.querySelector('.message_info_temp').innerHTML = `
          <div class="form">
                          <form name="forgoten" method="post" action="index.php" class="mes_infos_standard_form">
                              <p>PSEUDO <input required type="text" name="pseudo" class="pseudo_html_input_mes_infos_standard" value="${user_actually_connected.pseudo}"></p>
                              <p>PRENOM <input required type="text" name="prenom" class="prenom_html_input_mes_infos_standard" value="${user_actually_connected.prenom}"></p>
                              <p>NOM <input required type="text" name="nom" class="nom_html_input_mes_infos_standard" value="${user_actually_connected.nom}"></p>
                              <p>MAIL <input required type="text" name="mail" class="mail_html_input_mes_infos_standard" value="${got_mail}"></p>
                              <p>BIO <textarea class="bio_html_input_mes_infos_standard" name="bio">${user_actually_connected.bio}</textarea></p>
                              <p class="input"><input class="mes_infos_standard_button" type="submit" value="METTRE À JOUR" /></p>
                              <p class="input"><input class="change_pass_info" type="submit" value="PLUS D'INFO" /></p>
                          </form>
          `;
          document.querySelector('.modal1').style.display = null;
          
          document.querySelector('.mes_infos_standard_button').addEventListener('click', mettreAJourInfoStandard)
          document.querySelector('.change_pass_info').addEventListener('click', send_new_pass);
          document.querySelector('.modal1').addEventListener('click', closeModal2);
          document.querySelector('.js-modal-stop').addEventListener('click', stopPropagation);
        } else {
          console.log('something wrong happened');
        }
      };
      get_mail.send();

  }
  //<input required type="textarea" name="mail" class="bio_html_input_mes_infos" value="">
  if (document.querySelector('.js_change_info') !== null ){
    document.querySelector('.js_change_info').addEventListener('click', openmodal2);
  }





    // SUPER USEFULL PERSONNAL FUNCTION
    function display_date(date) {
      var now = new Date();
      var timestamp_now = now.getTime();
      var timestamp_post = date.getTime();

      var month_fr = [
        "janvier",
        "février",
        "mars",
        "avril",
        "mai",
        "juin",
        "juillet",
        "août",
        "septembre",
        "octobre",
        "novembre",
        "décembre"
      ];

      var dif = timestamp_now - timestamp_post;
      var dif_date = new Date(dif);
      dif = dif / 1000;
      if (dif < 1) {
        return "maintenant";
      } else if (dif < 60) {
        return `${dif_date.getSeconds()} s`;
      } else if (dif < (60 * 60)) {
        return `${dif_date.getMinutes()} min`;
      } else if (dif < (60 * 60 * 24)) {
        return `${dif_date.getHours()} h`;
      } else if (dif < (60 * 60 * 24 * 7)) {
        return `il y a ${dif_date.getDate() - 1} jour${(dif_date.getDate() - 1) > 1 ? 's' : ''}`;
      } else if (dif < (60 * 60 * 24 * 7 * 3)) {
        return `il y a ${(Math.floor(dif_date.getDate() / 7))} semaine${(Math.floor(dif_date.getDate() / 7)) > 1 ? 's' : ''}`;
      } else if (dif < (60 * 60 * 24 * 30 * 12)) {
        return `${date.getDate()} ${month_fr[date.getMonth()]}`;
      } else {
        return `${date.getDate()} ${month_fr[date.getMonth()]} ${date.getFullYear()}`;
      }
    }

  }

  };

  global_fix.send();

  } else {
    console.log('ERROR while requesting something : ' + babtoo.status);
  }
  
};
babtoo.send();

