<?php

require_once 'question.php';
require_once 'user.php';

/**
 * @Entity
 * @Table (name="tb_answer")
 */
class answer {

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
    /**
     * @ManyToOne(targetEntity="user")
     * @JoinColumn(name="author", referencedColumnName="id") 
     */
    private $author;

    /**
     * @ManyToOne(targetEntity="question")
     * @JoinColumn(name="question", referencedColumnName="id") 
     */
    private $question;

    function __construct($content, $published, $post_date, $user, $question) {
        $this->content = $content;
        $this->published = $published;
        $this->post_date = $post_date;
        $this->author = $user;
        $this->question = $question;
    }

    public function getId() {
        return $this->id;
    }

    public function getPublished() {
        return $this->published;
    }

    public function getUser() {
        return $this->author;
    }

    public function getQuestion() {
        return $this->question;
    }

    public function setPublished($published) {
        $this->published = $published;
    }

    public function setUser($user) {
        $this->author = $user;
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

?>
