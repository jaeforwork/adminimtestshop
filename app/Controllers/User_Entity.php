<?php
namespace App\Controllers;

use App\Entities\UserEntity; // (1)
use App\Models\UserEntityModel;

class Entity extends BaseController {
  
  public function age() {
     $user = new UserEntity(); // (2)
    //  $tt=$UserEntity->getAge(20); // (3)   
    $data = [
      "USERNAME" => "foo",
      "EMAIL" => "foo@gmail.com",
      "PASSWORD" => "1234567890",
    ];

    $user->fill($data);

    $std = new UserEntityModel();
    $user = $std->find(1);

    // Display
    echo $user->USERNAME;
    echo $user->EMAIL;

    $RR=$UserEntityModel->save($user);
   // );

    // $UserEntityModel = new UserEntityModel(); // (4)
    // $UserEntityModel->insert($data); // (5)
 
      return $UserEntity;
  }

  public function regist() {
    // Create
    $user = new UserEntity();
    // $user->USERNAME = 'foo';
    // $user->EMAIL    = 'foo@example.com';
    // $user->PASSWORD    = 'foo@example.com';

    // Creating an instance of modal
    $UserEntityModel = new UserEntityModel(); // (4)

    // Creating an instance of entity

    $data = [
      "USERNAME" => "foo",
      "EMAIL" => "foo@gmail.com",
      "PASSWORD" => "1234567890",
    ];

    $user->fill($data);
    $RR=$UserEntityModel->save($user);
    print_r($RR);
  }

  public function regist2() {
    // Create
    $user = new \App\Entities\UserEntity();
    // $user->USERNAME = 'foo';
    // $user->EMAIL    = 'foo@example.com';
    // $user->PASSWORD    = 'foo@example.com';

    // Creating an instance of modal
    $UserEntityModel = new UserEntityModel(); // (4)

    // Creating an instance of entity
    $user->USERNAME = "User101";
    $user->EMAIL = "user101@gmail.com";
    $user->PASSWORD = "896543133";

    $UserEntityModel->save($user);
    print_r($RR);
  }
}
