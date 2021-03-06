<?php

class Shweet{


    private int $id;
    private string $texte;
    private string $dateCreation;
    private Utilisateur $auteur;
    private  $parent_id;


    public function __construct(
        int $id = 0,
        string $texte,
        string $dateCreation = null,
        Utilisateur $auteur,
        $parent_id
    )
    {
        $this->setId($id);
        $this->setTexte($texte);
        $this->setDateCreation($dateCreation);
        $this->setAuteur($auteur);
        $this->setParent($parent_id);
    }


    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    

    /**
     * Get the value of texte
     *
     * @return string
     */
    public function getTexte(): string
    {
        return $this->texte;
    }

    /**
     * Set the value of texte
     *
     * @param string $texte
     *
     * @return self
     */
    public function setTexte(string $texte): self
    {
        $texte = trim($texte);
        if (strlen($texte) > 65535 || empty($texte))
            throw new Exception("Le texte '$texte' doit être >= 1 ET <= 65535.");
        $this->texte = $texte;
        return $this;
    }

    /**
     * Get the value of dateCreation
     *
     * @return ?DateTime
     */
    public function getDateCreation(): ?string
    {
        return $this->dateCreation;
    }

   
    public function setDateCreation(string $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    
    

    /**
     * Get the value of auteur
     *
     * @return Utilisateur
     */
    public function getAuteur(): Utilisateur
    {
        return $this->auteur;
    }

    /**
     * Set the value of auteur
     *
     * @param Utilisateur $auteur
     *
     * @return self
     */
    public function setAuteur(Utilisateur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    
    /**
     * Get the value of the parent id
     *
     * @return int
     */
    public function getParent(): int
    {
        return $this->parent_id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setParent($parent_id): self
    {
        $this->parent_id = $parent_id;

        return $this;
    }
    

}


