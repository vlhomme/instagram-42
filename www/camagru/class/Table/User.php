<?php
namespace Clas\Table;
use Clas\Database\BasicQuery;
use Clas\GeneratePass;
use Clas\Mail\Sendgrid;
use Clas\Table\ProfilePicture;
class User{
    public $db;
    public $id;
    public $pseudo;
    public $prenom;
    public $nom;
    public $bio;
    public $mail;
    public $pass;
    public $verificationPass = null;
    public $hashToVerify;
    public $created;
    public $updated;
    public $admin;
    public $suspended;
    public $ErrorMessage = null;
    public function Import($db, $id){
        $pdo = new BasicQuery($db);
        $user = $pdo->execute("SELECT * FROM `users` WHERE id = ?", [$id])->fetch(\PDO::FETCH_ASSOC);
        $this->id = $user['id'];
        $this->pseudo = $user['pseudo'];
        $this->prenom = $user['prenom'];
        $this->nom = $user['nom'];
        $this->bio = $user['bio'];
        $this->mail = $user['mail'];
        $this->pass = $user['pass'];
        $this->hashToVerify = $user['verificationPass'];
        $this->created = $user['created'];
        $this->updated = $user['updated'];
        $this->admin = $user['admin'];
        $this->suspended = $user['suspended'];
        $this->db = $db;
    }
    public function Add($db, $pseudo, $prenom, $nom, $mail, $pass, $profile_pic_path = null)
    {
        $pdo = new BasicQuery($db);
        $potential_error_message = self::IsValid($pseudo, $prenom, $nom, $mail, $pass);
        if ($potential_error_message === "OK") {
            if (($res = self::exists($pdo, $pseudo, $mail)) === "no error") {
                $hash = GeneratePass::sha($pass);
                $verificationPass = GeneratePass::pass('verif');
                $stored_hash = GeneratePass::sha($verificationPass);
                $timestamp = date('Y-m-d G:i:s');
                $pseudo = htmlspecialchars($pseudo);
                $nom = htmlspecialchars($nom);
                $prenom = htmlspecialchars($prenom);
                $query = "INSERT INTO users (`pseudo`, `prenom`, `nom`, `mail`, `pass`, `verificationPass`, `created`, `updated`, `admin`, `suspended`) VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $params = [
                    $pseudo,
                    $prenom,
                    $nom,
                    $mail,
                    $hash,
                    $stored_hash,
                    $timestamp,
                    $timestamp,
                    0,
                    1
                ];
                $ret = $pdo->execute($query, $params);
                // $ret = $ret->fetchAll(\PDO::FETCH_ASSOC);
                if ($ret !== false) {
                    $user = $pdo->execute("SELECT * FROM `users` WHERE verificationPass = ?", [$stored_hash]);
                    $user = $user->fetch(\PDO::FETCH_ASSOC);
                    $this->id = $user['id'];
                    $this->pseudo = $user['pseudo'];
                    $this->prenom = $user['prenom'];
                    $this->nom = $user['nom'];
                    $this->mail = $user['mail'];
                    $this->pass = $user['pass'];
                    $this->verificationPass = $verificationPass;
                    $this->created = $user['created'];
                    $this->updated = $user['updated'];
                    $this->admin = $user['admin'];
                    $this->suspended = $user['suspended'];
                }
                if ($profile_pic_path !== null) {
                    $ppic = new ProfilePicture();
                    $ailldi = $this->id;
                    $ppic->add($db, $profile_pic_path, $ailldi);
                } else {
                    $ppic = new ProfilePicture();
                    $ailldi = $this->id;
                    $ppic->add($db, '/img/ico/user.png', $ailldi);
                }
            } else {
                // echo $res;
                $this->ErrorMessage = $res;
            }
        } else {
            // echo $potential_error_message;
            $this->ErrorMessage = $potential_error_message;
        }
    }

    public function AddBio($db, string $str)
    {
        $pdo = new BasicQuery($db);
        $str = substr($str, 0, 150);
        $str = htmlspecialchars($str);
        $pdo->execute('UPDATE users SET bio = ? WHERE id = ?', [$str, $this->id]);
        $this->bio = $str;
    }

    public function Update($db, $array)
    {
        // dump($array);
        $new_array = [];
        foreach($array as $key => $value){
            if ($key === 'mail') {
                $new_array[$key] = $value;
            } else {
            $new_array[$key] = htmlspecialchars($value);
            }
        };
        // dump($new_array);
        $array = $new_array;
        // dump($array);
        $pdo = new BasicQuery($db);
        $potential_error_message = self::IsValid_nopass($array['pseudo'], $array['prenom'], $array['nom'], $array['mail']);
        if ($potential_error_message === "OK") {
            if (($res = self::existsToo($pdo, $array['pseudo'], $array['mail'])) === "no error") {
            $timestamp = date('Y-m-d G:i:s');
            $query = "UPDATE users SET `pseudo` = ?, `prenom` = ?, `nom` = ?, `mail` = ?, `updated` = ? WHERE `id` = ?";
            $params = [
                $array['pseudo'],
                $array['prenom'],
                $array['nom'],
                $array['mail'],
                $timestamp,
                $this->id
            ];
            $ret = $pdo->execute($query, $params) ;
            if ($ret !== false) {
                $user = $pdo->execute("SELECT * FROM `users` WHERE id = ?", [$this->id])->fetch(\PDO::FETCH_ASSOC);
                $this->id = $user['id'];
                $this->pseudo = $user['pseudo'];
                $this->prenom = $user['prenom'];
                $this->nom = $user['nom'];
                $this->mail = $user['mail'];
                $this->pass = $user['pass'];
                $this->created = $user['created'];
                $this->updated = $user['updated'];
                $this->admin = $user['admin'];
                $this->suspended = $user['suspended'];
            }
        }else {
            // echo $res;
            $this->ErrorMessage = $res;
        }
        } else {
            // echo $potential_error_message;
            $this->ErrorMessage = $potential_error_message;
        }
    }
    public function Check($db, $pseudo, $pass){
        // dump($db);
        // dump($pseudo);
        // dump($pass);
        $pdo = new BasicQuery($db);
        // dump($pdo);
        $pseudo = htmlspecialchars($pseudo);
        $user = $pdo->execute("SELECT * FROM `users` WHERE pseudo = ?", [$pseudo])->fetch(\PDO::FETCH_ASSOC);
        if (!GeneratePass::verify($pass, $user['pass'])){
            $this->ErrorMessage = "Mot de passe erroné";
            return false;
        } else {
            $this->id = $user['id'];
            $this->pseudo = $user['pseudo'];
            $this->prenom = $user['prenom'];
            $this->nom = $user['nom'];
            $this->mail = $user['mail'];
            $this->pass = $user['pass'];
            $this->hashToVerify = $user['verificationPass'];
            $this->created = $user['created'];
            $this->updated = $user['updated'];
            $this->admin = $user['admin'];
            $this->suspended = $user['suspended'];
            $this->db = $db;
        }
        if ($this->suspended === "1"){
            $this->ErrorMessage = "Vous n'avez pas validé votre compte par mail";
            return false;
        }
        return true;
    }
    public function ChangePass($db, $pass_update){
        $firstRegexPass = "/^(?=.{10,}$)(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?\W).*$/";
        if(!preg_match($firstRegexPass, $pass_update))
        {
            $this->ErrorMessage = "Votre mot de passe ne respecte pas notre politique de sécurité : minimum dix caractères, une majuscule, une minuscule et un caractère spécial";
            return false;
        }elseif (strlen($pass_update) > 100){
            $this->ErrorMessage = "Votre mot de passe est trop long";
            return false;
        }
        $pdo = new BasicQuery($db);
        $new_pass = GeneratePass::sha($pass_update);
        $timestamp = date('Y-m-d G:i:s');
        $random = GeneratePass::pass("random");
        $random = GeneratePass::sha($random);
        $query = "UPDATE users SET `pass` = ?, `verificationPass` = ?, `updated` = ?, `suspended` = ? WHERE `id` = ?";
        $params = [
            $new_pass,
            $random,
            $timestamp,
            0,
            $this->id
        ];
        $ret = $pdo->execute($query, $params);
        if ($ret !== false){
            return true;
        }else{
            return false;
        }
    }
    public function SetSession(){
        $_SESSION["user_id"] = $this->id;
        $_SESSION["pseudo"] = $this->pseudo;
    }
    private function exists($pdo, $pseudo, $mail):string {        
        $tab = $pdo->execute("SELECT * FROM `users`", [])->fetchAll(\PDO::FETCH_ASSOC);
        foreach($tab as $user){
            if($pseudo === $user['pseudo']){
                return('pseudo already exists');
            }elseif($mail === $user['mail']){
                return('mail already exists');
            }
        }
        return ("no error");
    }
    private function existsToo($pdo, $pseudo, $mail):string {        
        $tab = $pdo->execute("SELECT * FROM `users`", [])->fetchAll(\PDO::FETCH_ASSOC);
        foreach($tab as $user){
            if(($pseudo === $user['pseudo']) && ($user['id'] !== $this->id)){
                return('pseudo already exists');
            }elseif(($mail === $user['mail']) && ($user['id'] !== $this->id)){
                return('mail already exists');
            }
        }
        return ("no error");
    }
    private function IsValid($pseudo, $prenom, $nom, $mail, $pass = NULL)
    {
        $str = self::PassesRegex($pseudo, $prenom, $nom, $mail, $pass);
        $str2 = self::IslongEnough($pseudo, $prenom, $nom, $mail, $pass);
        if ($str !== "OK")
        {
            return $str;
        }
        elseif($str2 !== "OK")
        {
            return $str2;
        }
        else
        {
            return "OK";
        }
    }
    private function IsValid_nopass($pseudo, $prenom, $nom, $mail)
    {
        $str = self::PassesRegex_nopass($pseudo, $prenom, $nom, $mail);
        $str2 = self::IslongEnough_nopass($pseudo, $prenom, $nom, $mail);
        if ($str !== "OK")
        {
            return $str;
        }
        elseif($str2 !== "OK")
        {
            return $str2;
        }
        else
        {
            return "OK";
        }
    }
    private function PassesRegex($pseudo, $prenom, $nom, $mail, $pass)
    {
        $test = "ཀུ༇ༀ";
        $test250char = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
        /* 
        implements a quick test
        *
        *
        $pseudo = "Salut ░ ▒ ▓ ཀུ༇ༀ";
        $prenom = "SLut";
        $nom = "Slut";
        $mail = "vlhomme@student.42.fr";
        $pass = "Q1234-Mdt4r&";
        $user = new User();
        $user->Add($db, $pseudo, $prenom, $nom, $mail, $pass); */
        $firstRegexPass = "/^(?=.{10,}$)(?=.*?[a-z])(?=.*?[A-Z])(?=.*?[0-9])(?=.*?\W).*$/";
        $regexPseudo = "/^[^\n\r\t\f\v]+$/";
        $regexPrenomNom = "/^[A-Za-z- ]+$/";
        if (!preg_match($regexPrenomNom, $prenom))
        {
            // dump($prenom);
            if (self::isAccented($prenom))
            {
                if(!preg_match($regexPrenomNom, self::RemoveAccemt($prenom)))
                {
                    return "Votre prénom contient des caractéres interdits. Seuls les lettres, les espaces et les '-' sont autorisés pour ce champ";
                }
            } else {
                return "Votre prénom contient des caractéres interdits. Seuls les lettres, les espaces et les '-' sont autorisés pour ce champ";
            }
        }elseif(!preg_match($regexPrenomNom, $nom))
        {
            // dump($nom);
            if (self::isAccented($nom))
            {
                if(!preg_match($regexPrenomNom, self::RemoveAccemt($nom)))
                {
                    return "Votre nom contient des caractéres interdits. Seuls les lettres, les espaces et les '-' sont autorisés pour ce champ";
                }
            } else {
                return "Votre nom contient des caractéres interdits. Seuls les lettres, les espaces et les '-' sont autorisés pour ce champ";
            }
        }elseif(!preg_match($regexPseudo, $pseudo))
        {
            // dump($pseudo);
            return "Votre pseudo contient des caractéres interdits. Veuillez réessayer sans tabulations";
        }elseif(filter_var($mail, FILTER_VALIDATE_EMAIL) === false){
            // dump($mail);
            return "Votre adresse mail semble incorrecte";
        }elseif(!preg_match($firstRegexPass, $pass))
        {
            // dump($pass);
            return ("Votre mot de passe ne respecte pas notre politique de sécurité : minimum dix caractères, une majuscule, une minuscule et un caractère spécial");
        }
        return ("OK");
    }
    private function PassesRegex_nopass($pseudo, $prenom, $nom, $mail)
    {
        $test = "ཀུ༇ༀ";
        $test250char = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";
        /* 
        implements a quick test
        *
        *
        $pseudo = "Salut ░ ▒ ▓ ཀུ༇ༀ";
        $prenom = "SLut";
        $nom = "Slut";
        $mail = "vlhomme@student.42.fr";
        $pass = "Q1234-Mdt4r&";
        $user = new User();
        $user->Add($db, $pseudo, $prenom, $nom, $mail, $pass); */
        $regexPseudo = "/^[^\n\r\t\f\v]+$/";
        $regexPrenomNom = "/^[A-Za-z- ]+$/";
        if (!preg_match($regexPrenomNom, $prenom))
        {
            // dump($prenom);
            if (self::isAccented($prenom))
            {
                if(!preg_match($regexPrenomNom, self::RemoveAccemt($prenom)))
                {
                    return "Votre prénom contient des caractéres interdits. Seuls les lettres, les espaces et les '-' sont autorisés pour ce champ";
                }
            } else {
                return "Votre prénom contient des caractéres interdits. Seuls les lettres, les espaces et les '-' sont autorisés pour ce champ";
            }
        }elseif(!preg_match($regexPrenomNom, $nom))
        {
            // dump($nom);
            if (self::isAccented($nom))
            {
                if(!preg_match($regexPrenomNom, self::RemoveAccemt($nom)))
                {
                    return "Votre nom contient des caractéres interdits. Seuls les lettres, les espaces et les '-' sont autorisés pour ce champ";
                }
            } else {
                return "Votre nom contient des caractéres interdits. Seuls les lettres, les espaces et les '-' sont autorisés pour ce champ";
            }
        }elseif(!preg_match($regexPseudo, $pseudo))
        {
            // dump($pseudo);
            return "Votre pseudo contient des caractéres interdits. Veuillez réessayer sans tabulations";
        }elseif(filter_var($mail, FILTER_VALIDATE_EMAIL) === false){
            // dump($mail);
            return "Votre adresse mail semble incorrecte";
        }
        return ("OK");
    }
    private function IslongEnough($pseudo, $prenom, $nom, $mail, $pass)
    {
        if (self::long(2, 250, $pseudo) !== "OK"){
            return ("Votre pseudo " . self::long(2, 250, $pseudo));
        }elseif(self::long(1, 250, $prenom) !== "OK"){
            return ("Votre prénom " . self::long(1, 250, $prenom));
        }elseif(self::long(1, 250, $nom) !== "OK"){
            return ("Votre nom " . self::long(1, 250, $nom));
        }elseif(self::long(5, 250, $mail) !== "OK"){
            return ("Votre email " . self::long(1, 250, $mail));
        }elseif(self::long(10, 100, $pass) !== "OK"){
            return ("Votre mot de passe " . self::long(1, 250, $pass));
        }
        return "OK";
    }
    private function IslongEnough_nopass($pseudo, $prenom, $nom, $mail)
    {
        if (self::long(2, 250, $pseudo) !== "OK"){
            return ("Votre pseudo " . self::long(2, 250, $pseudo));
        }elseif(self::long(1, 250, $prenom) !== "OK"){
            return ("Votre prénom " . self::long(1, 250, $prenom));
        }elseif(self::long(1, 250, $nom) !== "OK"){
            return ("Votre nom " . self::long(1, 250, $nom));
        }elseif(self::long(5, 250, $mail) !== "OK"){
            return ("Votre email " . self::long(1, 250, $mail));
        }
        return "OK";
    }
    private function long($min, $max, $str){
        if (strlen($str) >= $min) {
            if (strlen($str) > $max) {
                return ("est trop self::long");
            }
            return ("OK");
        } else {
            return ("est trop court");
        }
    }
    public function IsAccented(string $str){
        $i = 0;
        $testCase = "àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ";
        while($testCase[$i]){
            if (strpos($str, $testCase[$i]) !== false)
            {
                return true;
            }
            $i++;
        }
    
        return false;
    }
    public function RemoveAccemt ($string){
        $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $b = 'AAAAAAACeeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $string = utf8_decode($string);
        $string = strtr($string, utf8_decode($a), $b);
        //$string = strtolower($string);
        return utf8_encode($string); 
    }
    public function GetErrors(){
        return ($this->ErrorMessage);
    }
    public function SendValidationMail(){
        $mail = new Sendgrid("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");
        $validationURI = "http://localhost:8001/index.php?id=$this->id&valid=$this->verificationPass";
        $body = "<h1>Bienvenue chez Camagru, $this->pseudo !</h1><br>Voici votre lien de validation<br><a href=$validationURI>CLIQUE</a><br>$validationURI";
        $mail->sendMail(
            "$this->mail",
            "camagru-official@vlhommetavelino.42.fr",
            "$this->prenom $this->nom",
            "votre mail de la team camagru",
            "$body"
        );
        return ("We need to fucking implement if mail fuck up");
    }
    public function ConfirmUser($verificationPass){
        if(GeneratePass::verify($verificationPass, $this->hashToVerify)){
            $pdo = new BasicQuery($this->db);
            $res = $pdo->execute("UPDATE users SET suspended = 0 WHERE id = ?", [$this->id]);
            // dump($res);
            return("Votre compte est validé vous pouvez désormais vous connecter");
        } else {
            return ("Votre lien est corrompu. Veuillez réessayer.");
        }
    }
    public function MailExists($db, $mail){
        $pdo = new BasicQuery($db);
        $tab = $pdo->execute("SELECT * FROM `users`", [])->fetchAll(\PDO::FETCH_ASSOC);
        foreach($tab as $user){
            if($mail === $user['mail']){
                return('yes');
            }
        }
        return ("votre email n\'a pas ete trouvé");
    }
    public function SendRecovery($db, $mail)
    {
        $pdo = new BasicQuery($db);
        $user = $pdo->execute("SELECT * FROM `users` WHERE mail = ?", [$mail])->fetch(\PDO::FETCH_ASSOC);
        //dump($user);
        $this->id = $user['id'];
        $this->pseudo = $user['pseudo'];
        $this->prenom = $user['prenom'];
        $this->nom = $user['nom'];
        $this->mail = $user['mail'];
        $this->created = $user['created'];
        $this->updated = $user['updated'];
        $this->admin = $user['admin'];
        $this->suspended = $user['suspended'];
        $this->db = $db;
        $newPass = GeneratePass::pass('forgot');
        $stored_hash = GeneratePass::sha($newPass);
        $null = "";
        date_default_timezone_set('Europe/Paris');
        $timestamp = date('Y-m-d G:i:s');
        $ret = $pdo->execute("UPDATE `users` SET `verificationPass` = ?, `pass` = ?, `updated` = ?, `suspended` = ? WHERE id = ?", [$stored_hash, $null, $timestamp, 1, $this->id]);
        //dump($ret);
        $mail = new Sendgrid("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");
        $validationURI = "http://localhost:8001/index.php?id=$this->id&pass=$newPass";
        $body = "<h1>Salut, $this->pseudo !</h1><br>Tu as oublié ton mot de passe petit chenapan ?<br>Voici ton lien pour en recreer un<br><a href=$validationURI>CLIQUE</a><br>$validationURI";
        $mail->sendMail(
            "$this->mail",
            "camagru-official@vlhommetavelino.42.fr",
            "$this->prenom $this->nom",
            "Récupération de mot de passe",
            "$body"
        );
    }
    public function AccessRecovery($db, $id, $pseudo, $mail, $pass)
    {
        $pdo = new BasicQuery($db);
        $user = $pdo->execute("SELECT * FROM `users` WHERE id = ?", [$id])->fetch(\PDO::FETCH_ASSOC);
        if (($pseudo !== $user['pseudo']) && ($mail !== $user['mail'])){
            return "Votre pseudo et votre mail ne correspondent pas.";
        } else {
            return "OK";
        }
    }
    public function ConfirmLinkRecovery($db, $id, $pass){
        $pdo = new BasicQuery($db);
        $user = $pdo->execute("SELECT * FROM `users` WHERE id = ?", [$id])->fetch(\PDO::FETCH_ASSOC);
        if ((GeneratePass::verify($pass, $user['verificationPass']))){
            return true;
        }else{
            return false;
        }
    }
    public function GetPictures($db, $id){
        $pdo = new BasicQuery($db);
        $pictures = $pdo->execute("SELECT * FROM `pictures` WHERE id_user = ?", [$id])->fetchAll(\PDO::FETCH_ASSOC);
        $array_of_pic = [];
        foreach ($pictures as $picture){
            $pic = new Picture();
            $pic->import($db, $picture['id']);
            $array_of_pic[]= $pic;
        }
        return($array_of_pic);
    }
}