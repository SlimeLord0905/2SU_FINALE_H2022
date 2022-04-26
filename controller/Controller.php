<?php

/**
 * Un contrôleur est responsable de gérer un ensemble d'actions qui ont un
 * lien étroit entre elles.
 * 
 * Ex: Gestion des inscriptions, gestion de l'authentification, etc.
 * 
 * Un controleur doit obligatoirement implémenter
 * une action dite par défaut : "default".
 * 
 * À la fin de l'exécution d'une action, 
 * le controleur doit générer une vue et l'afficher.
 */
abstract class Controller
{
    /**
     * Configuration qui permettra de construire les ModelRepository nécessaires
     */
    protected ModelRepositoryConfig $configBD;

    /**
     * Constructeur 
     * 
     * @param ModelRepositoryConfig $config La configuration pour construire des ModelRepository
     */
    function __construct(ModelRepositoryConfig $configBD)
    {
        $this->configBD = $configBD;
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    }


    /**
     * Action par défaut du contrôleur.
     */
    abstract function default();


    private const SEUIL_INACTIVITE = 10; // 5 minutes.
    protected function assurerUtilisateurAuthentifieValide(int $typeUtilisateur = 0)
    {
        if (
            !isset($_SESSION['utilisateur']) ||
            ($typeUtilisateur != 0 && $_SESSION['utilisateur']->getType() != $typeUtilisateur) ||
            time() - $_SESSION['derniereActivite'] > self::SEUIL_INACTIVITE
        )
        {
            $this->detruireSession();

            $vue = new ViewCreator('view/accueil.phtml');
            $vue->assign("erreurs", array("Votre session n'est plus valide. Veuillez-vous reconnecter."));
            echo $vue->render();
            exit; // Très important afin d'éviter que l'exécution continue
        }
        // Session valide, on met à jour le temps de la dernière activité
        else
        {
            $_SESSION['derniereActivite'] = time();
        }
    }



    /**
     * Méthode qui détruit la session actuelle.
     */
    protected function detruireSession()
    {
        $_SESSION = array();
        if (ini_get("session.use_cookies"))
        {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 60,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }
}
