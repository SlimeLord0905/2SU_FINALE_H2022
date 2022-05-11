<?php

/**
 * Contrôleur pour la gestion des blogues 
 */
class ControllerShweet extends Controller
{
    private UtilisateurModelRepository $utilisateurRepo;
    private AvatarModelRepository $avatarrepository;
    private ShweetModelRepository $ShweetRepo;


    function __construct(ModelRepositoryConfig $config)
    {
        parent::__construct($config);
        $this->avatarrepository = new AvatarModelRepository($config);
        $this->utilisateurRepo = new UtilisateurModelRepository($config, $this->avatarrepository);
        $this->ShweetRepo = new ShweetModelRepository($config, $this->avatarrepository, $this->utilisateurRepo);
    }


    function shweeter()
    {
        $texte = filter_input(INPUT_POST, 'texte', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $session =  $_SESSION['utilisateur'];
        $auteurid = $session->getid();
        $parent_id = null;

        $this->ShweetRepo->insert($texte, $session->getId(), $parent_id);

        //$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $shweets = $this->ShweetRepo->selectDernierShweetParent($auteurid);
        $shweetskids = $this->ShweetRepo->selectenfant();
        $User = $this->utilisateurRepo->select($auteurid);

        $vue = new ViewCreator("view/page.phtml");
        $vue->assign("shweets", $shweets);
        $vue->assign("enfants", $shweetskids);
        $vue->assign("utilisateur", $User);
        echo $vue->render();
    }


    function commenter()
    {
        $texte = filter_input(INPUT_POST, 'texte', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $origine = filter_input(INPUT_POST, 'profil-origine-id', FILTER_SANITIZE_NUMBER_INT);
        $session =  $_SESSION['utilisateur'];
        $auteurid = $session->getid();
        $parent_id = ($_REQUEST['parent_id']);

        $this->ShweetRepo->insert($texte, $auteurid, $parent_id);

        if ($origine == 0)
        {
            $shweets = $this->ShweetRepo->selectDernierShweetParent(0);
            $shweetskids = $this->ShweetRepo->selectenfant();


            $vue = new ViewCreator("view/accueil.phtml");
            $vue->assign("shweets", $shweets);
            $vue->assign("enfants", $shweetskids);
            echo $vue->render();
        }
        else
        {
            $shweets = $this->ShweetRepo->selectDernierShweetParent($origine);
            $shweetskids = $this->ShweetRepo->selectenfant();
            $User = $this->utilisateurRepo->select($origine);

            $vue = new ViewCreator("view/page.phtml");
            $vue->assign("shweets", $shweets);
            $vue->assign("enfants", $shweetskids);
            $vue->assign("utilisateur", $User);
            echo $vue->render();
        }
    }

    function supprimer()
    {
        $shweet =  filter_input(INPUT_POST, 'shweet-id', FILTER_SANITIZE_NUMBER_INT);
        $origine = filter_input(INPUT_POST, 'profil-origine-id', FILTER_SANITIZE_NUMBER_INT);

        $this->ShweetRepo->delete($shweet);

        if ($origine == 0)
        {
            $shweets = $this->ShweetRepo->selectDernierShweetParent(0);
            $shweetskids = $this->ShweetRepo->selectenfant();


            $vue = new ViewCreator("view/accueil.phtml");
            $vue->assign("shweets", $shweets);
            $vue->assign("enfants", $shweetskids);
            echo $vue->render();
        }
        else
        {
            $shweets = $this->ShweetRepo->selectDernierShweetParent($origine);
            $shweetskids = $this->ShweetRepo->selectenfant();
            $User = $this->utilisateurRepo->select($origine);

            $vue = new ViewCreator("view/page.phtml");
            $vue->assign("shweets", $shweets);
            $vue->assign("enfants", $shweetskids);
            $vue->assign("utilisateur", $User);
            echo $vue->render();
        }
    }

    function default()
    {
        $this->commenter();
    }

    function afficherPage($var)
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $shweets = $this->ShweetRepo->selectDernierShweetParent($id);
        $shweetskids = $this->ShweetRepo->selectenfant();
        $User = $this->utilisateurRepo->select($id);

        $vue = new ViewCreator("view/page.phtml");
        $vue->assign("shweets", $shweets);
        $vue->assign("enfants", $shweetskids);
        $vue->assign("utilisateur", $User);
        echo $vue->render();
    }
}
