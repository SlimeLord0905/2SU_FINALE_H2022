<?php
/**
 * Contrôleur par défaut. Il permet d'afficher la page d'accueil.
 */
class ControllerDefault extends Controller
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


    function consulter()
    {
        $shweets = $this->ShweetRepo->selectDernierShweetParent(0);
        $shweetskids = $this->ShweetRepo->selectenfant();
        $vue = new ViewCreator("view/accueil.phtml");
        $vue->assign("shweets", $shweets);
        $vue->assign("enfants", $shweetskids);
        echo $vue->render();
    }

    function default()
    {
        $this->consulter();
    }
}
