<?php
class Password_mod
{
  public function __construct()
  {

  }
  function hashPassword($password)
  {
    $salt = random_bytes(16);
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    return $hashedPassword;
  }
  function verifyPassword($hashedPassword, $password)
  {
    return password_verify($password, $hashedPassword);
  }
}


