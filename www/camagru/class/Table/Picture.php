<?php
namespace Clas\Table;
use Clas\Database\BasicQuery;
class Picture{
    public $id;
    public $id_user;
    public $pseudo;
    public $path;
    public $likes;
    public $liked;
    public $comment;
    public $created;
    public $table = 'pictures';
    public function __construct()
    {
        $this->table = 'pictures';
    }
    public function Import($db, $id){
        $pdo = new BasicQuery($db);
        $table = $this->table;
        $picture = $pdo->execute("SELECT * FROM `$table` WHERE id = ?", [$id])->fetch(\PDO::FETCH_ASSOC);
        $this->id = $picture['id'];
        $this->id_user = $picture['id_user'];
        $this->path = $picture['path'];
        $this->likes = $picture['likes'];
        $this->liked = $picture['liked'];
        $this->comment = $picture['comment'];
        $this->created = $picture['created'];
    }
    public function Add(\PDO $db, string $path, int $id_user, $date = ''){
        //$path = "http://localhost:8001/img/" . $name;
        if ($date === ''){
            $timestamp = date('Y-m-d G:i:s');
        } else {
            $timestamp = $date;
        }
        $table = $this->table;
        $this->path = $path;
        $this->id_user = $id_user;
        $this->likes = 0;
        $this->liked = '';
        $this->comment = 0;
        $this->created = $timestamp;
        $pdo = new BasicQuery($db);
        // dump($pdo);
        $query = "INSERT INTO $table (`id_user`, `path`, `likes`, `liked`, `comment`, `created`) VALUES (?, ?, ?, ?, ?, ?)";
        $params = [
            $id_user,
            $path,
            0,
            '',
            0,
            $timestamp
        ];
        // dump($params);
        $ret = $pdo->execute($query, $params);
        // dump($ret);
    }
    public function ToHTML($class){
        $path = $this->path;
        $str = "<img src=\"$path\" class=\"$class\" >";
        return ("$str");
    }
    public function GetAll(\PDO $db, $options = ''){
        $table = $this->table;
        $pdo = new BasicQuery($db);
        $query = "SELECT * FROM `$table` " . $options;
        $pictures = $pdo->execute($query, [])->fetchAll(\PDO::FETCH_ASSOC);
        $array_of_pic = [];
        if ($table === 'pictures'){
            foreach ($pictures as $picture){
                $pic = new Picture();
                $pic->import($db, $picture['id']);
                $id_user = $pic->id_user;
                // dump($id_user);
                $pdo = new BasicQuery($db);
                $pseudo = $pdo->execute('SELECT `pseudo` FROM `users` WHERE `id` = ?', [$id_user])->fetch(\PDO::FETCH_ASSOC)['pseudo'];
                // dump($pseudo);
                $pic->pseudo = $pseudo;
                // dump($pic);
                $array_of_pic[]= $pic;
            }
        } else {
            foreach ($pictures as $picture){
                $pic = new ProfilePicture();
                $pic->import($db, $picture['id']);
                $array_of_pic[]= $pic;
            }
        }
        return($array_of_pic);
    }
}