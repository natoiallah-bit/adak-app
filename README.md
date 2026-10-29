# ADAK — Gestion de l'Association des Anciens Kemkogi

Application Laravel (PHP 8.3) : membres, cartes de membre imprimables (recto/verso, QR code de vérification), cotisations et reçus.

## Installation

```sh
composer install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan adak:admin adresse@exemple.org "Nom"   # crée le compte (mot de passe saisi, jamais écrit dans le code)
php artisan serve
```

## Fonctionnement

- **Membres** : fiche, photo, catégorie, statut. Numéro unique `ADAK-AAAA-NNNN` et jeton de vérification générés automatiquement.
- **Cartes** : sélection dans *Cartes de membre*, impression une carte par page (85,6 × 54 mm) ou planche A4 (10 cartes), recto puis verso. Suivi : à imprimer, imprimée, remise, perdue. Un duplicata garde le même numéro.
- **QR code** : ouvre `/verification/{jeton}` (page publique) qui indique si la carte est valide.
- **Cotisations** : les montants par catégorie sont fixés par le bureau national dans *Paramètres*. Tant qu'un montant est vide, personne n'est « en retard ». Le blocage des cartes en cas de retard est une option activable.

## Tests

```sh
php artisan test
```
