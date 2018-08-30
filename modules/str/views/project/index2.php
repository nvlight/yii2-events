<?php

use app\components\Debug;

?>

<div class="str-default-index">
    <p>
        Здесь планируется построить систему оформления работа по выполнению отделочных работ, по подсчетам материалов и денежных средств
    </p>

    <div class="row">
        <div class="col-sm-12">

            <div class="btn-group">
                <button type="button" class="btn btn-primary">Создать проект</button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Test Project</td>
                        <td>Sarmakovo</td>
                        <td>04-07-2018</td>
                        <td>
                            <a href="#">Open Project</a>
                        </td>
                    </tr>
                </table>
                <?php //echo Debug::d($projects,'$projects'); ?>
            </div>

        </div>
    </div>

</div>
