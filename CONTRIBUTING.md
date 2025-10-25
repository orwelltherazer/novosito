# Guide de contribution à NovoSito CMS

Merci de votre intérêt pour contribuer à NovoSito CMS ! Nous accueillons avec plaisir toutes les contributions qui améliorent le projet.

## Comment contribuer

### Signaler un bug

Si vous trouvez un bug, veuillez ouvrir une issue sur GitHub avec :

1. **Titre clair** : Décrivez brièvement le problème
2. **Description détaillée** :
   - Étapes pour reproduire le bug
   - Comportement attendu
   - Comportement actuel
   - Captures d'écran si pertinent
3. **Environnement** :
   - Version de PHP
   - Version de MySQL/MariaDB
   - Système d'exploitation
   - Navigateur web

### Proposer une fonctionnalité

Pour proposer une nouvelle fonctionnalité :

1. Vérifiez qu'elle n'existe pas déjà dans les issues
2. Ouvrez une issue avec le tag `enhancement`
3. Décrivez clairement :
   - Le problème que cette fonctionnalité résout
   - Comment elle devrait fonctionner
   - Des exemples d'utilisation

### Soumettre une Pull Request

1. **Fork le projet**

```bash
git clone https://github.com/votre-username/novosito.git
cd novosito
```

2. **Créez une branche pour votre fonctionnalité**

```bash
git checkout -b feature/ma-super-fonctionnalite
```

3. **Effectuez vos modifications**

- Suivez les conventions de code du projet
- Commentez votre code si nécessaire
- Testez vos modifications

4. **Committez vos changements**

```bash
git add .
git commit -m "Ajout de ma super fonctionnalité"
```

Format des messages de commit :
- `feat: Description` pour une nouvelle fonctionnalité
- `fix: Description` pour une correction de bug
- `docs: Description` pour la documentation
- `style: Description` pour le formatage
- `refactor: Description` pour du refactoring
- `test: Description` pour les tests
- `chore: Description` pour les tâches de maintenance

5. **Poussez vers votre fork**

```bash
git push origin feature/ma-super-fonctionnalite
```

6. **Ouvrez une Pull Request**

- Donnez un titre clair
- Décrivez ce que fait votre PR
- Référencez les issues liées

## Standards de code

### PHP

- **PSR-12** pour le style de code
- **PHP 7.4+** minimum
- **Typage strict** recommandé
- **PHPDoc** pour documenter les méthodes

Exemple :

```php
<?php

namespace App\Controllers;

use Core\Controller;

class MyController extends Controller {
    /**
     * Description de la méthode
     *
     * @param string $param Description du paramètre
     * @return void
     */
    public function myMethod(string $param): void {
        // Code ici
    }
}
```

### JavaScript

- **ES6+** recommandé
- **Commentaires JSDoc** pour les fonctions complexes
- **Noms descriptifs** pour les variables et fonctions

Exemple :

```javascript
/**
 * Description de la fonction
 * @param {string} param - Description du paramètre
 * @returns {boolean} Description du retour
 */
function myFunction(param) {
    // Code ici
}
```

### CSS

- **Classes BEM** pour la structure
- **Mobile-first** pour le responsive
- **Commentaires** pour les sections importantes

Exemple :

```css
/* Section: Navigation */
.navigation {
    /* Styles */
}

.navigation__item {
    /* Styles */
}

.navigation__item--active {
    /* Styles */
}
```

## Structure des modules

Chaque module doit suivre cette structure :

```
modules/
└── monmodule/
    ├── MonmoduleModule.php  (Classe principale)
    ├── README.md            (Documentation)
    └── assets/              (Optionnel)
        ├── css/
        ├── js/
        └── img/
```

La classe du module doit :
- Étendre `Core\Module`
- Implémenter `render()`
- Implémenter `renderEditForm()`
- Implémenter `validate()`

## Tests

Actuellement, NovoSito n'a pas de suite de tests automatisés, mais nous encourageons :

1. **Tests manuels** de toutes les fonctionnalités modifiées
2. **Vérification multi-navigateurs** (Chrome, Firefox, Safari, Edge)
3. **Tests responsive** (mobile, tablette, desktop)
4. **Vérification de compatibilité** PHP 7.4, 8.0, 8.1, 8.2

## Documentation

Toute nouvelle fonctionnalité doit être documentée :

1. **README.md** : Mise à jour si nécessaire
2. **CHANGELOG.md** : Ajout de l'entrée appropriée
3. **Commentaires dans le code** : PHPDoc, JSDoc
4. **Guide utilisateur** : Si pertinent

## Checklist avant soumission

Avant de soumettre votre PR, vérifiez :

- [ ] Le code suit les conventions du projet
- [ ] Tous les fichiers sont bien formatés
- [ ] Les nouvelles fonctionnalités sont documentées
- [ ] Les tests manuels sont passés
- [ ] Aucune erreur PHP/JavaScript dans la console
- [ ] Le CHANGELOG.md est mis à jour
- [ ] Les commits sont bien nommés
- [ ] La PR a un titre et une description clairs

## Code de conduite

En participant à ce projet, vous acceptez de respecter notre code de conduite :

- **Respect** : Soyez respectueux envers tous les contributeurs
- **Constructivité** : Donnez des retours constructifs
- **Inclusion** : Accueillez les nouveaux contributeurs
- **Professionnalisme** : Maintenez un environnement professionnel

## Licence

En contribuant à NovoSito CMS, vous acceptez que vos contributions soient sous licence MIT, la même licence que le projet.

## Questions

Si vous avez des questions :

- Ouvrez une issue avec le tag `question`
- Consultez les issues existantes
- Contactez les mainteneurs

## Merci !

Merci de contribuer à NovoSito CMS et d'aider à créer un meilleur outil pour tous ! 🎉
