<?php
namespace App\Service;


class AuthentificationManager{

    // Génération d'une chaine aléatoire
    public function chaine_aleatoire($nb_car, $chaine = 'azertyuiopqsdfghjklmwxcvbn123456789')
    {
        $nb_lettres = strlen($chaine) - 1;
        $generation = '';
        for($i=0; $i < $nb_car; $i++)
        {
            $pos = random_int(0, $nb_lettres);
            $car = $chaine[$pos];
            $generation .= $car;
        }
        return $generation;
    }
    public function verifMdpSecurise(string $password)
    {
        if (strlen($password)<8){
            $message = "mot de passe trop court";
        }
        else{

            if (ctype_upper($password)){
                $message = "le mot de passe ne contient que des majuscules";
            }
            else{
                if (ctype_lower($password)){
                    $message = "le mot de passe ne contient que des minuscules";
                }
                else{
                    if (ctype_alpha($password)){
                        $message = " le mot de passe ne possède que des caractère letter";
                    }
                    else{
                          if (ctype_alnum($password)){
                              $message = "le mot de passe ne possède pas de caratère spéciaux";
                          }
                          else{
                              $message="bon";
                          }
                    }
                }
            }

        }
        return $message;
    }



}


?>

