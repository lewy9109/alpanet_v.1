<?php

namespace App\Model;

use PDO;
// use App\Token;
// use App\Mail;
// use Core\View;
// use \App\DateTimeMy;
// use \App\UploadImg;

// use DateTime;
// use Doctrine\DBAL\Types\VarDateTimeImmutableType;

/**
 *  user model
 *
 * PHP version 7.0
 */
class User extends \App\Core\Model
{

    /**
    * Error message
    */
    public $errors =[];


    /**
     * Class constructor
     *
     * @param array $data  Initial property values
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Save a user model with the current propetry values
     *
     * @return void
     */
    public function save($access)
    {
        $this->validate();
     

        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
            if (isset($this->nameCompany)) {
                $sql = 'INSERT INTO user (name, surname, email, password, access, phone, img, company)
                    VALUES (:name, :surname, :email, :password, :access, :phone, :img, :company)';
            
                $db = static::getDB();
                $stmt = $db->prepare($sql);
    
                $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
                $stmt->bindValue(':surname', $this->surname, PDO::PARAM_STR);
                $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
                $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
                $stmt->bindValue(':access', $access, PDO::PARAM_STR);
                $stmt->bindValue(':phone', $this->phone, PDO::PARAM_STR);
                $stmt->bindValue(':img', $this->image_field, PDO::PARAM_STR);
                $stmt->bindValue(':company', $this->nameCompany, PDO::PARAM_STR);
    
                return $stmt->execute();
            } else {
                $sql = 'INSERT INTO user (name, surname, email, password, access, phone, img)
                VALUES (:name, :surname, :email, :password, :access, :phone, :img)';
        
                $db = static::getDB();
                $stmt = $db->prepare($sql);

                $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
                $stmt->bindValue(':surname', $this->surname, PDO::PARAM_STR);
                $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
                $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
                $stmt->bindValue(':access', $access, PDO::PARAM_STR);
                $stmt->bindValue(':phone', $this->phone, PDO::PARAM_STR);
                $stmt->bindValue(':img', $this->image_field, PDO::PARAM_STR);
         
                return $stmt->execute();
            }
        }

        return false;
    }
  

    public function saveCustomer()
    {
        $this->validate();
        
        $access = "null";

        $check1 = $this->save($access);
 
        $check2 = $this->saveDomain();

        $check3 = $this->savePakiet();

        
        if ($check1 == true && $check2 == true && $check3 == true) {
            return true;
        } else {
            return false;
        }
    }
  
    public function saveDomain()
    {
        if (empty($this->errors)) {
            $sql = "INSERT INTO customer_domain (name_domain, id_user)
                    VALUES (:domain, (SELECT id FROM user WHERE email = '$this->email') )";
            
            $db = static::getDB();
            $stmt = $db->prepare($sql);
    
            $stmt->bindValue(':domain', $this->nameDomain, PDO::PARAM_STR);

            $stmt->execute();
            return true;
        }

        return false;
    }

    public function savePakiet()
    {
        if (empty($this->errors)) {
            $sql = "INSERT INTO pakiet (id_user)
                    VALUES ((SELECT id FROM user WHERE email = '$this->email') )";
            
            $db = static::getDB();
            $stmt = $db->prepare($sql);
    

            $stmt->execute();
            return true;
        }

        return false;
    }


    public function validate()
    {
        if (isset($this->nameCategory)) {

            if ($this->nameCategory == "") {
                $this->errorCategory = "Wpisz nazwę kategorii";
                $this->errors[] = "Podaj nazwe domeny";
            }
        }

        if (isset($this->nameDomain)) {
            //nameDomain
            if ($this->nameDomain == '') {
                $this->errors[] = "Podaj nazwe domeny";
                $this->errorDomain = "Podaj nazwe domeny";
            }
        }
        //img
        if (!isset($this->image_field)) {
            $this->image_field = null;
        }

        //Name
        if (isset($this->name)) {
            ucfirst($this->name);

            if ($this->name == '') {
                $this->errors[] = "Podaj Imię";
                $this->errorName = "Podaj Imię";
            }
        }
        //phone

        if (isset($this->phone)) {
        }

        //Surname
        
        if (isset($this->surname)) {
            ucfirst($this->surname);

            if ($this->surname == '') {
                $this->errors[] = "Podaj nazwisko";
                $this->errorSurname = "Podaj Nazwisko";
            }
        }
        
        if (isset($this->email)) {
            //Email
            if (filter_var($this->email, FILTER_VALIDATE_EMAIL)===false) {
                $this->errors[] = "Niepoprawny adress e-mail";
                $this->errorEmail = "Niepoprawny adress e-mail";
            }

            if ($this->emailExists($this->email, $this->id ?? null)) {
                $this->errors[] = "e-mail jest zajęty";
                $this->errorEmail = "e-mail jest zajęty";
            }
        }
        
        
        if (isset($this->password)) {
            //Password
            if ($this->password != $this->password2) {
                $this->errors[]= "Hasła się nie zgadzają";
                $this->errorPassword = "Hasła się nie zgadzają";
            }

            if (strlen($this->password) <6) {
                $this->errors[]= "Hasło musi zawierać conajmniej 6 znaków";
                $this->errorPassword = "Hasła się nie zgadzają";
            }
        }
    }

    /**
     * Check the email is exist in data base
     *
     */
    public static function emailExists($email, $ignore_id = null)
    {
        $user = static::findByEmail($email);

        if ($user) {
            if ($user->id != $ignore_id) {
                return true;
            }
        }
        return false;
        //return static::findByEmail($email) !== false;
    }

    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM user WHERE email = :email';
        

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Sprawdz poprawnosc hasla i loginu email
     *
     */

    public static function authenticate($email, $password)
    {

        //sprawdzenie czy uzytkownik o danym adresie email istnieje
        $user = static::findByEmail($email);
        //jesli uzytkownik istnieje -> sprawdzenie poprawnosci hasla
        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
            return false;
        }
    }

    public static function getDomainByIdUser($id)
    {
        $sql = "SELECT name_domain FROM customer_domain WHERE id_user = :id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
    
    public static function findById($id)
    {
        $sql = 'SELECT * FROM user WHERE id = :id';
        
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    // public function rememberLogin()
    // {
    //     $token = new Token();
    //     $hashed_token = $token->getHash();
    //     $this->remember_token = $token->getValue();

    //     $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;
       
        
    //     $sql = "INSERT INTO remember_logins (token_hash, user_id, expires_at)
    //             VALUES (:token, :user_id, :expires_at)";

    //     $db = static::getDB();
    //     $stmt = $db->prepare($sql);

    //     $stmt->bindValue(':token', $hashed_token, PDO::PARAM_STR);
    //     $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
    //     $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

    //     return $stmt->execute();
    // }

    /**
     *  get employee list
     *
     * */

    public static function getEmployees()
    {
        $access = "null";

        $sql = "SELECT * FROM user 
        WHERE access != :access ORDER BY surname ASC";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
            
        $stmt->bindValue(':access', $access, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $result = $stmt->fetchAll();
       
        return $result;
    }

    /**
    *  get customer list
    *
    * */

    public static function getCustomers()
    {
        $access = "null";

        $sql = "SELECT * FROM user 
                INNER JOIN customer_domain ON customer_domain.id_user = user.id
                RIGHT JOIN pakiet ON customer_domain.id_user = pakiet.id_user
                WHERE user.access = :access AND pakiet.status_pakiet = 'Aktywny' || pakiet.status_pakiet IS NULL ORDER BY date_add DESC";

        // $sql = "SELECT * FROM user
        // INNER JOIN customer_domain ON customer_domain.id_user = user.id
        // WHERE user.access = :access  ORDER BY date_add DESC";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
            
        $stmt->bindValue(':access', $access, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            $row->pakiet_time = $row->pakiet_time/60;
        }
 
        return $result;
    }

    public static function getAllDomainsByUser($id_customer)
    {
        $sql = "SELECT name_domain FROM customer_domain WHERE id_user = '$id_customer' ";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }
  

    public static function getEmployeeById($id)
    {
        $sql = "SELECT * FROM user WHERE id = '$id' ";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $result = $stmt->fetch();

        return $result;
    }

    public static function getCustomerById($id)
    {
        $sql = "SELECT * FROM user 
        INNER JOIN customer_domain ON user.id = customer_domain.id_user 
        WHERE user.id = '$id' ";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $result = $stmt->fetch();

        return $result;
    }


    public function deleteUser($id)
    {
        $sql = "DELETE FROM user WHERE id = :id";
            
        $db = static::getDB();
        $stmt = $db->prepare($sql);
            
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    
        return $stmt->execute();
    }

    public function updatePhone($id)
    {
        if (empty($this->errors)) {
            $sql = "UPDATE user SET phone = :phone WHERE id = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':phone', $this->phone, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
            return $stmt->execute();
        }
        return false;
    }

    public function updateAccess($id)
    {
        $sql = "UPDATE user SET access = :access WHERE id = :id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':access', $this->access, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updateName($id)
    {
        $this->validate();

        if ($this->name == '') {
            return false;
        }

        if (empty($this->errors)) {
            $sql = "UPDATE user SET name = :name WHERE id = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
            return $stmt->execute();
        }
        return false;
    }

    public function updateSurname($id)
    {
        $this->validate();

        if ($this->surname == '') {
            return false;
        }

        if (empty($this->errors)) {
            $sql = "UPDATE user SET surname = :surname WHERE id = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':surname', $this->surname, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
            return $stmt->execute();
        }
        return false;
    }



    public function updateEmail($id)
    {
        $emailExists = static::emailExists($this->email);

        if ($this->email == '' || $emailExists === true) {
            unset($this->email);

            return false;
        }

        $this->validate();

        if (empty($this->errors)) {
            $sql = "UPDATE user SET email = :email WHERE id = :id";
            
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
            return $stmt->execute();
        }
        return false;
    }

    public function updatePassword($id)
    {
        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = "UPDATE user SET password = :password_hash WHERE id = :id";
            
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
            return $stmt->execute();
        }
        return false;
    }

    public function updateCompanyName($id)
    {
        $this->validate();

        if ($this->nameCompany == '') {
            return false;
        }

        if (empty($this->errors)) {
            $sql = "UPDATE user SET company = :company WHERE id = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':company', $this->nameCompany, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
            return $stmt->execute();
        }
        return false;
    }
    
    /**
     *
     * IMG  save
     */
    public  function updateImg($id)
    {
        $file_ext = explode('.', $this->image_field['name']);
        $file_ext = end($file_ext);

        $newNameFile = uniqid('', true).".".$file_ext;
        
        $dirpath = realpath(dirname(getcwd()));
        move_uploaded_file($this->image_field['tmp_name'], "$dirpath/private_html/img/avatar/".$newNameFile);

        $sql = "UPDATE user SET img = :img WHERE id = :id";
    
        $db = static::getDB();
        $stmt = $db->prepare($sql);
    
        $stmt->bindValue(':img', $newNameFile, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }



    /**
     *
     * RESET PASSWORD
     */
    public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);

        if ($user) {
            if ($user->startPasswordReset()) {
                $user->sendPasswordResetEmail();
            }
        }
    }

    // protected function startPasswordReset()
    // {
    //     $token = new Token();

    //     $hashed_token = $token->getHash();
        
    //     $this->password_reset_token = $token->getValue();
        
    //     $expiry_timestamp = time() + 60*60*26;

    //     $sql = "UPDATE user SET password_rest_hash = :password_rest_hash, 
    //     password_reset_exp = :password_reset_exp
    //     WHERE id = :id";

    //     $db = static::getDB();
    //     $stmt = $db->prepare($sql);

    //     $stmt->bindValue(':password_rest_hash', $hashed_token, PDO::PARAM_STR);
    //     $stmt->bindValue(':password_reset_exp', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
    //     $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

    //     return $stmt->execute();
    // }

    public function validateEmail()
    {
        if ($this->subject == '') {
            $this->errors[] = "Wpisz temat";
            $this->errorTopicEmail = "Wpisz temat";
        }

        if ($this->message == '') {
            $this->errors[] = "Wpisz wiadomość";
            $this->errorMessageEmail = "Wpisz wiadomość";
        }

        if(isset($this->categorie))
        {
            if ($this->categorie == 0 || $this->categorie == '0' || $this->categorie == ''  ) {
                $this->errors[] = "Wybierz temat";
                $this->errorMessageCat = "Wybierz temat";
            }
        }
        

        if(isset($this->domain))
        {
            if ($this->domain == '' ) {
                $this->errors[] = "Wpisz adrees www domeny";
                $this->errorDomainAdress = "Wpisz adrees www domeny";
            }
        }

        if(isset($this->emailFromUser))
        {
            if ($this->emailFromUser == '' ) {
                $this->errors[] = "Wpisz adress e-mail";
                $this->errorEmailFromUser = "Wpisz adress e-mail";
            }
        }

        if(isset($this->name))
        {
            if ($this->name == '' ) {
                $this->errors[] = "Podaj imię";
                $this->errorName = "Podaj imię";
            }
        }
        if(isset($this->surname))
        {
            if ($this->surname == '' ) {
                $this->errors[] = "Podaj Nazwisko";
                $this->errorSurname = "Podaj Nazwisko";
            }
        }

    }

    protected function validateBox()
    {
        if(!isset($this->checkBox2) || !isset($this->checkBox1))
        {
            $this->errors[] = "Zaznacz obowiązkowe pola";
           
        }

    }

    // protected function sendPasswordResetEmail()
    // {
    //     $url = 'http://'.$_SERVER['HTTP_HOST']. '/password/reset?token='.$this->password_reset_token;

    //     $text = View::getTemplate('Password/reset_email.txt', [
    //         'url'=>$url
    //     ]);

    //     $html = View::getTemplate('Password/reset_email.html', [
    //         'url'=>$url
    //     ]);
       
    //     Mail::send($this->email, 'Reset Hasła', $text, $html);
    // }

    // public function sendEmailToUs($attachments, $id, $nameFile)
    // {
    //     $emailfromUser = $this->emailFromUser;
    //     $title = $this->subject;
    //     $priority = $this->priority;
    //     $message = $this->message;

    //     $text = View::getTemplate('MailToUs/e-mailToUs.txt', [
    //        'emailFromUser'=>$emailfromUser,
    //        'title'=>$title,
    //        'priority'=>$priority,
    //        'message'=>$message
    //     ]);

    //     $html = View::getTemplate('MailToUs/e-mailToUs.html', [
    //         'emailFromUser'=>$emailfromUser,
    //         'title'=>$title,
    //         'priority'=>$priority,
    //         'message'=>$message,
    //     ]);
        
    //     $this->validateEmail();
    //     $ourEmail = "biuro@alpanet.pl";  //CONFIG::USERNAME;


    //     if (empty($this->errors)) {
    //         if ($attachments == '') {
    //             if (Mail::sendToUs($ourEmail, "PAKIET POMOCNA DLON - ".$this->emailFromUser." - ".$title, $text, $html)) {
    //                 if ($this->saveMailToDB($id, $nameFile)) {
    //                     return true;
    //                 }
    //                 return true;
    //             }
    //             return false;
    //         } else {
    //             if (Mail::sendToUs2($ourEmail, "PAKIET POMOCNA DLON - ".$this->emailFromUser." - ".$title, $text, $html, $attachments, $nameFile)) {
    //                 if ($this->saveMailToDB($id, $nameFile)) {
    //                     return true;
    //                 }
    //                 return true;
    //             }
    //             return false;
    //         }
    //     }
    //     return false;
    // }

    // public function reArrayFiles($file_post)
    // {
    //     $filel_ary = array();
    //     $file_count = count($file_post['name']);
    //     $file_keys = array_keys($file_post);

    //     for ($i=0; $i<$file_count; $i++) {
    //         foreach ($file_keys as $key) {
    //             $filel_ary[$i][$key] = $file_post[$key][$i];
    //         }
    //     }
    //     return $filel_ary;
    // }

    // public function sendEmailToUs2($file_array, $id)
    // {
    //     $date = new DateTime();
    //     $date = $date->format('Y-m-d H:i:s');
       
    //     $emailfromUser = $this->emailFromUser;
    //     $title = $this->subject;
    //     $priority = $this->priority;
    //     $message = $this->message;

    //     $text = View::getTemplate('MailToUs/e-mailToUs.txt', [
    //        'emailFromUser'=>$emailfromUser,
    //        'title'=>$title,
    //        'priority'=>$priority,
    //        'message'=>$message
    //     ]);

    //     $html = View::getTemplate('MailToUs/e-mailToUs.html', [
    //         'emailFromUser'=>$emailfromUser,
    //         'title'=>$title,
    //         'priority'=>$priority,
    //         'message'=>$message,
    //     ]);
        
    //     $this->validateEmail();
            

    //     $ourEmail = CONFIG::MAILTO; 
    //         $mail = new Mail;
           
    //     if(empty($this->errors)){

    //         if($files = $mail->sendToUs3($ourEmail, "PAKIET POMOCNA DLON - ".$this->emailFromUser." - ".$title, $text, $html, $file_array)){
             
    //             if($files)
    //             {
    //                 if($this->saveMailToDB2($id, $files, $date) && $this->saveCat($date)){
    //                     $this->saveOrdinalNumberMailPakiet($date);
                        
    //                     return true;
    //                 }
    //                 return false;
    //             }
    //             return false;
               
    //         }
    //        return false;
            
    //     }
    //     return false;

    // }

    // public function saveOrdinalNumberMailPakiet($date)
    // {
        
    //     $ordinalNumber = "am1x";

    //     $date2 = new \DateTime($date);
    //     $year = $date2->format('Y');
    //     $month = $date2->format('m');
    //     $day = $date2->format('d');
    //     $hour = $date2->format('H');
    //     $min = $date2->format('i');
        
    //     $ordinalNumber .= $year.$month.$day.$hour.$min."-";

        
    //     if(empty($result = $this->getLastOrdinalNumberPakiet()))
    //     {
    //         $number = "1";
    //     }else{
    //         foreach($result as $row){
    //             $file_ext = explode('-', $row->ordinal_number);
    //             $file_ext = end($file_ext);
    //         }
    //         $number = $file_ext + 1;
    //     }
        

    //     $ordinalNumber .= $number;

      
    //     $sql = "INSERT mail_pakiet_ordinal_number (id_mail, ordinal_number)
    //             VALUES ((SELECT id FROM mailtous WHERE topic = '$this->subject' AND data = '$date'), 
    //             :ordinal_number)";

    //     $db = static::getDB();
    //     $stmt = $db->prepare($sql);

    //     $stmt->bindValue(':ordinal_number', $ordinalNumber, PDO::PARAM_STR);

    //     $result = $stmt->execute();

    //     if($result)
    //     {
            
    //         $this->sendConfirmationEmailPakiet($ordinalNumber);
            
    //     }
        

    //     return $result;
    // }
    public function getLastOrdinalNumberPakiet()
    {
        $sql = "SELECT ordinal_number FROM mail_pakiet_ordinal_number ORDER BY id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetchAll();

        return $results;
    }

    // public function sendConfirmationEmailPakiet($ordinalNumber)
    // {
        
    //     $text = View::getTemplate('MailToUs/_confirmation.txt', [
            
    //         'notification'=>$ordinalNumber
    //      ]);
 
    //      $html = View::getTemplate('MailToUs/_confirmation.html', [
         
    //         'notification'=>$ordinalNumber
    //      ]);

    //     $mail = new Mail;

    //     $ourEmail = $this->emailFromUser;
    //     if($mail->send($ourEmail, "PAKIET POMOCNA DLON -  Potwierdzenie zgloszenia", $text, $html))
    //     {
    //         return true;
    //     }
    // }

    // public function sendEmailToUs3($file_array)
    // {
    //     $date = new DateTime();
    //     $date = $date->format('Y-m-d H:i:s');
       
    //     $emailfromUser = $this->domain;
    //     $title = $this->subject;
    //     $priority = $this->priority;
    //     $message = $this->message;

    //     $text = View::getTemplate('MailToUs/e-mailToUs.txt', [
    //        'emailFromUser'=>$emailfromUser,
    //        'title'=>$title,
    //        'priority'=>$priority,
    //        'message'=>$message
    //     ]);

    //     $html = View::getTemplate('MailToUs/e-mailToUs.html', [
    //         'emailFromUser'=>$emailfromUser,
    //         'title'=>$title,
    //         'priority'=>$priority,
    //         'message'=>$message,
    //     ]);
        
    //     $this->validateEmail();

    //     $this->validateBox();
       
    //     $ourEmail = CONFIG::MAILTO;   //CONFIG::MAILTO
    //         $mail = new Mail;
    //     if(empty($this->errors)){

    //         if($files = $mail->sendToUs3($ourEmail, "CENTRUM POMOCY - ".$this->domain." - ".$title, $text, $html, $file_array)){ 
               
    //             if($this->saveMailToDB3($files, $date)){
                    
    //                 $this->saveOrdinalNumberMail($date);
    //                 return true;
    //             }
    //             return false;
    //         }
    //        return false;
            
    //     }
    //     return false;

    // }
    

    // public function saveOrdinalNumberMail($date)
    // {

    //     $ordinalNumber = "am1x";

    //     $date2 = new \DateTime($date);
    //     $year = $date2->format('Y');
    //     $month = $date2->format('m');
    //     $day = $date2->format('d');
    //     $hour = $date2->format('H');
    //     $min = $date2->format('i');
        
        
    //     $ordinalNumber .= $year.$month.$day.$hour.$min."-";

    //     if(empty($result = $this->getLastOrdinalNumber()))
    //     {
    //         $number = "1";
    //     }else{
    //         foreach($result as $row){
    //             $file_ext = explode('-', $row->ordinal_number);
    //             $file_ext = end($file_ext);
    //         }
    //         $number = $file_ext + 1;
    //     }

    //     $ordinalNumber .= $number;

       
      
    //     $sql = "INSERT mail_ordrinal_number (id_mail, ordinal_number)
    //             VALUES ((SELECT id FROM maitous_no_pakiet WHERE topic = '$this->subject' AND data = '$date'), 
    //             :ordinal_number)";

    //     $db = static::getDB();
    //     $stmt = $db->prepare($sql);

    //     $stmt->bindValue(':ordinal_number', $ordinalNumber, PDO::PARAM_STR);

    //     $result = $stmt->execute();

    //     $this->sendConfirmationEmail($ordinalNumber);
    
        
    //     return $result;

    // }

    public function getLastOrdinalNumber(){
        $sql = "SELECT ordinal_number FROM mail_ordrinal_number ORDER BY id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetchAll();

        return $results;

    }

    // public function sendConfirmationEmail($ordinalNumber)
    // {
    //     $text = View::getTemplate('MailToUs/_confirmation.txt', [
            
    //         'notification'=>$ordinalNumber
    //      ]);
 
    //      $html = View::getTemplate('MailToUs/_confirmation.html', [
         
    //         'notification'=>$ordinalNumber
    //      ]);

    //     $mail = new Mail;

    //     $ourEmail = $this->emailFromUser;
    //     if($mail->send($ourEmail, "CENTRUM POMOCY - Potwierdzenie zgloszenia", $text, $html))
    //     {
    //         return true;
    //     }
    // }
  
    protected function saveMailToDB2($id, $file, $date)
    {
        
        $sql = 'INSERT INTO mailtous (id_user, topic, text, data)
        VALUES (:id_user, :topic, :text, :data)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id_user', $id, PDO::PARAM_INT);
        $stmt->bindValue(':topic', $this->subject, PDO::PARAM_STR);
        $stmt->bindValue(':text', $this->message, PDO::PARAM_STR);
        $stmt->bindValue(':data', $date, PDO::PARAM_STR);
        
        $result = $stmt->execute();
        if($file[0]['tmp_name'] != null || $file[0]['tmp_name'] !=''){

            for ($i = 0; $i <count($file); $i++) {
         
                $this->saveAttachments($file[$i], $date);
            }
        }   
        
        return $result;
    }

    protected function saveMailToDB3($file, $date)
    {
        
      
        $sql = 'INSERT INTO maitous_no_pakiet (email, domain, name, surname, topic, text_box, data, id_cat)
        VALUES (:email, :domain, :name, :surname, :topic, :text_box, :data, :id_cat)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':email', $this->emailFromUser, PDO::PARAM_STR);
        $stmt->bindValue(':domain', $this->domain, PDO::PARAM_STR);
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':surname', $this->surname, PDO::PARAM_STR);
        $stmt->bindValue(':topic', $this->subject, PDO::PARAM_STR);
        $stmt->bindValue(':text_box', $this->message, PDO::PARAM_STR);
        $stmt->bindValue(':data', $date, PDO::PARAM_STR);
        $stmt->bindValue(':id_cat', $this->categorie, PDO::PARAM_STR);
        
        $result = $stmt->execute();
       

        if($file[0]['tmp_name'] != null || $file[0]['tmp_name'] !=''){

            for ($i = 0; $i <count($file); $i++) {
         
                $this->saveAttachments2($file[$i], $date);
                
            }
        }   

       
        return $result;
    }
    
    protected function saveAttachments2($file, $date){
        
       ;
        
        $sql = "INSERT INTO maitous_no_pakiet_attachments (id_mail, attachments, attachments_name)
        VALUES ((SELECT id FROM maitous_no_pakiet WHERE topic = '$this->subject' AND data = '$date'), 
        :attachments, :attachments_name)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':attachments', $file['newName'], PDO::PARAM_STR);
        $stmt->bindValue(':attachments_name', $file['name'], PDO::PARAM_STR);
 
        return $stmt->execute();

    }

    protected function saveCat($date)
    {
        
        $sql = "INSERT INTO mailtous_category (id_mail, id_cat)
        VALUES ((SELECT id FROM mailtous WHERE topic = '$this->subject' AND data = '$date'), :id_cat)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id_cat', $this->categorie, PDO::PARAM_INT);
        
 
        return $stmt->execute();
    }

    protected function saveAttachments($file, $date){
      

        $sql = "INSERT INTO mailtous_attachments (id_mail, attachments, attachments_name)
        VALUES ((SELECT id FROM mailtous WHERE topic = '$this->subject' AND data = '$date'), :attachments, :attachments_name)";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':attachments', $file['newName'], PDO::PARAM_STR);
        $stmt->bindValue(':attachments_name', $file['name'], PDO::PARAM_STR);
 
        return $stmt->execute();

    }


    protected function saveMailToDB($id, $nameFile)
    {
        if ($nameFile == "") {
            $nameFile = null;
        }

        $sql = 'INSERT INTO mailtous (id_user, topic, text, attachments )
        VALUES (:id_user, :topic, :text, :attachments)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id_user', $id, PDO::PARAM_INT);
        $stmt->bindValue(':topic', $this->subject, PDO::PARAM_STR);
        $stmt->bindValue(':text', $this->message, PDO::PARAM_STR);
        $stmt->bindValue(':attachments', $nameFile, PDO::PARAM_STR);
  
        return $stmt->execute();
        //dodanie metody zapisujace zalaczniki w petli do danego id maila
    }

    public static function getAllMailById($id)
    {
        //$sql = "SELECT * FROM mailtous WHERE id_user = '$id' ORDER BY data DESC ";

        $sql = "SELECT u.id as user_id, u.email as email,  m.id as mail_id, m.topic, m.text, m.data, 
        m.allocated, ma.attachments
        FROM  user as u
        INNER JOIN mailtous as m  ON m.id_user = u.id
        LEFT JOIN mailtous_attachments as ma ON m.id = ma.id_mail
        WHERE u.id = '$id'
        GROUP BY m.id ORDER BY data DESC";


        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetchAll();

        foreach($results as $row)
        {
           
            $data = new \DateTime($row->data);
            $row->data = $data->format('Y-m-d H:i');
           
        }
       

        return $results;
    }

    public static function getMailById($id)
    {
        $sql = "SELECT * FROM customer_domain  
        LEFT JOIN mailtous ON customer_domain.id_user = mailtous.id_user
        WHERE mailtous.id = '$id'";

        // $sql = "SELECT dom.name_domain, m.id as mail_id, m.topic, m.text, m.data, 
        // m.allocated, ma.attachments, ma.attachments_name
        // FROM  customer_domain as dom
        // LEFT JOIN mailtous as m  ON m.id_user = dom.id_user
        // LEFT JOIN mailtous_attachments as ma ON m.id = ma.id_mail
        // WHERE m.id = $id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetch();
        return $results;
    }

    public static function getMailByIdCentrum($id)
    {
        $sql = "SELECT * FROM maitous_no_pakiet  
        WHERE id = '$id'";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetch();
        return $results;

      
       
        exit;
    }
    

    public static function getMailById2($id)
    {
        $sql = "SELECT * FROM mailtous  
        WHERE id = '$id'";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetch();
        return $results;
    }

    public static function getAttachmentsByIdMail($id)
    {
        $sql = "SELECT m.id as id_mail, m.id_user, m.topic, m.text,  m.allocated, m.data, ma.attachments, ma.attachments_name
        FROM  mailtous as m 
        LEFT JOIN mailtous_attachments as ma ON m.id = ma.id_mail
        WHERE m.id = $id GROUP BY ma.id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetchAll();
        return $results;
    }

    public static function getAttachmentsByIdMailCentrum($id)
    {
        $sql = "SELECT *
        FROM  maitous_no_pakiet_attachments as ma
        WHERE ma.id_mail = $id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetchAll();
        return $results;
    }

    public static function getAllMail()
    {
        $sql = "SELECT u.id as user_id, u.email as email, dom.name_domain, m.id as mail_id, m.topic, m.text, m.data, 
        m.allocated, ma.attachments
        FROM  customer_domain as dom
        RIGHT JOIN user as u ON u.id = dom.id_user
        INNER JOIN mailtous as m  ON m.id_user = u.id
        LEFT JOIN mailtous_attachments as ma ON m.id = ma.id_mail
        GROUP BY m.id
        
        ORDER BY data DESC";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetchAll();

        foreach($results as $row)
        {
           
            $data = new \DateTime($row->data);
            $row->data = $data->format('Y-m-d H:i');
          
        }
        return $results;
    }

    public static function getAllMailCentrum()
    {
        $sql = "SELECT m.*, ma.attachments
        FROM  maitous_no_pakiet as m
        LEFT JOIN maitous_no_pakiet_attachments as ma ON m.id = ma.id_mail
        GROUP BY m.id
        
        ORDER BY data DESC";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetchAll();
       
        foreach($results as $row)
        {
           
            $data = new \DateTime($row->data);
            $row->data = $data->format('Y-m-d H:i');
          
        }
        return $results;
    }


    // public static function findByPasswordReset($token)
    // {
    //     $token = new Token($token);
    //     $hashed_token = $token->getHash();
        
        
    //     $sql = "SELECT * FROM user WHERE password_rest_hash =:token_hash ";

    //     $db = static::getDB();
    //     $stmt = $db->prepare($sql);

    //     $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);

    //     $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

    //     $stmt->execute();

    //     $user =  $stmt->fetch();
       
    //     if ($user) {
    //         if (strtotime($user->password_reset_exp) > time()) {
    //             return $user;
    //         }
    //     }
    // }

    public function resetPassword($password, $password2)
    {
        $this->password = $password;
        $this->password2 = $password2;

        $this->validate();

        if (empty($this->errors)) {
            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = "UPDATE user SET password = :password,
            password_rest_hash = NULL, password_reset_exp = NULL
            WHERE id = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
    
            return $stmt->execute();
        }
        return false;
    }

    /**
     * END RESET PASWORD
     */

     public function addMessages($file_array)
     {
        $this->validateEmail();
       
        if(empty($this->errors))
        {
            $sql = "INSERT INTO messages (topic, text_box)
            VALUES (:topic, :text)";
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);
    
            $stmt->bindValue(':topic', $this->subject, PDO::PARAM_STR);
            $stmt->bindValue(':text', $this->message, PDO::PARAM_STR);
         
            $result = $stmt->execute();
    
           
            if($file_array[0]['tmp_name'] != null || $file_array[0]['tmp_name'] !=''){
                
                for($i = 0; $i<count($file_array); $i++)
                {
                    $file_ext = explode('.', $file_array[$i]['name']);
                    $file_ext = end($file_ext);
                    $newNameFile = uniqid('', true).".".$file_ext;
                    $file_array[$i]['newName']= $newNameFile;
    
                    $dirpath = realpath(dirname(getcwd()));
                    move_uploaded_file($file_array[$i]['tmp_name'], "$dirpath/private_html/message-attachments/".$file_array[$i]['newName']);
                    
                    $sql = "INSERT INTO messages_attachments (id_messages, attachments, attachments_name)
                             VALUES ((SELECT id FROM messages WHERE topic = '$this->subject' AND text_box = '$this->message'), :attachments, :attachments_name )";
                    $db = static::getDB();
                    $stmt = $db->prepare($sql);
    
                    $stmt->bindValue(':attachments', $file_array[$i]['newName'], PDO::PARAM_STR);
                    $stmt->bindValue(':attachments_name', $file_array[$i]['name'], PDO::PARAM_STR);
    
                    $stmt->execute();
                }
            }
    
            return $result;
        }
        return false;
       

    }

    public static function getAllMessages()
    {
        $sql = "SELECT ma.attachments_name, ma.attachments, m.topic, m.text_box, m.data, m.id
        FROM messages_attachments as ma
        RIGHT JOIN messages as m ON m.id = ma.id_messages
        ORDER BY m.data DESC";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetchAll();


        foreach($results as $row)
        {
           
            $data = new \DateTime($row->data);
            $row->data = $data->format('Y-m-d H:i');
          
        }
        
        return $results;
    }

    public static function getMessageById($id)
    {
        
        $sql = "SELECT  m.topic, m.text_box, m.data, m.id
        FROM messages as m
        WHERE m.id = $id
        ORDER BY m.data DESC";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetch();

     
        
        return $results;

    }

    public static function getAttachmentsMessageById($id)
    {
        $sql = "SELECT ma.attachments_name, ma.attachments
        FROM messages_attachments as ma
        WHERE ma.id_messages = $id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetchAll();
        
        return $results;
    }

    public static function getLastMessage($id)
    {
        $time = new \DateTime();

        $timeSql = clone $time;
        $timeSql = $timeSql->format('Y-m-d');
        $user = User::findById($id);
         

        $sql = "SELECT ma.attachments_name, ma.attachments, m.topic, m.text_box, m.data, m.id
        FROM messages as m
        RIGHT JOIN messages_attachments as ma ON m.id = ma.id_messages
        WHERE m.data BETWEEN '$user->date_add' AND '$timeSql'
        ORDER BY m.data DESC";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetch();
        
        
        if($results == true)
        {
            $reesultTime = new \DateTime($results->data);
       
            $interval = date_diff($time, $reesultTime);
            
    
            if($interval->m > 1)
            {
                $results->old = true;
            }else{
                $results->old = null;
            }
        }else{
            $results = new User();
            $results->old = true;
        }
        
        
        return $results;
    }

    public static function getLastMessageAdmin()
    {
        $time = new \DateTime();

        $sql = "SELECT ma.attachments_name, ma.attachments, m.topic, m.text_box, m.data, m.id
        FROM messages as m
        RIGHT JOIN messages_attachments as ma ON m.id = ma.id_messages
       
        ORDER BY m.data DESC";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $results = $stmt->fetch();
        
        
        if($results == true)
        {
            $reesultTime = new \DateTime($results->data);
       
            $interval = date_diff($time, $reesultTime);
            
    
            if($interval->m > 1)
            {
                $results->old = true;
            }else{
                $results->old = null;
            }
        }else{
            $results = new User();
            $results->old = true;
        }
      
        return $results;
    }

    public function createCategory()
    {
        $this->validate();

        if (empty($this->errors)) {
           
            $sql = "INSERT INTO categories_to_mail (name_cat) VALUES (:category)";

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':category', $this->nameCategory, PDO::PARAM_STR);
            return $stmt->execute();
        }

        
        return false;
    } 

    public static function getAllCategories()
    {
        $sql="SELECT * FROM categories_to_mail ORDER BY id_category DESC";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $results = $stmt->fetchAll();
      
        return $results;
    }

    public function editCategory($id)
    {
        $this->validate();

        if (empty($this->errors)) {
            $sql = "UPDATE categories_to_mail SET name_cat = :name WHERE id_category = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            
            $stmt->bindValue(':name', $this->nameCategory, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        }

        return false;
        
    }

    public function deleteCategory($id)
    {
        $sql = "DELETE FROM categories_to_mail WHERE id_category = :id";
            
        $db = static::getDB();
        $stmt = $db->prepare($sql);
            
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    
        return $stmt->execute();
    }

    public function getMailStats()
    {
        $sql = "SELECT m.id, m.id_user, mc.id_cat, mdc.name_cat , SUM(mc.number) as sumCat
        FROM mailtous as m
        LEFT JOIN mailtous_category as mc ON m.id = mc.id_mail
        INNER JOIN categories_to_mail as mdc ON mdc.id_category = mc.id_cat
        GROUP BY mdc.name_cat";

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

            $stmt->execute();

            $results = $stmt->fetchAll();

            
            return $results;
    }
}
