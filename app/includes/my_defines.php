<?PHP
// *****************************
// * Database section
// *****************************

  //define('DB_SERVER', '/opt/oracle/product/9.2.0/');
  define('DB_SERVER', '');
  
  define('DB_SERVER_USERNAME', 'icargoofi');
  define('DB_SERVER_PASSWORD', 'icargoofi');


  define('DB_DATABASE', 'home');

//Encryption key is the private key used to encrypt passwords with the blowfish encryption algorithm,
//if your system supports it.  You do /NOT/ want to change this if you already have users entered.
//Only change prior to installation.  You also do not want to give this out, as it would make decryption of your password database much simpler ;)
  define('ENCRYPTION_KEY', '8aw3wb7v35n9awv48b7o8c7a4bd');

?>
