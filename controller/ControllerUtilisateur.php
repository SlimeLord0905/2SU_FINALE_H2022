<?php


class ControllerUtilisateur extends Controller
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