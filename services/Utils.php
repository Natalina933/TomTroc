<?php

/**
 * Classe utilitaire : cette classe ne contient que des méthodes statiques qui peuvent être appelées
 * directement sans avoir besoin d'instancier un objet Utils.
 * Exemple : Utils::redirect('home'); 
 */
class Utils {
    /**
     * Convertit une date vers le format de type "Samedi 15 juillet 2023" en francais.
     * @param DateTime $date : la date à convertir.
     * @return string : la date convertie.
     */
    public static function convertDateToFrenchFormat(DateTime $date) : string
    {
        // Attention, s'il y a un problème lié à IntlDateFormatter, 
        // il faut activer l'extension "intl" dans le fichier php.ini.
        $dateFormatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::FULL);
        $dateFormatter->setPattern('EEEE d MMMM Y');
        return $dateFormatter->format($date);
    }

    /**
     * Formate une date relative au format "il y a X jours/semaines/mois/années".
     * @param DateTime $date : La date à formatter.
     * @return string : Une chaîne de caractères indiquant combien de temps s'est écoulé depuis la date.
     */
    public static function formatMemberSince(DateTime $date) : string
    {
        $now = new DateTime();
        $interval = $now->diff($date);

        if ($interval->y > 0) {
            return "Membre depuis " . $interval->y . " an" . ($interval->y > 1 ? "s" : "");
        } elseif ($interval->m > 0) {
            return "Membre depuis " . $interval->m . " mois";
        } elseif ($interval->d >= 7) {
            return "Membre depuis " . floor($interval->d / 7) . " semaine" . (floor($interval->d / 7) > 1 ? "s" : "");
        } elseif ($interval->d > 0) {
            return "Membre depuis " . $interval->d . " jour" . ($interval->d > 1 ? "s" : "");
        } else {
            return "Membre depuis aujourd'hui";
        }
    }

    /**
     * Redirige vers une URL.
     * @param string $action : l'action que l'on veut faire (correspond aux actions dans le routeur).
     * @param array $params : Facultatif, les paramètres de l'action sous la forme ['param1' => 'valeur1', 'param2' => 'valeur2']
     * @return void
     */
    public static function redirect(string $action, array $params = []) : void
    {
        $url = "index.php?action=$action";
        foreach ($params as $paramName => $paramValue) {
            $url .= "&$paramName=$paramValue";
        }
        header("Location: $url");
        exit();
    }

    /**
     * Cette méthode retourne le code js à insérer en attribut d'un bouton.
     * Pour ouvrir une popup "confirm", et n'effectuer l'action que si l'utilisateur
     * a bien cliqué sur "ok".
     * @param string $message : le message à afficher dans la popup.
     * @return string : le code js à insérer dans le bouton.
     */
    public static function askConfirmation(string $message) : string
    {
        return "onclick=\"return confirm('$message');\"";
    }

    /**
     * Cette méthode protège une chaîne de caractères contre les attaques XSS.
     * De plus, elle transforme les retours à la ligne en balises <p> pour un affichage plus agréable. 
     * @param string $string : la chaîne à protéger.
     * @return string : la chaîne protégée.
     */
    public static function format(string $string) : string
    {
        // Étape 1 : protéger le texte avec htmlspecialchars.
        $finalString = htmlspecialchars($string, ENT_QUOTES);

        // Étape 2 : découper par rapport aux retours à la ligne.
        $lines = explode("\n", $finalString);

        // Reconstruire en mettant chaque ligne dans un paragraphe.
        $finalString = "";
        foreach ($lines as $line) {
            if (trim($line) != "") {
                $finalString .= "<p>$line</p>";
            }
        }
        
        return $finalString;
    }

    /**
     * Cette méthode permet de récupérer une variable de la superglobale $_REQUEST.
     * Si cette variable n'est pas définie, on retourne la valeur null (par défaut)
     * ou celle qui est passée en paramètre si elle existe.
     * @param string $variableName : le nom de la variable à récupérer.
     * @param mixed $defaultValue : la valeur par défaut si la variable n'est pas définie.
     * @return mixed : la valeur de la variable ou la valeur par défaut.
     */
    public static function request(string $variableName, mixed $defaultValue = null) : mixed
    {
        return $_REQUEST[$variableName] ?? $defaultValue;
    }
}
