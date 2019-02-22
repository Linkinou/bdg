<?php

namespace App\Model;

use App\Entity\Post;

class TopicPost
{
    /**
     * @var string
     */
    protected $content;

    /**
     * TopicPost constructor.
     * @param string $content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * @param Post $post
     * @return TopicPost
     */
    public static function createFromPost(Post $post)
    {
        return new self($post->getContent());
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
    }
}