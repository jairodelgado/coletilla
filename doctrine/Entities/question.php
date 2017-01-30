<?php

require_once 'answer.php';

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table (name="tb_question")
 */
class question {

    /**
     * @Id @Column(type="integer") 
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="string") */
    private $content;

    /** @Column(type="boolean") */
    private $published;

    /** @Column(type="datetime") */
    private $post_date;

    /** @OneToMany(targetEntity="answer", mappedBy="question", cascade={"remove"}) */
    private $answers;

    function __construct($content, $published, $post_date) {
        $this->content = $content;
        $this->published = $published;
        $this->post_date = $post_date;
        $this->answers = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getPublished() {
        return $this->published;
    }

    public function getAnswers() {
        return $this->answers;
    }

    public function setPublished($published) {
        $this->published = $published;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getPost_date() {
        return $this->post_date;
    }

    public function setPost_date($post_date) {
        $this->post_date = $post_date;
    }

}
