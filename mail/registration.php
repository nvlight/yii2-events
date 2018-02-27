<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 25.02.2018
 * Time: 14:12
 */

?>

<style type="text/css" >
    h4{
        color: #4f5f6f;
        font-weight: 700;
        font-size: 16px;
        margin: 10px 0;
    }
    h5{
        color: #4f5f6f;
        font-weight: 600;
        font-size: 14px;
        display: inline-block;
        text-align: left;
        width: 100%;
        margin: 0;
    }
    .wrapper{
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 14px;
        line-height: 1.42857143;
        color: #333;
        background-color: #677283;
        padding: 0;
        margin: 0;
        min-height: 100vh;
    }
    .mailInner{
        margin: 0 auto;
        padding: 15px 30px;
        color: #B1B0B7;
        max-width: 330px;
        border: 1px solid #ccc;
        display: block;
        background-color: #fff;
        border-radius: 3px;
    }
    .wrapper > .cntr{
        padding: 23px 5px;
    }
    .hr-toh2 {
        border: 1px solid #91D3E4;
    }
    dl{
        color: #4f5f6f;
        font-family: helvetica;
    }
    dl dt{

    }
    dl dd{
        font-weight: 700;
        border-bottom: 3px solid #E5F4F8;
        border-radius: 0px;
    }
    p{
        color: #6E7E8E;
        font-size: 12px;
    }

    .tblForMail{
        /*border: 1px solid #ccc;*/
        color: #4f5f6f;
    }
    .tblForMail tbody{

    }
    .tblForMail tr{

    }
    .tblForMail tr td:nth-child(2){
        padding-left: 33px;
        font-weight: 700;

    }
    .tblForMail tr td:nth-child(2) span{
        border-bottom: 3px solid #E5F4F8;
        display: block;
    }
    .copy{
        color: #4f5f6f;
    }

</style>


<div class="wrapper">
    <div class="cntr">
        <div class="mailInner">
            <h2><i class="fa fa-sun-o" aria-hidden="true"></i> Events. Регистрация</h2>
            <div class="hr-toh2"></div>
            <h4>Вы успешно зарегистрировались в приложении Events </h4>

            <table class="tblForMail">
                <tbody>
                <tr>
                    <td>Имя</td>
                    <td><span><?=$mailData['uname']?></span></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><span><?=$mailData['umail']?></span></td>
                </tr>
                <tr>
                    <td>Пароль</td>
                    <td><span><?=$mailData['upass']?></span></td>
                </tr>
                <tr>
                    <td>Дата регистрации</td>
                    <td><span><?=$mailData['udtreg']?></span></td>
                </tr>
                </tbody>
            </table>

            <p>Это сообщение отправлено автоматически, пожалуйста, не отвечайте на него
            </p>
            <div class="copy">
				<span>
					© Martin German. All rights reserved
				</span>
            </div>
        </div>
    </div>
</div>