<?php
require_once ("config.php");
$data = json_decode(file_get_contents('php://input'), true);
$cmd = $data['cmd'];
$value = $data['value'];
$response = ['success' => 0];

if(!empty($cmd) && !empty($value))
{
    $validator  = new Validator(new User());
    switch ($cmd)
    {
        case 'check_user_name':
            $isAvalible = $validator->isUsernameAvailable($value);
            if(!$isAvalible) {
                $response = ['success' => 1];
            } else {
                $response = ['success' => 0, 'error' => $validator->errors];
            }
            break;
        case "check_email":
            $isAvalible = $validator->isEmailAvailable($value);
            if(!$isAvalible) {
                $response = ['success' => 1];
            } else {
                $response = ['success' => 0, 'error' => $validator->errors];
            }
            break;
        default:
            break;
    }
}
header('Content-Type: application/json');
echo json_encode($response);
die();