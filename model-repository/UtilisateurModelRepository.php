<?php

class UtilisateurModelRepository extends ModelRepository
{

    protected AvatarModelRepository $AvatarRepository;

    public function __construct(ModelRepositoryConfig $config, AvatarModelRepository $AvatarRepository)
    {
        parent::__construct($config);
        $this->config = $config;
        $this->AvatarRepository = $AvatarRepository;
    }


    public function selectAll(): array
    {
        $s_requete = "SELECT * FROM utilisateur";
        $requete = $this->connexion->prepare("$s_requete");
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
    public function Insert(Utilisateur $user): ?int
    {
        $this->connexion->beginTransaction();

        $requete = $this->connexion->prepare(
            "INSERT INTO utilisateur(bio, localisation, url, username, hash, avatar_id) " .
                " VALUE(:bio, :localisation, :url, :username, :hash, :avatar_id)"
        );
        $requete->bindValue(":bio", $user->getBio());
        $requete->bindValue(":localisation", $user->getLocalisation());
        $requete->bindValue(":url", $user->getUrl());
        $requete->bindValue(":username", $user->getUsername());
        $requete->bindValue(":hash", $user->getHash());
        $requete->bindValue(":avatar_id", $user->getAvatar()->getId());

        $requete->execute();

        $id = $this->connexion->lastInsertId();

        $this->connexion->commit();

        $user->setId($id);
        return $id;
    }

    public function selectByUsernameAndHash($username, $hash)
    {
        $s_requete = "SELECT * FROM utilisateur WHERE username = :username AND hash = :hash";
        $requete = $this->connexion->prepare("$s_requete");
        $requete->bindValue(":username", $username);
        $requete->bindValue(":hash", $hash);
        $requete->execute();

        if ($record = $requete->fetch());
        {
            if ($record != null)
            {
                $utilisateur = $this->constructUtilisateurFromRecord($record);
            }
        }

        return $utilisateur;
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
            $this->AvatarRepository->select($record['avatar_id'])
        );
    }
}
