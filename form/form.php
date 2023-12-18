<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" /> 
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
        <link href="../project/form/style_form.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <style>
            .container{
                margin-top: 50px;
                border-radius: 15px;
                border: 3px solid #333;
            }
            .col-3{
                background-color: #FF9A00;
                border-radius: 0;
                border-top-left-radius: 13px;
                border-bottom-left-radius: 13px;
                display: flex;
                align-items: center;
                flex-flow: column;
                justify-content: center;
                font-size: 26px;
                font-weight: 900;
            }
            label:not([class="form-check-label"]) {
                font-size: 16px;
                font-weight: 600;
            }
            .form-check-input:checked{
                background-color: #FF9A00;
                border-color: #FF9A00;
            }
            .col-9{
                padding: 25px;
            }
            .btn-primary {
                color: #fff;
                background-color: #FF9A00;
                border-color: #FF9A00;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row row-body">
                <div class="col-3">
                    <span style="text-align: center">Форма расчета</span>
                    <i class="bi bi-activity"></i>
                </div>
                <div class="col-9">
                    <form action="" id="form">
                            <label class="form-label" for="product">Выберите продукт:</label>
                            <select class="form-select" name="product" id="product">
                            <?php
                                $products = $dbh->mselect_rows('a25_products', null, null, null, 'ID');
                                foreach ($products as $s) {
                                    echo '<option value="'.$s['ID'].'">'.$s['NAME'].'</option>';
                                }
                            ?>
                            </select>

                            <label for="customRange1" class="form-label">Количество дней:</label>
                            <input type="text" name="days" class="form-control" id="customRange1" min="1" max="30">

                            <label>Дополнительно:</label>
                            <?php
                                $settings = unserialize($dbh->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value']);
                                $count = 0;
                                foreach ($settings as $k => $s) {
                                    echo '
                                    <div class="form-check">
                                        <input class="form-check-input" name="additional'.$count.'" type="checkbox" value="'.$s.'" id="additional'.$count.'" checked>
                                        <label class="form-check-label" for="additional'.$count.'">
                                            '.$k.'
                                        </label>
                                    </div>
                                    ';
                                    $count++;
                                }
                            ?>
                            <button type="button" id="test"  class="btn btn-primary">Рассчитать</button>
                            <div class="col-5">
                            <label>Итого: </label>
                            <label id="totalCost"></label>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>

<script>
$(document).ready(function(){
    $('#test').click(function(){
        let formData = {};
        // // Получаем данные из всех чекбоксов
        formData['additional'] = {};
        $('input[type="checkbox"]').each(function(){
            if ( $(this).is(':checked')) {
                formData['additional'][$(this).attr('name')] = $(this).val();
            }
        });
        // Получаем данные из всех селекторов
        $('select').each(function(){
            formData[$(this).attr('name')] = $(this).val();
        });
        // Получаем данные из всех инпутов
        $('input[type="text"]').each(function(){
            formData[$(this).attr('name')] = $(this).val();
        });

        $.ajax({
            type: "POST",
            url: "backend/request.php", // замените на адрес вашего обработчика
            data: formData,
            success: function(response){
                $('#totalCost').text(response);
                console.log(response);
            },
        });
    });
});
</script>