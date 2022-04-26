<?php


class UtilisateurModelRepository extends ModelRepository
{
    private ModelRepositoryConfig $config;
    private AvatarModelRepository $AvatarRepository;

    public function __construct(ModelRepositoryConfig $config, AvatarModelRepository $AvatarRepository)
    {
        $this->Config = $config;
        $this->AvatarRepository = $AvatarRepository;
    }


    public function selectAll(): array
    {
        $requete = $this->connexion->prepare("SELECT * FROM utilisateur");
        $requete->execute();

        $utilisateurs = array();
        while ($record = $requete->fetch())
            $utilisateurs[] = $this->constructUtilisateurFromRecord($record);

        return $utilisateurs;
    }


    /**
     * @param string $id L'identifant unique de l'utilisateur à sélectionner.
     * @return Utilisateur L'utilisateur si trouvé, sinon null.
     */
    public function select($id): ?Utilisateur
    {
        $requete = $this->connexion->prepare("SELECT * FROM utilisateur WHERE id=:id");
        $requete->bindValue(":id", $id);
        $requete->execute();

        $utilisateur = null;
        if ($record = $requete->fetch())
            $utilisateur = $this->constructUtilisateurFromRecord($record);

        return $utilisateur;
    }
    public function Insert(Utilisateur $user) : ?int
    {
        $this->connexion->beginTransaction();

        $requete = $this->connexion->prepare(
            "INSERT INTO utilisateur(bio, localisation, url, username, hash, avatar_id) " .
                " VALUE(:bio, :localisation, :url, :username, :hash, :avatar_id)"
        );
        $requete->bindValue(":bio", $user->getID());
        $requete->bindValue(":localisation", $user->getID());
        $requete->bindValue(":url", $user->getID());
        $requete->bindValue(":username", $user->getID());
        $requete->bindValue(":hash", $user->getID());
        $requete->bindValue(":avatar_id", $user->getID());

        $requete->execute();

        $id = $this->connexion->lastInsertId();

        $this->connexion->commit();

        $user->setId($id);
        return $id;
    }


    private function constructUtilisateurFromRecord($record): ?Utilisateur
    {
        return new Utilisateur(
            $record['username'],
            $record['localisation'],
            $record['hash'],
            $record['bio'],
            $record['url'],
            $record['id'],
            $record['aRejointLe'],
            $record['avatar_id']
        );
    }
    
}