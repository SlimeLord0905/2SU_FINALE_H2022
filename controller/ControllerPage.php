<?php


class Controllerpage extends Controller
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

    function afficherProfil()
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

    function default()
    {
        return $this->afficherProfil();
    }
}