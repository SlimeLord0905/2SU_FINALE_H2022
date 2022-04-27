<?php


class Avatar
{
    private int $id;
    private string $url;

    public function __construct(
        int $id = 0,
        string $url
    )
    {
        $this->setId($id);
        $this->setUrl($url);
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

    public function getUrl(): string
    {
        return $this->url;
    }


    public function setUrl(string $url): self
    {
        if (filter_var($url, FILTER_SANITIZE_URL) || (strlen($url) > 255))
            throw new Exception("Le contenu de votre url ' $url' doit être de moins de 255 caractères.");

        $this->url = $url;
        return $this;
    }
}
