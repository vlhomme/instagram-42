<?php
namespace Clas\Table;
use Clas\Database\BasicQuery;
use Clas\Table\ProfilePicture;
use Clas\Table\User;

class Comment {
    public $id;
    public $id_picture;
    public $id_user;
    public $comment;
    public $likes;
    public $liked;
    public $created;
    public $table = 'comment';

    public function __construct()
    {
        $this->table = 'comment';
    }

    public function Import($db, $id_of_comment){
        $pdo = new BasicQuery($db);
        $table = $this->table;
        $comment = $pdo->execute("SELECT * FROM `$table` WHERE id = ?", [$id_of_comment])->fetch(\PDO::FETCH_ASSOC);
        $this->id = $comment['id'];
        $this->id_picture = $comment['id_picture'];
        $this->id_user = $comment['id_user'];
        $this->comment = $comment['comment'];
        $this->likes = $comment['likes'];
        $this->liked = $comment['liked'];
        $this->created = $comment['created'];
    }

    public function Add(\PDO $db, string $comment, int $id_picture, int $id_user, $date = ''){
        if ($date === '') {
            $date = date('Y-m-d G:i:s');
        }
        $table = $this->table;
        $comment = htmlspecialchars($comment);
        $this->comment = $comment;
        $this->id_picture = $id_picture;
        $this->id_user = $id_user;
        $this->likes = 0;
        $this->liked = '[]';
        $this->created = $date;
        $pdo = new BasicQuery($db);
        // dump($pdo);
        $query = "INSERT INTO $table (`id_picture`, `id_user`, `comment`, `likes`, `liked`, `created`) VALUES (?, ?, ?, ?, ?, ?)";
        $params = [
            $id_picture,
            $id_user,
            $comment,
            0,
            '[]',
            $date
        ];
        // dump($params);
        // var_dump($params);
        // $ret = $pdo->execute($query, $params);

        //INCORRECT DATETIME VALUE
        $tmp = $db->prepare($query);
        $ret = $tmp->execute($params);
        // echo('retour d\'execution pdo');
        // dump($ret);
        // var_dump($ret);
        // dump($tmp->errorInfo());
    }

    public function GetAll(\PDO $db, $options = ''){
        $table = $this->table;
        $pdo = new BasicQuery($db);
        $query = "SELECT * FROM `$table` " . $options;
        $comments = $pdo->execute($query, [])->fetchAll(\PDO::FETCH_ASSOC);
        $array_of_com = [];
        foreach ($comments as $comment) {
            $id_user = $comment['id_user'];
            $com = new Comment();
            $com->import($db, $comment['id']);
            $ppic = new ProfilePicture();
            $profile_pic = $ppic->GetAll($db, "WHERE id_user = $id_user");
            
            $pdo = new BasicQuery($db);
            $query="SELECT * FROM users WHERE id = $id_user";
            $user = $pdo->execute($query, [])->fetch(\PDO::FETCH_ASSOC);

            $array_of_com[] = $com;
            $array_of_com[] = $profile_pic;
            $array_of_com[] = $user;
        }
        return($array_of_com);
    }
}