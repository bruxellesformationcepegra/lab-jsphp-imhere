# "I'm here !"

## Pitch
L’objectif de cet exercice est de réaliser une page web qui va permettre à un utilisateur de partager sa position, via une carte Google Maps, à qui il le souhaite, via un envoi de mail (preview sur http://imhere.azurewebsites.net/) 

Cet exercice est découpé en 2 parties : 
1.	**Le côté client**
    
    Vous allez devoir intégrer une carte Google Maps en JavaScript. Lorsque que l’utilisateur confirme sa position, un formulaire lui permet de préciser son mail, le mail du destinataire et un message.
Lorsque l’utilisateur confirme l’envoi de sa position, ces données ainsi qu’une version image de la carte (et de sa position sur celle-ci) sont envoyées vers une page PHP via un appel AJAX. 
2.	**Le côté serveur**

    Une page PHP va pouvoir recevoir ces données, créer et envoyer un mail au destinataire précisé. Ce mail contiendra l’image de la carte (et la position de l’expéditeur) et le message. 
La page PHP renvoie un statut en JSON pour confirmer que l’envoi à fonctionné ou non.

Cet exercice peut aussi réalisé avec du Node.js côté serveur bien évidemment, mais les instructions données ci-dessous concernent du PHP.

## Liste des tâches
### On commence **côté client**
1.	Intégrer le plug-in gmaps.js (https://hpneo.github.io/gmaps) pour qu’il prenne 100% de la hauteur et de la largeur de la page.
2.	Préparer un formulaire (caché à l’affichage de la page) qui doit pouvoir s’afficher par-dessus la carte (sous forme d’overlayer) et qui doit comprendre les champs suivants :
    - Votre e-mail (input)
    - E-mail de votre ami (input)
    - Message (textarea)
    - Un bouton d’envoi
3.	Détecter si le navigateur supporte la géolocalisation
    - Si oui : récupérer les coordonnées renvoyées par le navigateur
    - Si non : définir des coordonnées par défaut – vous pouvez utiliser ce site http://www.gps-coordinates.net/ 
4.	Centrer la carte sur ces coordonnées et ajouter un « marker » aux mêmes coordonnées. Le « marker » doit être déplaçable (drag)
5.	Pour permettre de confirmer la position, définissez un bouton en HTML dans la propriété « infoWindow » de la carte. 
6.	Au clic sur ce bouton (attention, rappelez-vous, il n’existera pas au démarrage de la page) :
    - Récupérer l’URL de l’image de la carte via la fonctionnalité « Static Map » et le stocker dans une variable.
    - Afficher votre formulaire en overlayer.
6.  Le marker peut être déplacé. Il faut donc récupérer les nouvelles coordonnées une fois qu'il est déplacé par le visiteur. Pour ce faire, regardez du côté de l'event `dragEnd`
7.	Lors de l’envoi des données du formulaire, préparer une requête AJAX qui va envoyer les données suivantes en **POST** à la page « sendmail.php » :
     -url : l’URL de l’image de la carte
    - message : le message du textarea
    - youremail : la valeur du champ « Votre e-mail »
    - friendemail : la valeur du champ « E-mail de votre ami »
 
### On passe **côté serveur**
1.	Créez, au même niveau que votre page HTML, une page « sendmail.php »
2.	Faites un include de 
    - PHPMailer (https://github.com/PHPMailer/PHPMailer)
    - json_response.php qui se trouve dans ce repository
3.	Récupérez les données envoyées en POST via l’appel AJAX :
    - url 
    - message 
    - youremail 
    - friendemail 
4.	Utilisez PHPMailer pour envoyer un mail
    - Expéditeur : valeur de « youremail »
    - Destinataire : valeur de « friendemail »
    - Body : valeur de « url » encadrée dans une balise <img> + la valeur de « message »
5.	Si le mail a pu être envoyé, appelez la fonction `json_success()`
6.	Si le mail n’a pu être envoyé, appelez la fonction `json_mail_error($mail->ErrorInfo)` (où `$mail` est l’objet PHPMailer)

### On repasse **côté client**
1.	Gérez le retour de la requête AJAX avec l’aide des événements « done » (dans le cas où ça a marché) et « fail » (dans le cas où ça n’a pas marché), de cette manière

```javascript

        $.ajax(
           {
             //...
           }
       )
       .done(function(){
           //ça a marché
       })
       .fail(function(xhr){
            //l'envoi n'a pas marché
       });   
```

2.	Si ça a marché, affichez un message de confirmation – vous pouvez utiliser le plugin SweetAlert http://t4t5.github.io/sweetalert/ 
3.	Si ça n’a pas marché, le détail de l’erreur se trouve dans la variable xhr : `xhr.responseText`
Cependant, ce champ est en JSON. Pour pouvoir l’utiliser, il faut transformer ce JSON en un objet JavaScript. Vous ferez donc ceci
```javascript
var err = JSON.parse(xhr.responseText);
```
La variable err contient 2 champs : « message » qui est la cause de l’erreur et « detail » qui comprend plus d’information sur l’erreur.

**That's all !** 

### Config mail

Pour envoyer le mail avec PHPMailer, vous devez préciser quel serveur mail à utiliser. 
Si vous utilisez OVH, vous devrez utiliser ces infos
```php
$mail->Host = "smtp.trucmuch.be"; //changer ceci par adresse du serveur SMTP de OVH
$mail->SMTPAuth = true; 
$mail->Username = 'brol@trucmuch.be'; //Nom d'utilisateur pour le SMTP
$mail->Password = 'monsuperpassword'; //Mot de passe pour le SMTP
$mail->Port = 587;     
```
Alternativement, vous pouvez utiliser SendGrid, une plate-forme d’envoi d’e-mails qui possède un plugin PHP : https://sendgrid.com/docs/Integrate/Code_Examples/php.html

