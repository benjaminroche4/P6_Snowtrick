<?php


namespace App\DTO;


class TrickDTO
{
    private $title;
    private $groupe;
    private $videoUrls;
    private $mainPhotoUrl;
    private $content;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * @param mixed $groupe
     */
    public function setGroupe($groupe): void
    {
        $this->groupe = $groupe;
    }

    /**
     * @return mixed
     */
    public function getVideoUrls()
    {
        return $this->videoUrls;
    }

    /**
     * @param mixed $videoUrls
     */
    public function setVideoUrls($videoUrls): void
    {
        $this->videoUrls = $videoUrls;
    }

    /**
     * @return mixed
     */
    public function getMainPhotoUrl()
    {
        return $this->mainPhotoUrl;
    }

    /**
     * @param mixed $mainPhotoUrl
     */
    public function setMainPhotoUrl($mainPhotoUrl): void
    {
        $this->mainPhotoUrl = $mainPhotoUrl;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }


}