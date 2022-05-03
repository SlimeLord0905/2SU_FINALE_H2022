<?php


class ShweetModelRepository extends ModelRepository
{
    protected UtilisateurModelRepository $utilisateurRepository;
    protected AvatarModelRepository $AvatarRepository;


    public function __construct(ModelRepositoryConfig $config, AvatarModelRepository $AvatarRepository, UtilisateurModelRepository $UtilisateurRepository)
    {
        parent::__construct($config);
        $this->Config = $config;
        $this->AvatarRepository = $AvatarRepository;
        $this->utilisateurRepository = $UtilisateurRepository;
    }



    function selectAll() //: array
    {
        $_requete = "SELECT * FROM shweet ";


        $requete = $this->connexion->prepare($_requete);
        $requete->execute();

        $articles = array();
        while ($record = $requete->fetch())
        {
            $article = $this->constructshweetFromRecord($record);
            if ($article != null)
                $articles[] = $article;
        }

        return $articles;
    }
    function selectDernierShweetParent(int $id, int $limit = 20) //: ?array()
    {
        if ($id != 0)
        {
            $requete = $this->connexion->prepare("SELECT * FROM shweet WHERE auteur_id=:id LIMIT 20");
            $requete->bindValue(":id", $id);
            $requete->execute();
        }
        else
        {

            $requete = $this->connexion->prepare("SELECT * FROM shweet WHERE parent_id IS NULL LIMIT 20");
            $requete->execute();
        }

        $shweets = array();
        while ($record = $requete->fetch())
            $shweets[] = $this->constructshweetFromRecord($record);

        return $shweets;
    }
    function selectenfant() //: ?array()
    {


        $requete = $this->connexion->prepare("SELECT * FROM shweet WHERE parent_id IS NOT NULL ");
        $requete->execute();


        $shweets = array();
        while ($record = $requete->fetch())
            $shweets[] = $this->constructshweetFromRecord($record);

        return $shweets;
    }



    function insert($texte,$auteur,$parent) //:?int
    {
        $this->connexion->beginTransaction();

        $requete = $this->connexion->prepare(
            "INSERT INTO shweet(texte, auteur_id, parent_id) " .
                " VALUE(:texte,:auteur_id,:parent_id)"
        );
        $requete->bindValue(":texte", $texte);
        $requete->bindValue(":auteur_id", $auteur);
        $requete->bindValue(":parent_id", $parent);

        $requete->execute();

        $id = $this->connexion->lastInsertId();

        $this->connexion->commit();

        return $id;
    }


    function delete(int $id) //:?bool
    {
        $this->connexion->beginTransaction();

        $requete = $this->connexion->prepare("DELETE FROM shweet WHERE id=:id");
        $requete->bindValue(":id", $id);
        $requete->execute();

        $succes = $requete->rowCount() != 0;

        $this->connexion->commit();

        return $succes;
    }




    function constructshweetFromRecord($record)
    {
        return new Shweet(
            $record['id'],
            $record['texte'],
            $record['dateCreation'],
            $this->utilisateurRepository->select($record['auteur_id']),
            $record['parent_id']
        );
    }
}
