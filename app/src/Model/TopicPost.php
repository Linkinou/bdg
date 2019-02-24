<?php

namespace App\Model;

use App\Entity\Post;

class TopicPost
{
    /**
     * @var string
     */
    protected $topicTitle;

    /**
     * @var string
     */
    protected $content;

    /**
     * TopicPost constructor.
     * @param null $topicTitle
     * @param string $content
     */
    public function __construct($topicTitle = null, $content = null)
    {
        $this->topicTitle = $topicTitle;
        $this->content = $content;
    }

    /**
     * @param Post $post
     * @return TopicPost
     */
    public static function createFromPost(Post $post) : TopicPost
    {
        return new self(null, $post->getContent());
    }

    /**
     * @return string
     */
    public function getContent() : ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content) : TopicPost
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getTopicTitle(): ?string
    {
        return $this->topicTitle;
    }

    /**
     * @param string $topicTitle
     * @return TopicPost
     */
    public function setTopicTitle(string $topicTitle): TopicPost
    {
        $this->topicTitle = $topicTitle;
        return $this;
    }
}