<?php

namespace LucStr\Controller;


use LucStr\MessageHandler;

class RegistrationController extends BaseController
{
  public function Index()
  {
  	$this->CreateCSRF();
  	return $this->view();
  }
  
  /**
   * @HTTP POST
   * @CSRF ON
   */
  public function Register($username, $email, $password)
  {  	
  	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
  		MessageHandler::danger("Email not valid!");
  		return $this->view("Registration", "Index", [
  				"username" => $username
  		]);
  	}
  	if(!$this->checkPassword($password)){
  		MessageHandler::danger("Password must be between 8 and 20 characters, 1 number and 1 character small and big");
  		return $this->view("Registration", "Index", [
  				"username" => $username,
  				"email" => $email
  		]);
  	}
  	if($this->CheckUsername($password)){
  		MessageHandler::danger("Username already in use");
  		return $this->view("Registration", "Index", [
  				"email" => $email
  		]);
  	}
  	$userService = $this->factory->getUserService();
  	$userId = $userService->register($username, $email, $password);
  	if($userId == 0){
  		MessageHandler::danger("Unrecognized Error occured. Please try again later");
  		return $this->view("Registration", "Index", [
  				"username" => $username,
  				"email" => $email
  		]);
  	}
  	$user = $userService->getUserById($userId);
  	$message =  \Swift_Message::newInstance()  	 
  	->setSubject('Confirm Mail')
   	->setFrom(array('luca.strebel@gmx.ch' => 'Luca Strebel'))
  	->setTo(array($user->getEmail() => $user->getUsername()))
  	->setBody('Hello ' . $user->getUsername() . ',</br> Please confirm your Mailaddres <a href="https://' 
  			. $_SERVER['SERVER_NAME'] . "/Registration/Activate?userId=" . $userId . "&confirmationUUID=" .
	$user->getConfirmationUUID() . '">Here</a>', 'text/html')
  	->setContentType("text/html");  	 
  	$this->factory->getMailer()->send($message);
  	return $this->view("Login", "Index", [
  			"username" => $username
  	]);
  	
  }
  
  public function CheckUsername($username){
  	$userService = $this->factory->getUserService();
  	echo $userService->checkUsername($username);
  }
  
  public function Activate($userId, $confirmationUUID){
  	$userService = $this->factory->getUserService();
  	$user = $userService->getUserById($userId);
  	if($user->getActivated()){
  		MessageHandler::info("This user has already been activated.");
  	} else if($user->getConfirmationUUID() == $confirmationUUID){
  		$userService->activate($userId);
  		MessageHandler::success("Email has been confirmed!");
  	} else{
  		MessageHandler::danger("invalid link!");
  	}
  	return $this->redirectToAction("Index", "Index");
  }
  
  public function RequestPasswordReset(){
  	return $this->action();
  }
  
  /**
   * @HTTP POST
   * @CSRF ON
   */
  public function RequestPasswordResetEmail($username){
  	$userService = $this->factory->getUserService();
  	$userService->renewConfirmationUUIDByUsername($username);
  	$user = $userService->getUserByUsername($username);
  	if($user->getUsername() == $username){
  		$message =  \Swift_Message::newInstance()
  		->setSubject('Reset Password')
  		->setFrom(array('luca.strebel@gmx.ch' => 'Luca Strebel'))
  		->setTo(array($user->getEmail() => $user->getUsername()))
  		->setBody('Hello ' . $user->getUsername() . ',</br> You can reset your Password <a href="https://'
  				. $_SERVER['SERVER_NAME'] . "/Registration/ResetPasswordForm?userId=" . $user->getUserId() . "&confirmationUUID=" .
  				$user->getConfirmationUUID() . '">Here</a>', 'text/html')
  		->setContentType("text/html");
  		$this->factory->getMailer()->send($message);
  	}
  	return $this->redirectToAction("Index", "Index");
  }
  
  public function ResetPasswordForm($userId, $confirmationUUID){
  	$userService = $this->factory->getUserService();
  	$user = $userService->getUserById($userId);
  	$this->CreateCSRF();
  	if($user->getUserId() == $userId){
  		return $this->view([
  				"confirmationUUID" => $confirmationUUID,
  				"userId" => $userId,
  		]);
  	} else{
  		MessageHandler::danger("invalid link!");
  		return $this->redirectToAction("Index", "Index");
  	}
  }
  
  /**
   * @HTTP POST
   * @CSRF ON
   */
  public function ResetPassword($userId, $confirmationUUID, $password, $passwordConfirm){
  	if($password != $passwordConfirm){
  		MessageHandler::danger("Password and confirmation don't match.");
  		return $this->redirectToAction("Registration", "ResetPasswordForm", ["userId" => $userId, "confirmationUUID" => $confirmationUUID]);
  	}
  	if(!$this->checkPassword($password)){
  		MessageHandler::danger("Password must be between 8 and 20 characters, 1 number and 1 character small and big");
  		return $this->redirectToAction("Registration", "ResetPasswordForm", ["userId" => $userId, "confirmationUUID" => $confirmationUUID]);
  	}
	$userService = $this->factory->getUserService();
	if($userService->updatePassword($userId, $password)){
		MessageHandler::success("Passwort has been updated");
		return $this->redirectToAction("Login", "Index");
	} else {
		MessageHandler::danger("An error occured");
		return $this->redirectToAction("Registration", "ResetPasswordForm", ["userId" => $userId, "confirmationUUID" => $confirmationUUID]);
	}
  }
  
  private function checkPassword($password) {
  	return preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $password);
  }
}
