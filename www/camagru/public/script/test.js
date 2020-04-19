// console.log('salut');
// http://localhost:8001/index.php?id=43&pass=forgot5d59b4f44a59a0.74003213

const stopPropagation = function (e) {
  e.stopPropagation();
};

var closeModal = () => {
  document.querySelector(".modal").style.display = "none";
  document.querySelector(".modal").removeEventListener("click", closeModal);
};

var send_new_pass = function (e) {
  e.preventDefault();
  if (document.querySelector(".alert_forgoten_form") !== null) {
    document.querySelector(".alert_forgoten_form").remove();
  }
  var mail = document.querySelector(".mail_html_input_forgoten").value;
  if (!mail) {
    document
      .querySelector(".forgoten_form")
      .insertAdjacentHTML(
        "beforebegin",
        '<p class="alert_forgoten_form">Vous devez rentrer une valeur dans le champ mail.</p>'
      );
    return;
  }
  var request = new XMLHttpRequest();
  request.open("GET", "/?verif_mail=" + mail);
  request.onload = () => {
    if (request.status === 200) {
      if (request.responseText !== "OK") {
        document
          .querySelector(".forgoten_form")
          .insertAdjacentHTML(
            "beforebegin",
            `<p class="alert_forgoten_form">${request.responseText}</p>`
          );
        return;
      } else {
        document.querySelector(".message_info_temp").innerHTML = "";
        document.querySelector(".message_info_temp").innerText =
          "Un email pour changer votre mot de passe vous a été envoyé !";
        document
          .querySelector(".js-modal-stop")
          .removeEventListener("click", stopPropagation);
      }
    } else {
      alert("request failed and retturned status " + request.status);
    }
  };
  request.send();
};

var openmodal = () => {
  //for displaying the form for changing password
  document.querySelector(".message_info_temp").innerText = "";
  document.querySelector(".message_info_temp").innerHTML = `
      <div class="form">
                      <form name="forgoten" method="post" action="index.php" class="forgoten_form">
                          <p>MAIL : <input required type="text" name="mail" class="mail_html_input_forgoten"
                                  value=""></p>
                          <p class="input"><input class="forgoten_button" type="submit" value="ENVOYER UN MAIL DE RECUPERATION" /></p>
                      </form>
      `;
  document.querySelector(".modal").style.display = null;

  document
    .querySelector(".forgoten_button")
    .addEventListener("click", send_new_pass);
  document.querySelector(".modal").addEventListener("click", closeModal);
  document
    .querySelector(".js-modal-stop")
    .addEventListener("click", stopPropagation);
};

document.querySelector(".js-forgoten").addEventListener("click", openmodal);

var update_new_pass = function (e) {
  e.preventDefault();
  if (document.querySelector(".alert_change_pass_formu") !== null) {
    document.querySelector(".alert_change_pass_formu").remove();
  }
  var pass = document.querySelector(".pass_html_input_change_pass").value;
  if (!pass) {
    document
      .querySelector(".change_pass_formu")
      .insertAdjacentHTML(
        "beforebegin",
        '<p class="alert_change_pass_formu">Vous devez rentrer une valeur pour votre nouveau mot de passe.</p>'
      );
    return;
  }
  var request = new XMLHttpRequest();
  var temp_id = parseInt(document.querySelector(".js-id-user").innerText);
  let formData = new FormData(document.forms.change_pass);
  formData.append("id", temp_id);
  request.open("POST", "/");
  request.onload = function () {
    if (request.status === 200) {
      var response = request.responseText;
      console.log(response);

      if (response !== "OK") {
        document
          .querySelector(".change_pass_formu")
          .insertAdjacentHTML(
            "beforebegin",
            `<p class="alert_change_pass_formu">${response}</p>`
          );
        return;
      } else {
        document.querySelector(".message_info_temp").innerHTML = "";
        document.querySelector(".message_info_temp").innerText =
          "Votre mot de passe a été changé !";
        document
          .querySelector(".js-modal-stop")
          .removeEventListener("click", stopPropagation);
      }
    } else {
      alert(request.status);
    }
  };
  request.send(formData);
};

var send_mail_valid = document.querySelector(".js-check-validation") !== null;
if (send_mail_valid) {
  if (
    document.querySelector(".js-check-validation").innerText ===
    "change_pass_form"
  ) {
    document.querySelector(".modal").style.display = null;
    document.querySelector(".message_info_temp").innerHTML = `
          <div class="form">
                      <form name="change_pass" method="post" action="index.php" class="change_pass_formu">
                          <p>pass : <input required type="text" name="password_change" class="pass_html_input_change_pass"
                                  value="" placeholder="nouveau mot de passe"></p>
                          <p class="input"><input class="change_pass_button" type="submit" value="VALIDER" /></p>
          </form>`;

    document
      .querySelector(".change_pass_button")
      .addEventListener("click", update_new_pass);
    document.querySelector(".modal").addEventListener("click", closeModal);
    document
      .querySelector(".js-modal-stop")
      .addEventListener("click", stopPropagation);
  } else {
    document.querySelector(".modal").style.display = null;
    document.querySelector(
      ".message_info_temp"
    ).innerText = document.querySelector(".js-check-validation").innerText;
    document.querySelector(".modal").addEventListener("click", closeModal);
  }
}

var verify = function (e) {
  e.preventDefault();

  var request = new XMLHttpRequest();

  let formData = new FormData(document.forms.connect);
  // let pseudo = document.querySelector(".pseudo_html_input_connect").value;
  // let pass = document.querySelector(".pseudo_html_input_connect").value;
  // formData.append(document.querySelector(".pseudo_html_input_connect").value, document.querySelector(".pseudo_html_input_connect").value);
  request.open("POST", "/");
  request.onload = () => {
    if (request.status === 200) {
      var response = request.responseText;
      console.log(request.responseText);

      if (response !== "OK") {
        var message = response;
        document.querySelector(".modal").style.display = null;
        document.querySelector(".message_info_temp").innerText = message;
        document.querySelector(".modal").addEventListener("click", closeModal);
      } else {
        //redirect to connection with success
        window.location.replace("/");
      }
    } else {
      alert(request.status);
    }
  };
  request.send(formData);
};

var register = function (e) {
  e.preventDefault();
  var request = new XMLHttpRequest();

  let formData = new FormData(document.forms.register);
  // let pseudo = document.querySelector(".pseudo_html_input_register").value; // let prenom = document.querySelector(".prenom_html_input_register").value; // let nom = document.querySelector(".nom_html_input_register").value; // let mail = document.querySelector(".mail_html_input_register").value; // let pass = document.querySelector(".pass_html_input_register").value; // formData.append('pseudo', pseudo); // formData.append('nom', nom); // formData.append('prenom', prenom); // formData.append('mail', mail); // formData.append('pseudo', pass);

  request.open("POST", "/");
  request.onload = () => {
    if (request.status === 200) {
      var response = request.responseText;
      // console.log(request.responseText);

      if (response !== "OK") {
        var message = response;
        document.querySelector(".modal").style.display = null;
        document.querySelector(".message_info_temp").innerText = message;
        document.querySelector(".modal").addEventListener("click", closeModal);
      } else {
        //redirect to connection with success
        window.location.replace("/");
      }
    } else {
      alert(request.status);
    }
  };
  request.send(formData);
};

document.querySelector(".connection_button").addEventListener("click", verify);
document
  .querySelector(".registration_button")
  .addEventListener("click", register);

var flecheBas = document.querySelector(".fleche-ico");
var flecheBas3 = document.querySelector(".fleche-ico2");
var flecheBas2 = document.querySelector(".fleche");
var blue = document.querySelector(".static-1");
var green = document.querySelector(".static-2");
var inner_1 = document.querySelector(".inner-1");
var inner_2 = document.querySelector(".inner-2");

var ran = Math.floor(Math.random() * 5 + 1);
// console.log(ran);
/* generate random image from list of 5 */
document.querySelector(
  ".test"
).style.backgroundImage = `url(http://localhost:8001/img/${ran}.jpg)`;

var descendre = function () {
  blue.classList.add("anim-1");
  green.classList.add("anim-2");
  inner_1.classList.add("anim-4");
  flecheBas.classList.add("testou");
  setTimeout(() => {
    blue.classList.remove("anim-1");
    green.classList.remove("anim-2");
    inner_1.classList.remove("anim-4");
    flecheBas.classList.remove("testou");
    blue.style.transform = "translateY(-20em)";
    green.style.transform = "translateY(0em)";
  }, 1000);
};

flecheBas.addEventListener("click", descendre);
flecheBas2.addEventListener("click", descendre);

flecheBas3.addEventListener("click", () => {
  blue.classList.add("anim-3");
  green.classList.add("anim-4");
  inner_2.classList.add("anim-1");
  flecheBas3.classList.add("testou");
  setTimeout(() => {
    blue.classList.remove("anim-3");
    green.classList.remove("anim-4");
    inner_2.classList.remove("anim-1");
    flecheBas3.classList.remove("testou");
    blue.style.transform = "translateY(0em)";
    green.style.transform = "translateY(20em)";
  }, 1000);
});
