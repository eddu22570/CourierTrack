# CourierTrack

CourierTrack est un logiciel de gestion et de suivi de courriers, conçu pour simplifier le processus de réception, d'envoi et de suivi des courriers dans une organisation.

## Informations concernant le logiciel
- Année de création: 2025
- Numéro de version: v1.0

## Fonctionnalités

- Ajout de nouveaux courriers (entrants et sortants)
- Suivi des courriers par numéro de suivi avec notifications par mail
- Gestion des destinataires internes et externes
- Différents niveaux d'accès utilisateur (administrateur, utilisateur)
- Visualisation de la liste des courriers
- Modification et suppression de courriers (pour les administrateurs)

## Installation

1. Clonez ce dépôt sur votre serveur local ou distant.
2. Assurez-vous que Apache2, PHP et SQLite sont installés sur votre système.
3. Configurez le sendmail avec vos paramètres SMTP
4. Identifiants administrateur: admin@example.com  /mdp: admin123
   Pour éviter les attaques, pensez à modifier votre mot de passe dans l'onglet "Gestion des utilisateurs"
6. Il est fortement recommandé de créer un compte administrateur pour la ou les personnes gérant les courriers et colis ainsi qu'aux différents administrateurs du logiciel
7. Créez les comptes pour vos utilisateurs

## Configuration requise

- PHP 7.4 ou supérieur
- SQLite 3
- Windows 11
- Wampserver avec sendmail configuré

## Utilisation

1. Connectez-vous à l'application avec vos identifiants.
2. Pour ajouter un nouveau courrier, cliquez sur "Ajouter un courrier" et remplissez le formulaire.
3. Pour voir la liste des courriers, accédez à la page "Liste des courriers".
4. Les administrateurs peuvent modifier ou supprimer des courriers depuis la liste. Les utilisateurs voient seulement ceux qu'ils ont créés.

## Structure du projet

CourierTrack/
│
├── config.php
├── header.php
├── index.php
├── login.php
├── logout.php
├── ajouter_courrier.php
├── liste_courriers.php
├── modifier_courrier.php
├── supprimer_courrier.php
│
├── css/
│ └── style.css
│
├── database/
│ └── setup.sql
│
└── README.md


## Contribution

Les contributions à ce projet sont les bienvenues. N'hésitez pas à ouvrir une issue ou à soumettre une pull request.

## Licence

Ce projet est sous licence [MIT](https://opensource.org/licenses/MIT). Vous êtes libres de modifier et de redistribuer le code tant que vous conservez la notice de copyright et de licence.

## Auteur

Conçu par eddu22570.

## Captures d'écran

![Capture d'écran 2025-03-18 233155](https://github.com/user-attachments/assets/e7e2382d-e891-4b39-b189-62d9a0f6f9b1)

![Capture d'écran 2025-03-18 233358](https://github.com/user-attachments/assets/dfb03038-46b2-49e3-947b-7bc9b415f0ab)

## FAQ

- **Puis-je utiliser CourierTrack dans un projet commercial ?**
  Oui, la licence MIT permet une utilisation commerciale.
- **Comment ajouter de nouveaux utilisateurs ?**
  Vous pouvez ajouter de nouveaux utilisateurs via la page d'administration. Pour cela, connectez vous en administrateur et allez dans l'onglet "Ajouter un utilisateur". Remplissez les différents champs et mettez la valeur "Rôle" sur Utilisateur.


