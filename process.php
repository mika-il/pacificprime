<?php
  require(dirname(__FILE__)."/db/db.connect.php");

  $errors = array();  
  $data = array();    

  // validate the variables 
  if (empty($_POST['name'])) {
    $errors['name'] = 'Name is required.';
  }

  if (empty($_POST['phone'])) {
    $errors['phone'] = 'Phone is required.';
  }

  if (empty($_POST['email'])) {
    $errors['email'] = 'Email is required.';
  }

  if (empty($_POST['company'])) {
    $errors['company'] = 'Company is required.';
  }

  if (empty($_FILES['companyLogo']['name'])){
    $errors['companyLogo'] = 'Company Logo is required.';
  } else {
    // valid extensions
    $validExtensions = array('jpeg', 'jpg', 'png', 'gif'); 
    $path = getcwd().'/uploads/'; 

    $logoImage = $_FILES['companyLogo']['name'];
    $tmp = $_FILES['companyLogo']['tmp_name'];
 
    // get uploaded file's extension
    $ext = strtolower(pathinfo($logoImage, PATHINFO_EXTENSION));
    $finalImage = rand(1000,1000000).'_'.$logoImage;
 
    // check's valid format
    if(in_array($ext, $validExtensions)) { 
      $path = $path.strtolower($finalImage); 
    }

    if(!move_uploaded_file($tmp,$path)) {
      $errors['companyLogo'] = 'File error, Please try again.';
    }
  }

  
  
  // response if there are errors
  if ( ! empty($errors)) {
    $data['success'] = false;
    $data['errors']  = $errors;
  } else {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $company = $_POST['company'];

    try {
      $database = new Connection();
      $db = $database->openConnection();
   
      // inserting data into table 
      $sql = "INSERT INTO customer (customer,phone,email,company,logo) VALUES ( :customer, :phone, :email, :company, :logo)";
      $result = $db->prepare($sql) ;
      // inserting a record
      $result->execute(array(':customer' => $name , ':phone' => $phone , ':email' => $email, ':company'=> $company, ':logo'=> $finalImage));
      $lastInsertId = $db->lastInsertId();
      $database->closeConnection();

      $data['message'] = 'Success!, New record created.';
      $data['success'] = true;
      $data['result']['name'] = $name;
      $data['result']['phone'] = $phone;
      $data['result']['email'] = $email;
      $data['result']['company'] = $company;
      $data['result']['companyLogo'] = $finalImage;
      $data['result']['lastID'] = $lastInsertId;
      
    }
    catch (PDOException $e) {
      echo "There is some problem in connection: " . $e->getMessage();
      die();
    }
    
  }

  // return all our data to an AJAX call
  echo json_encode($data);


?>