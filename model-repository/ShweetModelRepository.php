<?php


class ShweetModelRepository extends ModelRepository
{
    private UtilisateurModelRepository $utilisateurRepository;
    private AvatarModelRepository $AvatarRepository;
    private ModelRepositoryConfig $config;

    public function __construct(ModelRepositoryConfig $config, AvatarModelRepository $AvatarRepository, UtilisateurModelRepository $UtilisateurRepository)
    {
        $this->Config = $config;
        $this->AvatarRepository = $AvatarRepository;
        $this->utilisateurRepository = $UtilisateurRepository;
    }



    function selectAll() //: array
    {
        $requete = $this->connexion->prepare("SELECT * FROM shweet WHERE parent_id IS NULL");
        $requete->execute();

        $shweets = array();
        while ($record = $requete->fetch())
            $shweets[] = $this->constructshweetFromRecord($record);

        return $shweets;
    }
    function selectDernierShweetParent(int $limit = 20, int $id) //: ?array()
    {
        $requete = $this->connexion->prepare("SELECT * FROM shweet WHERE id=:id LIMIT :limit");
        $requete->bindValue(":id", $id);
        $requete->bindValue(":limit", $limit);
        $requete->execute();

        $shweets = array();
        while ($record = $requete->fetch())
            $utilisateur[] = $this->constructshweetFromRecord($record);

        return $shweets;
    }


    function insert(Shweet $shweet) //:?int
    {
        $this->connexion->beginTransaction();

        $requete = $this->connexion->prepare(
            "INSERT INTO shweet(texte, auteur_id, parent_id) " .
                " VALUE(:texte,:auteur_id,:parent_id)"
        );
        $requete->bindValue(":texte", $shweet->getTexte());
        $requete->bindValue(":auteur_id", $shweet->getAuteur());
        $requete->bindValue(":parent_id", $shweet->getParent());

        $requete->execute();

        $id = $this->connexion->lastInsertId();

        $this->connexion->commit();

        $shweet->setId($id);
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
            $record['auteur_id'],
            $record['parent_id']
        );
    }
}
