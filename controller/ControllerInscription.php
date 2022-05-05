<?php

/**
 * Contrôleur qui gère la connexion et déconnexion.
 */
class ControllerInscription extends Controller
{
    private UtilisateurModelRepository $utilisateurRepo;
    private AvatarModelRepository $avatarrepo;

    function __construct(ModelRepositoryConfig $config)
    {
        parent::__construct($config);
        $this->avatarrepo = new AvatarModelRepository($config);
        $this->utilisateurRepo = new UtilisateurModelRepository($config, $this->avatarrepo);
    }

    function creerProfil()
    {
        $Username = filter_var($_REQUEST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
        $password = hash('sha256', filter_var($_REQUEST['mdp'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $confirmpwd = filter_var($_REQUEST['confirmerMdp'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $bio = filter_var($_REQUEST['bio'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $localisation = filter_var($_REQUEST['localisation'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $url = filter_var($_REQUEST['url'], FILTER_SANITIZE_URL);
        $choixAv = filter_var($_REQUEST['choix-avatar'], FILTER_VALIDATE_INT);


        if ($Username != null && strlen($Username) < 256 && strlen($Username) > 0)
        {
            if (strlen($password) == 64 && $password == hash('sha256', $confirmpwd))
            {
                if (strlen($bio) < 256 && strlen($bio) > 0)
                {
                    if (strlen($localisation) < 101 && strlen($localisation) > 0)
                    {

                        if ($choixAv <= 6 && $choixAv >= 1)
                        {
                            if ($this->utilisateurRepo->selectByUsernameAndHash($Username, $password) == null)
                            {
                                $NewUser = new Utilisateur(
                                    $Username,
                                    $localisation,
                                    $password,
                                    $bio,
                                    $url,
                                    0,
                                    "",
                                    $this->avatarrepo->select($choixAv)
                                );
                                $this->utilisateurRepo->Insert($NewUser);
                                $succes = "utilisateur enregistrer";
                            }
                            else
                            {
                                $erreurs[] = "utilisateur déja exisatant ";
                            }
                        }
                        else
                        {
                            $erreurs[] = "le choix d'avatar est invalide";
                        }
                    }
                    else
                    {
                        $erreurs[] = "la localisation " . $localisation . "doit être entre 1 et 100 caractères ";
                    }
                }
                else
                {
                    $erreurs[] = "la bio " . $bio . "doit être entre 1 et 255 caractères ";
                }
            }
            else
            {
                $erreurs[] = "le mots de passe entré est invalide";
            }
        }
        else
        {
            $erreurs[] = "Le usernamre " . $Username . " est invalide";
        }
        $vue = new ViewCreator('view/inscription.phtml');
        $vue->assign("erreurs", $erreurs);
        $vue->assign("info", $succes);
        echo $vue->render();
    }


    function Inscriptionpanel()
    {
        $vue = new ViewCreator('view/inscription.phtml');
        echo $vue->render();
    }

    function default()
    {
        $this->Inscriptionpanel();
    }
}
