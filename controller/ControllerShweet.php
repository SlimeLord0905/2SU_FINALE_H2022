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
        $auteurid = 1;
        $parent_id = null;
        
        $this->ShweetRepo->insert($texte,$auteurid,$parent_id);

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
        $auteurid = 1;
        $parent_id = ($_REQUEST['parent_id']) ;
        $localisation = ($_REQUEST['localisation']);

        $this->ShweetRepo->insert($texte,$auteurid,$parent_id);
        
        if($localisation == 2)
        {
            //$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $shweets = $this->ShweetRepo->selectDernierShweetParent($auteurid);
            $shweetskids = $this->ShweetRepo->selectenfant();
            $User = $this->utilisateurRepo->select($auteurid);

            $vue = new ViewCreator("view/accueil.phtml");
            $vue->assign("shweets", $shweets);
            $vue->assign("enfants", $shweetskids);
            $vue->assign("utilisateur", $User);
            echo $vue->render();
        }
        else
        {
            $id = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_NUMBER_INT);
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