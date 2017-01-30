<?php

require_once 'answer.php';

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 * @Table (name="tb_user")
 */
class user {

    /**
     * @Id @Column(type="integer") 
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @Column(type="string") */
    private $nick_name;

    /** @Column(type="string") */
    private $secret;

    /** @Column(type="string") */
    private $email;

    /** @Column(type="boolean") */
    private $administrator;

    /** @OneToMany(targetEntity="answer", mappedBy="author", cascade={"remove"}) */
    private $answers;

    function __construct($nick_name, $secret, $email, $administrator) {
        $this->nick_name = $nick_name;
        $this->secret = $secret;
        $this->email = $email;
        $this->administrator = $administrator;
        $this->answers = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAdministrator() {
        return $this->administrator;
    }

    public function getAnswers() {
        return $this->answers;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setAdministrator($administrator) {
        $this->administrator = $administrator;
    }

    public function getNick_name() {
        return $this->nick_name;
    }

    public function setNick_name($nick_name) {
        $this->nick_name = $nick_name;
    }

    public function getSecret() {
        return $this->secret;
    }

    public function setSecret($secret) {
        $this->secret = $secret;
    }

}

?>
