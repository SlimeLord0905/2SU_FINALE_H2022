<?php

class Utilisateur
{
    private int $id;
    private string $bio;
    private string $url;
    private string $username;
    private string $localisation;
    private string $hash;
    private string$dtrejoint;
    private Avatar $avatar;


    public function __construct(string $username, string $localisation, string $hash, string $bio, string $url, int $id = 0,string $date , Avatar $avatar)
    {
        $this->setId($id);
        $this->setBio($bio);
        $this->setUrl($url);
        $this->setUsername($username);
        $this->setLocalisation($localisation);
        $this->setHash($hash); 
        $this->setDtrejoint($date);
        $this->setAvatar($avatar);
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getAvatar(): Avatar
    {
        return $this->avatar;
    }


    public function setAvatar(Avatar $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getBio(): string
    {
        return $this->bio;
    }


    public function setBio(string $bio): self
    {

        if (strlen($bio) > 255)
            throw new Exception("Le contenu de votre bio '$bio 'doit être de moins de 255 caractères.");

        $this->bio = $bio;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }


    public function setUrl(string $url): self
    {
        if (!filter_var($url, FILTER_SANITIZE_URL))
            throw new Exception("Le contenu de votre url ' $url' doit être de moins de 255 caractères.");

        $this->url = $url;
        return $this;
    }
    public function getUsername(): string
    {

        return $this->username;
    }


    public function setUsername(string $username): self
    {
        $username = trim($username);
        if (strlen($username) > 50 || empty($username))
            throw new Exception("Le username '$username' doit être >= 1 ET <= 50.");
        $this->username = $username;
        return $this;
    }

    public function getLocalisation(): string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $localisation = trim($localisation);
        if (strlen($localisation) > 100)
            throw new Exception("La localisation '$localisation' n'est pas de format valide.");
        $this->localisation = $localisation;
        return $this;
    }


    public function getHash(): string
    {
        return $this->hash;
    }

 
    public function setHash(string $hash): self
    {
        $hash = trim($hash);
        if (strlen($hash) != 64)
            throw new Exception("Le hash '$hash' n'est pas de longueur 64.");
        $this->hash = $hash;
        return $this;
    }

    public function getDtrejoint(): string
    {
        return $this->dtrejoint;
    }

 
    public function setDtrejoint(string $dtrejoint): self
    {
        /*if (!filter_var($dtrejoint, FILTER_SANITIZE_FULL_SPECIAL_CHARS))
            throw new Exception("La date '$dtrejoint' n'est pas formuler correctement.");*/
        $this->dtrejoint = $dtrejoint;
        return $this;
    }
}
