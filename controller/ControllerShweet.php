<?php

/**
 * Contrôleur pour la gestion des blogues 
 */
class ControllerShweet extends Controller
{
    private UtilisateurModelRepository $utilisateurRepo;
    private AvatarModelRepository $avatarrepository;
    private ShweetModelRepository $ShweetRepo;
    private ViewCreator $vue;
 

    function __construct(ModelRepositoryConfig $config)
    {
        parent::__construct($config);
        $this->avatarrepository = new AvatarModelRepository($config);
        $this->utilisateurRepo = new UtilisateurModelRepository($config, $this->avatarrepository);
        $this->ShweetRepo = new ShweetModelRepository($config, $this->avatarrepository, $this->utilisateurRepo);

    }


    function consulter()
    {
        $shweets = $this->ShweetRepo->selectDernierShweetParent(0);
        $vue = new ViewCreator("view/accueil.phtml");
        $vue->assign("blogues", $shweets);
        $vue->assign("utilisateur", $this->utilisateurRepo);
        $vue->assign("avatar", $this->avatarrepository);
        echo $vue->render();
    }

    function default()
    {
        $this->consulter();
    }


    
}