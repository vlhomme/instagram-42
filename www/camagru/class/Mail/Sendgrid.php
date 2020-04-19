<?php
namespace Clas\Mail;
/* *
 * Class Sendgrid
 *
 * Usage : 
 * $mail = new Sendgrid("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");
 * $body = "yo<br>yo<br><a href='https://www.google.com'>Google</a>";
 * $mail->sendMail(
    "abnaceur@gmail.com",
    "camagru-official@vlhommetavelino.42.fr",
    "abdel naceur",
    "votre mail de la team camagru",
    "$body");
 * 
 */
class Sendgrid{
    /*
    *
    * sendgrid account
    * vlhomme : user
    * vlhomme@student.42.fr : mail
    * 1=/*t^nfeP1?1&3%31 : pass
    *
    * vlhommetavelino : user
    * jefilmemaformation42@gmail.com :mail
    * 1=/*t^nfeP1?1&3%31 : pass
    *
    * * */
    private $apiKey;
    protected $destinationMail = "abnaceur@gmail.com";
    protected $name = "TEST";
    protected $body = "yo <br><br><a href='https://www.google.com'>Google</a>";
    protected $subject = "Test email";
    protected $senderMail = "camagru-official@vlhommetavelino.42.fr";
    private $headers = [];
    public function __construct(?string $apiKey)
    {
        if ($apiKey !== null && $apiKey !== "")
        {
            $this->apiKey = $apiKey;
        }
        else
        {
            //$this->apiKey = "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";
            $this->apiKey = "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";
        }
        $this->headers[] = "Authorization: Bearer $this->apiKey";
        $this->headers[] = "Content-Type: application/json";
        /* echo("<pre>");
        var_dump($this->headers);
        echo("</pre>"); */
    }
    public function sendMail(string $destinationMail, string $senderMail, string $name, string $subject, string $body)
    {
        $this->destinationMail = $destinationMail;
        $this->senderMail = $senderMail;
        $this->name = $name;
        $this->body = $body;
        $this->subject = $subject;
        /* echo("<pre>");
        var_dump($this->destinationMail);
        var_dump($this->senderMail);
        var_dump($this->name);
        var_dump($this->body);
        var_dump($this->subject);
        echo("</pre>"); */
        $data = array(
            "personalizations" => array(
                array(
                    "to" => array(
                        array(
                            "email" => $this->destinationMail,
                            "name" => $this->name
                        )
                    )
                )
            ),
            "from" => array(
                "email" => "$this->senderMail"
            ),
            "subject" => $this->subject,
            "content" => array(
                array(
                    "type" => "text/html",
                    "value" => $this->body
                )
            )
        );
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_RETURNTRANSFER => 1
        ]);
        $response = curl_exec($ch);
        // echo("<pre>");
        // echo "Retourne le message d'erreur ou '' (chaîne vide) si aucune erreur n'est survenue.";
        // var_dump(curl_error($ch));
        curl_close($ch);
        // echo 'mail was probably send';
        // echo("</pre>");
    }
}