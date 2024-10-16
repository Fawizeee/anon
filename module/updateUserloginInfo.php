<?php
class updateUserLoginInfo
{
    private $remember;
    private $id;
    private $rem;
    private $name;
    private $userid;
    private $db;
    private $addId;
    private $setCookie;

    public function __construct(mysqli $db, cookie_mod $cookie_mod, bool $rem, $userid, $name)
    {
        $this->db = $db;
        $this->rem = $rem;
        $this->userid = $userid;
        $this->addId = false;
        $this->setCookie = $cookie_mod;
        $this->name = $name;
    }
    private function remember()
    {

        if (!$this->rem) {
            $this->remember = 0;
        } else {
            $this->remember = uniqid(mt_rand(10, 100));
            $this->setCookie->Make_ckie(cookie: ["remember" => $this->remember]);
        }


    }
    private function id()
    {

        $this->remember();
        if (!$this->userid) {
            $this->id = uniqid(mt_rand(10, 10));
            $_SESSION["userid"] = $this->id;
            $this->setCookie->Make_ckie(["userid" => $this->id]);
            $this->addId = true;
            return "UPDATE INFO SET ID =?,remember =? WHERE USERNAME =?;";
        } else {
            $this->id = $this->userid;

            return "UPDATE INFO SET remember=? WHERE USERNAME =?";
            ;
        }
    }

    public function update()
    {
        $this->setCookie->Make_ckie(["userid" => $this->id]);
        $query = $this->id();
        $stmt = $this->db->prepare($query);
        if ($this->addId) {
            $stmt->bind_Param("sss", $this->id, $this->remember, $this->name);

        } else {
            $stmt->bind_Param("ss", $this->remember, $this->name);
        }
        $result = $stmt->execute();
        return [$result, $query, $this->remember, $this->id, $this->addId];


    }

}