<?php
/**
 * @param array $data       二维数组结果集
 * @param $name             用于提交表单的名字
 * @param string $value_field   value属性数据来源
 * @param string $name_field    文案数组来源
 */
function arr2select(array $data,$name,$value_field='id',$name_field='name',$selectID){
    ;
    $html = '<select name="'.$name.'" id="'.$selectID.'">';
    $html .= '<option value="0">请选择...</option>';
    foreach($data as $value){
        $html .='<option value="'.$value[$value_field].'">'.$value[$name_field].'</option>';
    }
    $html .='</select>';
    return $html;
}

/**
 * 处理错误信息
 * @param \Think\Model $model
 * @return string
 */
function dealErrorStr(\Think\Model $model){
    $errors = $model->getError();
    $errorStr = "<ul>";
    if (is_array($errors)) {
        foreach($errors as $val){
            $errorStr .='<li>'.$val.'</li>';
        }
    }else{
        $errorStr .='<li>'.$errors.'</li>';
    }
    $errorStr .='</ul>';
    return $errorStr;
}

/**
 * 加盐加密
 * @param $password     密码
 * @param $salt         盐
 * @return string       加盐加密后的结果
 */
function salt_mcrypt($password,$salt){
    return md5(md5($password).$salt);       //加密提交的密码后，连接盐，再加密
}

function send_mail($address,$subject,$content){
    vendor('PhpMailer.PHPMailerAutoload');
    $mail = new \PHPMailer;
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.163.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'hong_x94@163.com';                 // SMTP username
    $mail->Password = 'xh20140226tw';                           // SMTP password    授权码
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    $mail->setFrom('hong_x94@163.com', 'hong');
    $mail->addAddress($address);     // Add a recipient
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->CharSet='utf-8';

    $mail->Subject = $subject;
    $mail->Body    = $content;

    if (!$mail->send()) {
        $data=[
            'status'=>false,
            'msg'=>$mail->ErrorInfo,
        ];
    }else{
        $data=[
            'status'=>true,
            'msg'=>'发送成功',
        ];
    }
    return $data;
}