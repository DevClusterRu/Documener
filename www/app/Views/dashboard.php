<?php echo $this->extend('dashboard_template') ?>
<?php echo $this->section('content') ?>

    <style>
        h1 {
            font-size: 25px;
            border-bottom: 1px solid;
            margin-bottom: 30px;
            padding-bottom: 10px;
            color: #4c939e;
        }

        h1 > a {
            margin-left: 20px;
            border: none !important;
        }
    </style>


    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-light navbar-light">
        <!-- Container wrapper -->
        <div class="container-fluid">
            <!-- Navbar brand -->
            <a class="navbar-brand" href="#">Documenter</a>
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->


    <div class="container-fluid">
        <div style="margin-bottom: 20px"></div>
        <div class="row">
            <div class="col-4">
                <h1>Форма</h1>
                <form>
                    <div class="mb-3">
                        <label class="form-label">Название</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Описание</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Цена</label>
                        <input type="text" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
            </div>

            <div class="col-8">
                <form style="display: none" enctype="multipart/form-data" method="post" id="s_file" action="/upload">
                    <input type="file" name="source">
                </form>
                <h1>Источник<a id="f_loader" class="btn btn-primary" href="#">Загрузить</a></h1>

                <div>
                    <?php
                    if (isset($errors)) {
                        var_dump($errors);
                    } else {
                        if (isset($uploaded)) {
                            ?>
                            <script>
                                var image = "<?php echo $uploaded?>"
                            </script>

                            <img class="donor" src="<?php echo $uploaded ?>">

                            <?php
                        }
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>


    <script>
        var x, y, w, h

        function chAreas(event, id, areas) {
            console.log(event)
            x = Math.round(areas[id].x)
            y = Math.round(areas[id].y)
            w = Math.round(areas[id].width)
            h = Math.round(areas[id].height)
        }
    </script>


    <script>
        areas = [];

        $('.donor').selectAreas({
            areas: areas,
            allowDelete: true,
            allowNudge: false,
            overlayOpacity: 0.2,
            onChanged: chAreas,
            //onChanged: chAreas,
        });

        $("#f_loader").click(function () {
            $("[name=source]").click()
        });

        $("[name=source]").change(function () {
            if ($(this).val() != "") {
                $("#s_file").submit()
            }
        })
    </script>


    <script>
        $("input").dblclick(function () {
            let obj = this
            $.ajax({
                url: '/recognize',
                type: 'post',
                data: {
                    'x': x,
                    'y': y,
                    'w': w,
                    'h': h,
                    'image': image,
                },
                success: function (data) {
                    $(obj).val(data)
                }
            })
        })
    </script>


<?php echo $this->endSection() ?>