# Site de Gestion de Tickets - L'Atelier des Jeux

Ce projet est un gestionnaire de tickets fonctionnel pour la société "L'atelier des jeux". Chaque personne peut se créer un compte et donc devenir utilisateur, ces personnes pourront faire un ticket qui a un sujet, un menu déroulant pour choisir la catégorie de sa demande et une description à ajouter pour plus de précisions. Ces tickets seront reçus par le technicien sur son dashboard, il gère les tickets donc les résoudre, indiquer s'ils sont ouverts (pas traîtés), en cours, ou fermés (terminés). Et l'administrateur, lui, gère les utilisateurs, qui se connecte et quand, avec les heures précises et le pouvoir de créer, lire, modifier et supprimer un utilisateur, un technicien ou un admin.

## Fonctionnalites
- Authentification Multi-roles : Administrateur, Technicien, Utilisateur, Inactif.
- Gestion des Tickets : Créer, lire (suivi de statut (Ouvert, En cours, Fermé)), modifier et supprimer.
- Tableau de Bord Admin : Statistiques en temps réél sur les utilisateurs.
- Tableau de Bord Technicien : Statistiques en temps réél sur les tickets crééent.
- Sécurité : Hachage des mots de passe.    

## Apercu du Projet

### Interface de Connexion
<img width="1914" height="916" alt="connexion" src="https://github.com/user-attachments/assets/26d00138-6744-4a44-8ad5-108486dd6b88" />


### Interface de Création de Compte
<img width="1911" height="914" alt="creation_de_compte" src="https://github.com/user-attachments/assets/9a899c66-4976-48f1-a802-bed05155fcd9" />


## Administrateur
### Dashboard Administrateur
<img width="1909" height="916" alt="dashboard_admin" src="https://github.com/user-attachments/assets/48b22743-99e3-4ee9-a44c-bba99ed25f17" />


### Gestion des Comptes
<img width="1894" height="918" alt="gestion_utilisateurs_admin" src="https://github.com/user-attachments/assets/0a9526af-5d9f-43f8-8773-0c926a554ac0" />


### Tableau des Logs de Connexion
<img width="1909" height="914" alt="historique_de_connexions_admin" src="https://github.com/user-attachments/assets/73d10e3f-20b1-4ad5-bb8d-50f7f1f9d36a" />


### Paramètres du Compte Administrateur
<img width="1907" height="910" alt="parametres_admin" src="https://github.com/user-attachments/assets/a6382dbf-341e-4d9c-90d6-4b6a5de7e175" />


## Technicien 
### Dashboard Technicien
<img width="1911" height="911" alt="dashboard_technicien" src="https://github.com/user-attachments/assets/85400624-3baf-4624-82d7-8fcfdd26423e" />


### Vue d'un Ticket
<img width="1913" height="914" alt="ticket_technicien" src="https://github.com/user-attachments/assets/8909dbc4-8e06-4539-8388-9f5ab6f06c0d" />


### Paramètres du Compte Technicien
<img width="1912" height="912" alt="parametres_technicien" src="https://github.com/user-attachments/assets/20c76e92-72f5-46ef-a61a-edfa9378c9be" />


## Utilisateur
### Dashboard Utilisateur
<img width="1910" height="913" alt="dashboard_utilisateur" src="https://github.com/user-attachments/assets/0dcf10c9-5b17-489b-9fb2-e54a19af15ef" />


### Historique des tickets de l'Utilisateur
<img width="1908" height="912" alt="historique_tickets_utilisateur" src="https://github.com/user-attachments/assets/74ea4578-2d75-451f-87c5-92d08fe16663" />


### Paramètres du Compte Utilisateur
<img width="1909" height="911" alt="parametres_utilisateur" src="https://github.com/user-attachments/assets/8f554a63-7bbf-430d-86a4-8dded40411b0" />


## Structure de la Base de Donnees
### Les Différentes Tables
<img width="1022" height="175" alt="bdd" src="https://github.com/user-attachments/assets/39cd07f5-f0ec-4279-a990-3ce9c82ec4e2" />


### Table "tickets"
<img width="1271" height="345" alt="detail_table_tickets" src="https://github.com/user-attachments/assets/60044110-b874-4545-9629-735845122324" />


### Table "utilisateurs"
<img width="970" height="370" alt="detail_table_utilisateurs" src="https://github.com/user-attachments/assets/8ddf3edc-face-4654-9f78-08cf2204dfcf" />


## Conception de la Base de Données

La structure de données a été conçue pour garantir l'intégrité et la traçabilité des tickets. Voici le Modèle Conceptuel de Données (MCD) du projet :

![Modèle Conceptuel de Données - Gestion de Tickets](./mcd.jpeg)

### Dictionnaire de Données (utilisateur)
<img width="883" height="151" alt="ddd_utilisateurs" src="https://github.com/user-attachments/assets/a95e8ac7-37ed-4c81-b5ec-8529064bcf8c" />


### Dictionnaire de Données (tickets)
<img width="866" height="193" alt="ddd_tickets" src="https://github.com/user-attachments/assets/171ff625-473b-4fc6-8851-3f5c78ea0726" />


---

## Technologies Utilisees
- Backend : PHP
- Frontend : HTML/CSS (W3.CSS)
- Base de donnees : MySQL (XAMPP)

## Identifiants de Test
| Role | Identifiant | Mot de passe |
| :--- | :--- | :--- |
| Technicien | technicien | tech123 |
| Utilisateur | mdupont | password |


Vous pouvez également créer votre propre utilisateur en vous créeant un compte en appuyant sur "Créer un compte", en entrant votre nom, prénom, adresse mail et un mot de passe.

---

## Explication du Code

### 1. Architecture Technique
![index](index1.png)3. Interface Utilisateur5. Modification du Statut
![index](index2.png)

### 2.Gestion des Accès et Sécurité
![sécurité](sécurité.png)
![sécurité](sécurité.png)

### 3. Interface Utilisateur
![utilisateur](utilisateur.png)
![utilisateur](utilisateur2.png)

### 4. Interface Technicien
![technicien](tech1.png)
![technicien](tech1.png)

### 5. Modification du Statut
![modification](viewtech.png)
![modification](viewtech2.png)

### 6. Administration & Logs
![logs](logview.png)
![logs](logview2.png)




--- 

## Perspectives d’amélioration

Dans le futur, plusieurs améliorations peuvent être envisagées pour enrichir le projet :

-  Ajout d’un système de notifications (email ou en temps réel)
-  Ajout d’un système de priorisation des tickets  
-  Implémentation d’un chat entre utilisateur et technicien  
-  Ajout de pièces jointes dans les tickets
-  Amélioration de la sécurité (mail de confirmation, authentification à 2 facteurs)  

---

##  Conclusion

Ce projet nous a permis de :

- Comprendre la structure d’un gestionnaire de tickets (de la base de donnée au code)
- Gérer les rôles (Admin/Technicien/User)
- Implémenter un système CRUD complet
- Manipuler une base de données et les sessions

---

##  Contact
Pour toute question ou suggestion :

- https://www.linkedin.com/in/oumaima-saoui-4b0a9a387/
- https://www.linkedin.com/in/yachar22/

---

