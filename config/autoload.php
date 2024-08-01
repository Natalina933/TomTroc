<?php

/** Système d'autoload
 * A chaque fois que PHP va avoir besoin d'une classe, il va appeler cette fonction 
 * et chercher dans les divers dossiers (ici models, controllers, views, services) s'il trouve 
 * un fichier avec le bon nom. Si c'est le cas, il l'inclut avec require_once.
 */
spl_autoload_register(function ($className) {
    $paths = ['services', 'models', 'controllers', 'views'];
    
    foreach ($paths as $path) {
        $file = __DIR__ . '/../' . $path . '/' . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            break;
        }
    }
});
