<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 25.02.2018
 * Time: 14:12
 */

?>

<div class="wrapper" style="font-family: Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.42857143; color: #333; background-color: #677283; padding: 0; width: 475px; margin: 0 auto; ">
    <div class="cntr" style="padding: 23px 5px;">
        <div class="mailInner" style="margin: 0 auto; padding: 15px 30px; color: #B1B0B7; max-width: 330px; border: 1px solid #ccc;	 display: block; background-color: #fff; border-radius: 3px;">
            <h2><i class="fa fa-sun-o" aria-hidden="true"></i> Events. Регистрация</h2>
            <div class="hr-toh2" style="border: 1px solid #91D3E4;"></div>
            <h4 style="color: #4f5f6f; font-weight: 700; font-size: 16px; margin: 10px 0;">
                Вы успешно зарегистрировались в приложении Events
            </h4>

            <table class="tblForMail" style="color: #4f5f6f;">
                <tbody>
                <tr>
                    <td>Имя</td>
                    <td style="padding-left: 33px; font-weight: 700;">
							<span style="border-bottom: 3px solid #E5F4F8; display: block;">
								<?=$mailData['uname']?>
							</span>
                    </td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td style="padding-left: 33px; font-weight: 700;">
							<span style="border-bottom: 3px solid #E5F4F8; display: block;">
								<?=$mailData['umail']?>
							</span>
                    </td>
                </tr>
                <tr>
                    <td>Пароль</td>
                    <td style="padding-left: 33px; font-weight: 700;">
							<span style="border-bottom: 3px solid #E5F4F8; display: block;">
								<?=$mailData['upass']?>
							</span>
                    </td>
                </tr>
                <tr>
                    <td>Дата регистрации</td>
                    <td style="padding-left: 33px; font-weight: 700;">
							<span style="border-bottom: 3px solid #E5F4F8; display: block;">
								<?=$mailData['udtreg']?>
							</span>
                    </td>
                </tr>
                </tbody>
            </table>

            <p style="color: #6E7E8E; font-size: 12px;">
                Это сообщение отправлено автоматически, пожалуйста, не отвечайте на него
            </p>
            <div class="copy" style="color: #4f5f6f;">
				<span>
					© Martin German. All rights reserved
				</span>
            </div>
        </div>
    </div>
</div>