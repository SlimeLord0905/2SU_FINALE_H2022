<?php


class AvatarModelRepository extends ModelRepository
{

    
    public function __construct(ModelRepositoryConfig $config)
    {
        $this->Config = $config;
        parent::__construct($config);
    }
    function SelectAll()
    {
        $requete = $this->connexion->prepare("SELECT * FROM avatar");
        $requete->execute();

        $avatar = array();
        while ($record = $requete->fetch())
            $avatar[] = $this->constructAvatarFromRecord($record);

        return $avatar;
    }

    function Select($id)
    {
        $requete = $this->connexion->prepare("SELECT * FROM avatar WHERE id=:id");
        $requete->bindValue(":id", $id);
        $requete->execute();

        if ($record = $requete->fetch())
        {
            $avatar= $this->constructAvatarFromRecord($record);
        }


        return $avatar;
    }


    private function constructAvatarFromRecord($record): ?Avatar
    {
        return new Avatar($record['id'], $record['cheminImage']);
    }
}
