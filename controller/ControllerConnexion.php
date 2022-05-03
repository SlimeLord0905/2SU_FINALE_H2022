<?php

/**
 * Contrôleur qui gère la connexion et déconnexion.
 */
class ControllerConnexion extends Controller
{
    private UtilisateurModelRepository $utilisateurRepo;
    private AvatarModelRepository $avatarrepo;

    function __construct(ModelRepositoryConfig $config)
    {
        parent::__construct($config);
        $this->avatarrepo = new AvatarModelRepository($config);
        $this->utilisateurRepo = new UtilisateurModelRepository($config, $this->avatarrepo);
    }

    function seConnecter()
    {
        // On récupère les infos du formulaire de connexion
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        // On calcule le hash du mot de passe
        $hash = hash('sha256', $mdp);

        // Nous tentons de récupérer l'utilisateur en BD à l'aide des infos de connexion
        $utilisateur = $this->utilisateurRepo->selectByUsernameAndHash($username, $hash);

        // On vérifie que c'est un utilisateur valide
        if (isset($utilisateur))
        {
            $_SESSION['utilisateur'] = $utilisateur;
            $_SESSION['derniereActivite'] = time();
        }
        else
        {
            $erreurs[] = "Combinaison nom utilisateur/mot de passe non valide.";
        }

        $vue = new ViewCreator('view/accueil.phtml');
        $vue->assign("erreurs", $erreurs);
        echo $vue->render();
    }

    function seDeconnecter()
    {
        $this->detruireSession();
        $vue = new ViewCreator('view/accueil.phtml');
        echo $vue->render();
    }

    function default()
    {
        $this->seDeconnecter();
    }
}
