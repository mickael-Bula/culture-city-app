# INSTALLATION TAILWIND
## Docs

Install Tailwind : https://tailwindcss.com/docs/installation/using-postcss
Infos ici : https://blog.rherault.fr/utiliser-tailwindcss-avec-symfony/
infos ici : https://symfony.com/doc/current/frontend/encore/postcss.html
markdown : https://www.markdownguide.org/basic-syntax#links



## installer webpack encore

``` bash
composer require symfony/webpack-encore-bundle
yarn install

```

Deux nouveaux fichiers créés
- `webpack.config.js`, c’est dans ce fichier que tu vas configurer Webpack (ajouter le support de Sass par exemple)
- `assets/`, c’est ici que tout tes fichiers CSS et JS seront.


## Les commandes de Webpack
Webpack Encore met à disposition plusieurs commandes pratiques pour compiler notre code :

`yarn dev`, celui que l’on vient d’utiliser, sert à compiler notre CSS/JS en mode développement
`yarn watch`, très pratique, sert à compiler automatiquement à chaque changement sur un fichier source
`yarn build`, pour compiler ses fichiers en production.


## Installer Sass

Dans  `webpack.config.js` :
```
     // Enable Sass Support
    .enableSassLoader()
```

faire un   `yarn dev`

faire :
```
yarn add sass-loader@^12.0.0 sass --dev
```

renommer fichier assets/app.css en app.scss 
modifier import './styles/app.css' de ton fichier app.js.

## Installer et configurer Tailwind CSS

### Installer PostCSS

Pour utiliser PostCSS, il faut tout d’abord l’installer : (autoprefixer est un plugin bien utile pour ajouter automatique les prefix navigateurs à ton CSS)


```
yarn add postcss-loader autoprefixer --dev

```
Créer un fichier `postcss.config.js` à la racine de ton projet (au même niveau que `webpack.config.js`) 
y copier ceci

```
module.exports = {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  }
}
```

Ajouter cette ligne dans webpack.config.js

```
 // Enable PostCSS Support
    .enablePostCssLoader()
```

Initialiser le fichier de config de tailwind `tailwind.config.js` 

```
npx tailwindcss init
```

Dans `tailwind.config.js` 

```
module.exports = {
  content: [],
  theme: {
    extend: {},
  },
  plugins: [],
}

```

Dans le fichier CSS `css/style` principal du projet et dans app.scss ajouter:

```

@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";

```