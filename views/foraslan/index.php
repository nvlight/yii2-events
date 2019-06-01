<?php
/**
 * Created by PhpStorm.
 * User: lght
 * Date: 29.12.2018
 * Time: 15:13
 */



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="favicon.png">
    <style>
        @charset "UTF-8";

        /*==================
        FONTS
        ==================*/
        @import url("https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&subset=cyrillic");
        @font-face {
            font-family: 'fontello';
            src: url("../app-fonts/fontello/fontello.eot?33038310");
            src: url("../app-fonts/fontello/fontello.eot?33038310#iefix") format("embedded-opentype"), url("../app-fonts/fontello/fontello.woff2?33038310") format("woff2"), url("../app-fonts/fontello/fontello.woff?33038310") format("woff"), url("../app-fonts/fontello/fontello.ttf?33038310") format("truetype"), url("../app-fonts/fontello/fontello.svg?33038310#fontello") format("svg");
            font-weight: normal;
            font-style: normal;
        }

        [class^="icon-"]:before, [class*=" icon-"]:before {
            font-family: "fontello";
            font-style: normal;
            font-weight: normal;
            speak: none;
            display: inline-block;
            text-decoration: inherit;
            width: 1em;
            margin-right: .2em;
            text-align: center;
            font-variant: normal;
            text-transform: none;
            line-height: 1em;
            margin-left: .2em;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .icon-location:before {
            content: '\e800';
        }

        .icon-whatsapp:before {
            content: '\f232';
        }

        html {
            font-size: 62.5%;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            padding: 0;
            margin: 0;
            height: 100%;
        }

        *,
        *::before,
        *::after {
            -webkit-box-sizing: inherit;
            box-sizing: inherit;
        }

        /*=========
        * Заголовки
        ==========*/
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            padding: 0;
            margin: 0;
            font-size: inherit;
            font-weight: inherit;
        }

        /*=========
        * Параграф
        ==========*/
        p {
            padding: 0;
            margin: 0;
        }

        /*=========
        * Списки
        ==========*/
        ul,
        ol {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        /*=========
        * Таблицы
        ==========*/
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        /*=========
        * Картинки
        ==========*/
        img {
            height: auto;
            max-width: 100%;
            vertical-align: middle;
            border: none;
        }

        /*=========
        * Ссылки
        ==========*/
        a {
            text-decoration: none;
            font-family: inherit;
        }

        /*=========
        * Элементы формы
        ==========*/
        button,
        input,
        optgroup,
        select,
        textarea {
            padding: 0;
            margin: 0;
            border: none;
            border-radius: 0;
            -webkit-box-shadow: none;
            box-shadow: none;
            background-color: transparent;
            font: inherit;
            color: inherit;
            letter-spacing: inherit;
        }

        /*=========
        * Элементы формы (button, input)
        ==========*/
        button,
        input {
            overflow: visible;
        }

        button,
        [type='button'],
        [type='reset'],
        [type='submit'] {
            cursor: pointer;
            -webkit-appearance: none;
        }

        button::-moz-focus-inner,
        [type='button']::-moz-focus-inner,
        [type='reset']::-moz-focus-inner,
        [type='submit']::-moz-focus-inner {
            border: none;
            padding: 0;
        }

        button:-moz-focusring,
        [type='button']:-moz-focusring,
        [type='reset']:-moz-focusring,
        [type='submit']:-moz-focusring {
            outline: none;
        }

        [type='number']::-webkit-inner-spin-button,
        [type='number']::-webkit-outer-spin-button {
            height: auto;
        }

        [type='search']::-webkit-search-decoration {
            -webkit-appearance: none;
        }

        [type='search'] {
            outline: none;
        }

        ::-webkit-file-upload-button {
            -webkit-appearance: button;
            font: inherit;
        }

        /*=========
        * Элементы формы (select)
        ==========*/
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        select::-ms-expand {
            display: none;
        }

        /*=========
        * Элементы формы (textarea)
        ==========*/
        textarea {
            resize: none;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /*=========
        * Элементы формы (fieldset,legend)
        ==========*/
        fieldset {
            padding: 0;
            margin: 0;
            border: none;
        }

        legend {
            display: block;
            padding: 0;
            white-space: normal;
        }

        /*=========
        * Элементы формы (placeholder)
        ==========*/
        ::-webkit-input-placeholder {
            color: inherit;
            opacity: 1;
            -webkit-transition: opacity .3s;
            transition: opacity .3s;
        }

        ::-moz-placeholder {
            color: inherit;
            opacity: 1;
            -webkit-transition: opacity .3s;
            transition: opacity .3s;
        }

        :-moz-placeholder {
            color: inherit;
            opacity: 1;
            -webkit-transition: opacity .3s;
            transition: opacity .3s;
        }

        :-ms-input-placeholder {
            color: inherit;
            opacity: 1;
            -webkit-transition: opacity .3s;
            transition: opacity .3s;
        }

        :focus::-webkit-input-placeholder {
            opacity: 0;
        }

        :focus::-moz-placeholder {
            opacity: 0;
        }

        :focus:-moz-placeholder {
            opacity: 0;
        }

        :focus:-ms-input-placeholder {
            opacity: 0;
        }

        /*=========
        * Векторная графика
        ==========*/
        svg {
            display: block;
            width: 100%;
            height: 100%;
            fill: currentColor;
        }

        /*=========
        * Отключение элемента
        ==========*/
        :disabled,
        .disabled {
            cursor: not-allowed;
        }

        /*=========
        * Цвет текста при выделении
        ==========*/
        ::-moz-selection {
            color: #fff;
            background-color: #004fe4;
        }
        ::selection {
            color: #fff;
            background-color: #004fe4;
        }

        /*=========
        * Класс скрывающий элемент со страницы
        ==========*/
        .visually-hidden {
            position: absolute;
            z-index: -1;
            width: 0;
            height: 0;
            padding: 0;
            margin: 0;
            border: none;
            overflow: hidden;
        }

        /*=========
        * Переменные
        ==========*/
        /*=========
        * Тело страницы
        ==========*/
        body {
            padding: inherit;
            margin: inherit;
            font-weight: 400;
            line-height: 1;
            text-rendering: optimizeLegibility;
            -webkit-text-decoration-skip: objects;
            text-decoration-skip: objects;
            -webkit-text-size-adjust: 100%;
            -webkit-font-smoothing: antialiased;
            -webkit-tap-highlight-color: transparent;
            font-family: "Open Sans", "Arial Narrow", Arial, sans-serif;
            font-size: 1.6rem;
            color: #000000;
            background-color: #ffffff;
            height: 100%;
        }

        /* ============
        * SECTIONS
        ============ */
        /**** #.call-modal ****/
        .call-modal {
            display: block;
            margin: auto;
            margin-top: 30px;
            max-width: 200px;
            width: 100%;
            text-align: center;
            background-color: #218f99;
            color: #ffffff;
            font-size: 13px;
            padding: 15px;
        }

        /**** #.overlay ****/
        .overlay {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.65);
            z-index: 20;
            padding: 0px 10px;
            overflow-y: auto;
            display: none;
        }

        /**** #.modal ****/
        .modal {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            max-width: 822px;
            width: 100%;
            font-size: 13px;
        }

        /**** #.modal__exit ****/
        .modal__exit {
            position: absolute;
            content: '';
            top: 20px;
            right: 20px;
            font-size: 30px;
            color: #ffffff;
            outline: none;
            line-height: 20px;
        }

        /**** #.modal__header ****/
        .modal__header {
            padding: 28px 50px 21px 19px;
            background-color: #218f99;
        }

        .modal__header h3 {
            font-size: 18px;
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .modal__header p {
            font-size: 13px;
            color: #ffffff;
        }

        /**** #.modal__body ****/
        .modal__body {
            background-color: #ffffff;
            padding-top: 21px;
        }

        /**** #.modal__warning ****/
        .modal__warning {
            background-color: #f8e080;
            text-align: center;
            padding: 11px;
            font-size: 12px;
            font-weight: 600;
            color: #2f2f2f;
            line-height: 14px;
        }

        .modal__warning span {
            text-transform: uppercase;
        }

        /**** #.modal__footer ****/
        .modal__footer {
            text-align: center;
            padding: 13px;
            background-color: #ffffff;
            color: #676767;
        }

        .modal__footer .icon-location {
            font-size: 16px;
            color: #218f99;
        }

        .modal__footer span {
            color: #218f99;
        }

        /**** #.modal-form ****/
        .modal-form__row:nth-child(1) {
            padding: 0 23px 17px 18px;
        }

        @media (min-width: 850px) {
            .modal-form__row:nth-child(1) {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
            }
        }

        .modal-form__column:nth-child(1) {
            max-width: 458px;
            width: 100%;
            margin: auto;
            margin-bottom: 30px;
        }

        @media (min-width: 850px) {
            .modal-form__column:nth-child(1) {
                margin: 0;
                margin-right: 19px;
            }
        }

        .modal-form__column:nth-child(2) {
            max-width: 304px;
            width: 100%;
            margin: auto;
        }

        @media (min-width: 850px) {
            .modal-form__column:nth-child(2) {
                margin: 0;
            }
        }

        .modal-form__group {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        @media (min-width: 576px) {
            .modal-form__group {
                width: 100%;
                -webkit-box-orient: horizontal;
                -webkit-box-direction: normal;
                -ms-flex-direction: row;
                flex-direction: row;
                -webkit-box-pack: justify;
                -ms-flex-pack: justify;
                justify-content: space-between;
            }
        }

        .modal-form__field {
            width: 100%;
        }

        @media (min-width: 576px) {
            .modal-form__field {
                max-width: 150px;
            }
        }

        .modal-form__label {
            display: inline-block;
            position: relative;
            color: #5a5959;
            margin-bottom: 6px;
        }

        .modal-form__req {
            position: absolute;
            right: -8px;
            top: 0;
            color: #d74f5d;
            font-size: 14px;
        }

        .modal-form__input {
            display: block;
            width: 100%;
            padding: 10px 9px;
            margin-bottom: 14px;
            color: #bebebe;
            border: 2px solid #dcdcdc;
            min-height: 33px;
            max-height: 33px;
            outline: none;
        }

        .modal-form__input:focus {
            border-color: #218f99;
        }

        .modal-form__refine {
            color: #242424;
            font-size: 10px;
        }

        .modal-form__refine a {
            color: #218f99;
            text-decoration: underline;
        }

        .modal-form__license {
            color: #218f99;
            display: block;
            text-align: center;
            font-weight: 600;
            font-size: 12px;
            text-decoration: underline;
            margin-bottom: 17px;
        }

        .modal-form__btn {
            display: inline-block;
            text-align: center;
            max-width: 304px;
            width: 100%;
            background-color: #218f99;
            color: #ffffff;
            padding: 15px 15px 12px 18px;
            margin-bottom: 13px;
        }

        .modal-form__btn_whatsapp {
            border: 1px solid #218f99;
            background-color: #dff3f5;
            color: #218f99;
            padding: 7px 10px;
        }

        .modal-form__btn_whatsapp .icon-whatsapp {
            color: #218f99;
            font-size: 22px;
            vertical-align: middle;
        }

        .modal-form__copyright {
            color: #626262;
            font-size: 9px;
            line-height: 12px;
        }

        .modal-form__copyright a {
            color: #218f99;
            text-decoration: underline;
        }


        /**** #.add-family ****/
        .add-family {
            padding: 10px 18px;
            background-color: #dff3f5;
        }

        @media (min-width: 576px) {
            .add-family {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
            }
        }

        .add-family__desc {
            margin-bottom: 10px;
            text-align: center;
            color: #218f99;
        }

        @media (min-width: 576px) {
            .add-family__desc {
                margin: 0;
            }
        }

        .add-family__btn {
            display: block;
            max-width: 110px;
            width: 100%;
            margin: auto;
            color: #218f99;
            outline: none;
        }

        .add-family__btn img {
            padding-right: 12px;
        }

        @media (min-width: 576px) {
            .add-family__btn {
                margin: 0;
                margin-left: 40px;
            }
        }

        /**** ************************* ****/
        /**** ************************* ****/
        /**** #.modal-list ****/
        .modal-list__header {
            padding: 9px 33px;
            background-color: #218f99;
            min-height: 42px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        @media (min-width: 480px) {
            .modal-list__header {
                -webkit-box-orient: horizontal;
                -webkit-box-direction: normal;
                -ms-flex-direction: row;
                flex-direction: row;
            }
        }

        .modal-list__body {
            padding: 10px;
            background-color: #ffffff;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        @media (min-width: 768px) {
            .modal-list__body {
                -webkit-box-orient: horizontal;
                -webkit-box-direction: normal;
                -ms-flex-direction: row;
                flex-direction: row;
                -webkit-box-pack: justify;
                -ms-flex-pack: justify;
                justify-content: space-between;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                padding: 25px 65px 27px 72px;
            }
        }

        .modal-list__data {
            margin-bottom: 20px;
        }

        @media (min-width: 576px) {
            .modal-list__data {
                margin: 0;
            }
        }

        @media (min-width: 480px) {
            .modal-list__data {
                margin: 0;
            }
        }

        .modal-list .user-data {
            margin-bottom: 20px;
        }
        @media (min-width: 480px) {
            .modal-list .user-data {
                margin-bottom: 0;
            }
        }
        .modal-list__relative {
            color: #0b535a;
            display: inline-block;
            padding-right: 14px;
            margin-right: 12px;
            position: relative;
        }

        .modal-list__relative::after {
            position: absolute;
            content: '';
            top: 50%;
            right: 0;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            width: 2px;
            height: 24px;
            background-color: #2e747a;
        }

        .modal-list__name {
            color: #ffffff;
        }

        .modal-list__edit {
            max-width: 186px;
            width: 100%;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
        }

        @media (min-width: 480px) {
            .modal-list__edit {
                margin-left: auto;
            }
        }

        .modal-list__btn {
            color: #2e747a;
        }

        .modal-list__btn_edit {
            position: relative;
            display: inline-block;
            position: relative;
            padding-right: 19px;
            margin-right: 19px;
        }

        .modal-list__btn_edit::after {
            position: absolute;
            content: '';
            top: 50%;
            right: 0;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            width: 2px;
            height: 24px;
            background-color: #2e747a;
        }

        /**** #.list-form ****/
        .list-form {
            width: 100%;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            margin: auto;
        }

        @media (min-width: 576px) {
            .list-form {
                max-width: 342px;
                margin: 0;
            }
        }

        .list-form__group {
            width: 100%;
        }

        @media (min-width: 576px) {
            .list-form__group {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-pack: justify;
                -ms-flex-pack: justify;
                justify-content: space-between;
            }
        }

        @media (min-width: 576px) {
            .list-form__group:nth-child(1) {
                max-width: 304px;
            }
        }

        .list-form .modal-form__input_select {
            -webkit-appearance: menulist;
            -moz-appearance: menulist;
            appearance: menulist;
            padding: 5px 9px;
        }

        @media (min-width: 576px) {
            .list-form .modal-form__field_selected {
                max-width: 188px;
            }
        }

        /**** #.list-edit ****/
        .list-edit {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            max-width: 211px;
            margin: auto;
        }

        @media (min-width: 768px) {
            .list-edit {
                margin: 0;
            }
        }

        .list-edit__btn {
            display: inline-block;
            width: 100%;
            text-align: center;
            font-weight: 600;
        }

        .list-edit__btn_save {
            background-color: #218f99;
            color: #ffffff;
            padding: 15px 10px 12px;
            margin-bottom: 4px;
        }

        .list-edit__btn_save_imp {
            background-color: #218f99;
            color: #ffffff;
            padding: 15px 10px 12px;
            margin-bottom: 4px;
        }

        .list-edit__btn_remove {
            border: 1px solid #218f99;
            background-color: #dff3f5;
            color: #218f99;
            padding: 14px 10px 13px;
        }

        .list-edit__btn_remove_imp {
            border: 1px solid #218f99;
            background-color: #dff3f5;
            color: #218f99;
            padding: 14px 10px 13px;
        }
        /*# sourceMappingURL=app.css.map */
    </style>
    <title>Modal</title>
</head>

<button class="call-modal">Открыть модальное окно</button>


<div class="overlay">
    <!-- MODAL -->
    <div class="modal">
        <!-- MODAL EXIT -->
        <button class="modal__exit">&times;</button>
        <!-- MODAL HEADER -->
        <div class="modal__header">
            <h3>Сведения о налогоплательщике</h3>
            <p>Пожалуйста, заполните данные. Мы с вами свяжемся!</p>
        </div>
        <!-- MODAL BODY -->
        <div class="modal__body">

            <!-- FORM -->
            <form1 class="modal-form">

                <!-- MODAL ROW -->
                <div class="modal-form__row">
                    <!-- COLUMN -->
                    <div class="modal-form__column">
                        <div class="modal-form__group">
                            <p class="modal-form__field">
                                <label for="b_date" class="modal-form__label">Период от<span class="modal-form__req">*</span></label>
                                <input type="date" name="b_date" id="b_date" class="modal-form__input">
                            </p>
                            <p class="modal-form__field">
                                <label for="a_date" class="modal-form__label">До<span class="modal-form__req">*</span></label>
                                <input type="date" name="a_date" id="a_date" class="modal-form__input">
                            </p>
                            <p class="modal-form__field">
                                <label for="nnn" class="modal-form__label">ИНН</label>
                                <input type="number" name="nnn" id="nnn" placeholder="0000000000" class="modal-form__input">
                            </p>
                        </div>
                        <div class="modal-form__group">
                            <p class="modal-form__field">
                                <label for="u_name" class="modal-form__label">Имя<span class="modal-form__req">*</span></label>
                                <input type="text" name="u_name" id="u_name" class="modal-form__input">
                            </p>
                            <p class="modal-form__field">
                                <label for="u_surename" class="modal-form__label">Фамилия<span class="modal-form__req">*</span></label>
                                <input type="text" name="u_surename" id="u_surename" class="modal-form__input">
                            </p>
                            <p class="modal-form__field">
                                <label for="b_date" class="modal-form__label">Дата рождения<span class="modal-form__req">*</span></label>
                                <input type="date" name="b_date" id="b_date" placeholder="ДД/ММ/ГГГГ" class="modal-form__input">
                            </p>
                        </div>
                        <p class=" modal-form__refine">свой номер ИНН Вы можете уточнить на сайте <a href="http://service.nalog.ru/inn.do"
                                                                                                     target="_blank" rel="noopener noreferrer">service.nalog.ru/inn.do</a></p>
                    </div>


                    <!-- COLUMN -->
                    <div class="modal-form__column">
                        <a class="modal-form__license" href="http://" target="_blank" rel="noopener noreferrer">посмотреть лицензии</a>
                        <a href="http://msng.link/wa/79115556622" target="_blank" class="modal-form__btn modal-form__btn_whatsapp">Связаться с нами
                            через WHATSAPP <i class="demo-icon icon-whatsapp"></i></a>
                        <button type="submit" class="modal-form__btn">Заказать справку для налогового вычета</button>
                        <p class="modal-form__copyright">Нажимая на кнопку "Заказать справку для налогового вычета", Вы
                            даёте согласие на обработку персональных данных в соответствии с
                            <a href="#" target="_blank" rel="noopener noreferrer">Политикой конфиденциальности</a>, а также с
                            <a href="#" target="_blank" rel="noopener noreferrer">Пользовательским соглашением.</a></p>
                    </div>
                    <!-- MODAL ROW  END-->
                </div>


                <!-- MODAL ROW -->
                <p class="modal__warning">
                    Пожалуйста проверьте правильность написания <span>ФИО</span> и <span>Даты рождения. Это важно для справки</span>
                </p>
                <!-- MODAL ROW  END-->

                <!-- MODAL ROW -->
                <div class="modal__add-family add-family">
                    <p class="add-family__desc">Добавить ребенка/родственника в справку</p>
                    <a href="#" class="add-family__btn"><img src="app-images/add-icon.png">Добавить </a>
                </div>
                <ul class="modal-list"></ul>
                <!-- MODAL ROW  END-->
            </form1>
            <!-- FORM END-->
        </div>
        <!-- FOOTER -->
        <p class="modal__footer">
            <i class="demo-icon icon-location"></i> Мы находимся по адресу <span>г.Екатеринбург, ул. Барвинка 22</span>
        </p>
    </div>
</div>


<script>
    // Показываем модалку
    const modalOpen = document.querySelector('.call-modal');
    const modalOverlay = document.querySelector('.overlay');
    const modalClose = document.querySelector('.modal__exit');

    modalOpen.addEventListener('click', function () {
        modalOverlay.style.display = 'flex';
    })
    modalClose.addEventListener('click', function () {
        modalOverlay.style.display = 'none';
    })
    window.addEventListener('click', function (event) {
        if (event.target == modalOverlay) modalOverlay.style.display = 'none';
    })
    window.addEventListener('keydown', function (event) {
        if (event.key === "Escape") modalOverlay.style.display = 'none';
    })

    const addFamily = document.querySelector('.add-family__btn');
    const list = document.querySelector('.modal-list');
    button_num = 0;

    // Событие создаем структуру
    function createElements(e) {
        e.preventDefault();
        //m1 = document.getElementsByClassName('modal-list')[0].childNodes.length;
        //if (m1 > 1) return false;
        // Добавляем разметку
        button_num++;
        const li = document.createElement('li');
        li.innerHTML = `
		<div class="modal-list__header">
			<div class="user-data">

			</div>


			<div class="modal-list__edit">
				<a href="#" class="modal-list__btn modal-list__btn_edit${button_num}">редактировать</a>
				<a href="#" class="modal-list__btn modal-list__btn_remove${button_num}">удалить</a>
			</div>
		</div>

		<div class="modal-list__body">

		<div class="list-form">

			<div class="list-form__group">
				<p class="modal-form__field">
					<label for="family_name" class="modal-form__label">Имя<span class="modal-form__req">*</span></label>
					<input type="text" name="family_name" id="family_name" class="modal-form__input family_name">
				</p>
				<p class="modal-form__field">
					<label for="family_surename" class="modal-form__label">Фамилия<span class="modal-form__req">*</span></label>
					<input type="text" name="family_surename" id="family_surename" class="modal-form__input family_surename">
				</p>
			</div>

			<div class="list-form__group">
				<p class="modal-form__field">
					<label for="family_date" class="modal-form__label">Дата рождения<span class="modal-form__req">*</span></label>
					<input type="date" name="family_date" id="family_date" placeholder="ДД/ММ/ГГГГ" class="modal-form__input family_date">
				</p>
				<p class="modal-form__field modal-form__field_selected">
					<label for="family_select" class="modal-form__label">Укажите степень родства<span class="modal-form__req">*</span></label>
					<select name="family_select" id="family_select" class="modal-form__input modal-form__input_select family_select">
						<option value="son">Сын</option>
						<option value="husbend">Муж</option>
						<option value="wife">Жена</option>
						<option value="grandfather">Дедушка</option>
						<option value="grandmother">Бабушка</option>
					</select>
				</p>
			</div>

		</div>

		<div class="modal-list__edit list-edit">
			<button class="list-edit__btn list-edit__btn_save${button_num} list-edit__btn_save_imp">Сохранить</button>
			<button class="list-edit__btn list-edit__btn_remove${button_num} list-edit__btn_remove_imp">Удалить</button>
		</div>

		</div>`;
        // Добавялем в блок списка элемент списка
        list.appendChild(li);

        // Сохраняем блок

        let btn = document.querySelector('.list-edit__btn_save'+button_num);
        btn.addEventListener('click', function (event) {
        //$('.list-edit__btn_save').on('click', function () {

            console.log('list-edit__btn_save --- start')
            event.preventDefault();
            //
            var our_params = this.parentElement.parentElement.childNodes[1];
            var fm_name = our_params.getElementsByClassName('family_name')[0].value;
            var fm_surn = our_params.getElementsByClassName('family_surename')[0].value;
            var fm_slct = our_params.getElementsByClassName('family_select')[0].selectedOptions[0].label;
            var fm_date = our_params.getElementsByClassName('family_date')[0].value;
            console.log(fm_name);
            console.log(fm_surn);
            console.log(fm_slct);
            console.log(fm_date);
            //
            //if (document.querySelector('#family_name').value === ''|| document.querySelector('#family_surename').value==='' || document.querySelector('#family_date').value === '') {
            if ( fm_name === '' || fm_surn === '' || fm_slct === '' || fm_slct === '' ){
                alert('Пожалуйста заполните все поля')
            } else {
                var our_userdata = this.parentNode.parentNode.parentNode.childNodes[1].getElementsByClassName('user-data')[0];
                //document.querySelector('.user-data').innerHTML = `
                our_userdata.innerHTML = `
				<span class="modal-list__relative">
					${fm_slct}
                </span>
				<span class="modal-list__name">
					${fm_name}
					${fm_surn}
				</span>
				`;
                // for hide element...
                var fh = this.parentNode.parentNode.parentNode.childNodes[3];
                //document.querySelector('.modal-list__body').style.display = 'none';
                fh.style.display = 'none';
            }
            console.log('list-edit__btn_save --- stop')
        });

        //  Показываем
        var mlb = '.modal-list__btn_edit' + button_num;
        document.querySelector(mlb).addEventListener('click', function (event) {
            event.preventDefault();
            //document.querySelector('.modal-list__body').style.display = 'flex';
            this.parentNode.parentNode.parentNode.childNodes[3].style.display = 'flex';
        });
        //  Удаляем
        var ditem = '.list-edit__btn_remove' + button_num;
        document.querySelector(ditem).addEventListener('click', function () {
            this.parentElement.parentElement.parentElement.remove();
        });
        // delete - stage 2
        var del2 = '.modal-list__btn_remove' + button_num;
        document.querySelector(del2).addEventListener('click', function () {
            this.parentElement.parentElement.parentElement.remove();
        });
    }

    addFamily.addEventListener('click', createElements)

</script>

</body>

</html>


