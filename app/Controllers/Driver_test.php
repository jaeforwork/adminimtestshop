<?php

namespace App\Controllers;
use App\Models\DriverInfoModel;
use CodeIgniter\Exceptions\AlertError;

//use CodeIgniter\Config\Config;  ///base로 옮기고 테스트

class Driver_test extends BaseController
{

  public function index()
  {
    $session = \Config\Services::session();
    $message = $session->getFlashdata('message');

    $std = new DriverInfoModel();
    //$data['students'] = $std->findAll();
    // $data['students'] = $std->paginate(2);
   // $result = $std->countAllResults();
   // $builder = $std->builder();
   // $builder->where('s_subject', 33);

    $data = [
     'students' => $std->orderBy('S_IDX','DESC')->paginate(5),     
      'pager' => $std->pager,   
      'tcount' => $std->countAllResults(),   
    ];

    $msg="인증되었습니다.";
    echo(json_encode(array("result" => 'succ', "msg" => $msg))); 
    

    // $data['message'] = $message;
    // // echo view('students',$data);
    // $data['title'] = ucfirst('welcome'); // Capitalize the first letter

    // echo view('templates/header', $data);
    // echo view('students',$data);
    // echo view('templates/footer', $data);

    //var_dump($results);
   }


   public function search() {
    //$session = \Config\Services::session();
    // $where  = esc($request->getPost('where'));
    // $what  = esc($request->getPost('what'));


/* builder test ok

    // $db      = \Config\Database::connect();
    // $builder = $db->table('students');
    // $builder->where('s_subject', 77);
    // $tcount = $builder->countAllResults();
*/



    // if (!empty($where)) {
     
    // }
    $where  = 's_subject';
    $what  = 77;
     $std = new DriverInfoModel();

     //$data['students'] = $std->findAll();
     // $data['students'] = $std->paginate(2);
  
     $data = [
      'students' => $std->where($where,$what)->paginate(5),
       'pager' => $std->pager,          
      'tcount' => $std->where($where, $what)->countAllResults(),  
     ];
 
   //  $data['message'] = $message;
     // echo view('students',$data);
    }
 











   public function getinfo() {
      $request = \Config\Services::request();
    
      $IDX  = esc($request->getPost('driver_idx'));

      if (!empty($IDX)) {
      // if (!empty($IDX) && !empty($subject)) {

      $std = new DriverInfoModel();

      $result = $std->where('S_IDX',$userId)->findAll();
      if (count($result) > 0) {
         $data['student'] = $result;

         $data['title'] = ucfirst('welcome'); // Capitalize the first letter

      $msg="인증되었습니다.";
      echo(json_encode(array("result" => 'succ', "msg" => $data)));     
      }
    } else {
      $msg="단말기에서 넘어온 드라이버 고유번호가 없음.";
      echo(json_encode(array("result" => 'fail', "msg" => $msg))); 
    }


      
   }





   public function editstudent($userId =  null)
   {
      $session = \Config\Services::session();
      if (!empty($userId)) {
         $std = new DriverInfoModel();
         $result = $std->where('S_IDX',$userId)->findAll();
         if (count($result) > 0) {
            $data['student'] = $result;

            $data['title'] = ucfirst('welcome'); // Capitalize the first letter

    echo view('templates/header', $data);
    echo view('editstudent',$data);
    echo view('templates/footer', $data);

         }
         else{
            $session->setFlashdata('message','The Student is not exist');
            return redirect()->to(base_url('students'));
         }
      }
      else{
         $session->setFlashdata('message','The id is not available, please try again.');
         return redirect()->to(base_url('students'));
      }

   }

   public function updatestudent() {
      $request = \Config\Services::request();
      $session = \Config\Services::session();
      $name       = esc($request->getPost('std'));
      $subject    = esc($request->getPost('subject'));
      $studentId  = esc($request->getPost('id'));

      $updateStudent = [
         'S_NAME'=>$name,
         'S_SUBJECT'=>$subject,
      ];
      //echo $studentId;
      //die();
      $std = new DriverInfoModel();
      $result = $std->update($studentId,$updateStudent);
      if ($result) {
         $session->setFlashdata('message','You have successfully updated the student.');
      }
      else{
         $session->setFlashdata('message','Oops something went wrong please try again.');
      }
      return redirect()->to(base_url('students'));
   }

   public function delete($userId)
   {
      $session = \Config\Services::session();
      if (!empty($userId)) {
         $std = new DriverInfoModel();
         $result = $std->where('S_IDX',$userId)->findAll();
         if (count($result) > 0) {
            //$result = $std->delete($userId);
            $result = $std->where('S_IDX',$userId)->delete();
            if ($result){
               $session->setFlashdata('message','You have successfully deleted.');
               return redirect()->to(base_url('students'));
            }
            else{
               $session->setFlashdata('message','You can\'t delete the student right now.');
               return redirect()->to(base_url('students'));
            }
         }
         else{
            $session->setFlashdata('message','The Student is not exist');
            return redirect()->to(base_url('students'));
         }
      }
      else{
         $session->setFlashdata('message','The id is not available, please try again.');
         return redirect()->to(base_url('students'));
      }
   }
}//class