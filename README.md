# README - Projecte Xarxa Social

## Descripció del Projecte
Aquest projecte final de la unitat formativa consisteix en la definició i creació d'una xarxa social que permeti posar en pràctica els conceptes apresos durant el curs. El projecte inclou el disseny i desenvolupament d'un sistema de registre i inici de sessió per als usuaris, amb un enfocament en la funcionalitat i la seguretat.

---

## Definició de la Xarxa Social

### Públic Objectiu
La xarxa social està dirigida a:
- Persones interessades en connectar amb altres usuaris per trobar amistats, relacions o col·laboracions.
- Edats compreses entre 18 i 35 anys.
- Usuaris que valoren la senzillesa i funcionalitat en una aplicació de cites i connexió.

### Informació d’Usuari
A més de la informació bàsica requerida (nom d’usuari, correu electrònic i contrasenya), els usuaris podran afegir:
- Nom i cognoms (opcionals).
- Descripció breu sobre ells mateixos.
- Interessos i aficions.
- Foto de perfil.
- Localització (opcional).

### Interacció i Relacions
Els usuaris podran interactuar de la següent manera:
- Buscar altres usuaris amb filtres basats en interessos i localització.
- Enviar i rebre "matches" per indicar interès mutu.
- Xatejar amb altres usuaris un cop s'hagi establert un "match".

### Contingut que es Pot Pujar
Els usuaris podran pujar:
- Fotografies de perfil i galeria personal (fins a 5 imatges per usuari).
- Descripcions personalitzades.

---

## Funcionalitats Principals

### Sistema de Registre
- Verificació que el nom d’usuari i el correu electrònic no existeixin prèviament.
- Emmagatzematge segur de la contrasenya mitjançant hashing (password_hash()).
- Assignació de data i hora d’alta a la base de dades.

### Sistema d'Inici de Sessió
- Verificació de les credencials introduïdes (nom d’usuari/correu i contrasenya).
- Validació que el compte estigui actiu.
- Actualització de la data i hora de l'ultima connexió.
- Gestor de sessions i cookies.

### Pàgina Principal (Home)
- Mostrar la benvinguda personalitzada amb el nom de l’usuari.
- Opcions de tancar sessió i accés a les funcionalitats de la xarxa social.

---

## Consideracions Tècniques

### Requisits del Portal Web
- **Responsive Design:** Compatible amb dispositius mòbils i escriptoris.
- **Logo Creatiu:** Inclou un logo personalitzat utilitzant Google Fonts.
- **Imatge de Fons:** Una imatge representativa extreta de fonts com Pixabay o Unsplash.
- **Formularis:**
  - Formulari de registre: Inclou camps obligatoris i opcionals.
  - Formulari d'inici de sessió: Senzill amb username/correu i contrasenya.

### Requisits de Base de Dades
- Base de dades MySQL/MariaDB amb la taula `users` que conté:
  - `iduser`: Clau primària autoincrementable.
  - `mail`: Correu electrònic únic.
  - `username`: Nom d’usuari únic.
  - `passHash`: Hash de la contrasenya.
  - `userFirstName` i `userLastName`: Nom i cognoms (opcions).
  - `creationDate`: Data de registre.
  - `lastSignIn`: Data i hora de la última connexió.
  - `active`: Estat del compte (1 actiu, 0 inactiu).

### Seguretat
- Hashing de contrasenyes amb `password_hash()`.
- Validació del costat del servidor per evitar XSS i injeccions SQL.
- Sessós i cookies segures.

---

## Millores Futures
- Implementació de notificacions.
- Afegir compatibilitat amb xarxes socials externes per iniciar sessió.
- Sistema de recomanacions basat en interessos.
- Mètriques i estadístiques d’ús de la xarxa social.

---

Aquest README serveix com a guia per entendre el funcionament bàsic del projecte i la seva estructura. Estem oberts a suggeriments i millores!
