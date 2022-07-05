<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">
    <title>Text File Parser</title>
    <style>
        table{
            text-align: left;
            border: 1px solid;
        }
        th{
            border-left: 1px solid #000;
            border-right: 1px solid #000;
        }
    </style>
</head>
<body>
<div class="form">
    <input type="text" id="file_path" name="file_path" value="test.log" required>
    <button id="view">View</button>
    <i id="errors" style="color: red"></i>
</div>

<div id="content">
    <div class="lines_table">
        <table id="lines">

        </table>

    </div>
    <div id="controller" style="display: none">
        <button id="head" class="control">|<</button>
        <button id="previous" class="control"> < </button>
        <button id="next" class="control"> > </button>
        <button id="tail" class="control">>|</button>

    </div>
</div>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<script>
    jQuery(document).ready(function ($){
        let current_page = 0;
        let file_path = '';

        function buildLinesTable(res)
        {
            $("#content").show()
            current_page = res.data.current_page;
            $('#lines').html('');
            let line_number = res.data.current_page * 10 -10;
            res.data.data.forEach(function (line){
                line_number++;
                let html_line =  "<tr><th>"+line_number+"</th><th>"+line+"</th><tr>"
                $('#lines').append(html_line);
            });
        }

        $('#view').on('click',function (){

            file_path = $('#file_path').val();
            axios({
                method: 'get',
                url: 'api/file',
                params:  {
                    file_path: file_path
                }
            }).then(function (res) {
                if(res.data.errors.length > 0)
                {
                    $("#content").hide()
                    $('#errors').html('');
                    $('#errors').append(res.data.errors.join(', '))
               } else if(res.data.data.length > 0)
                {
                   buildLinesTable(res);
                   $("#controller").show();
                   $(".control").attr( "disabled", true );
                   $("#tail").attr( "disabled", false );
                   $("#next").attr( "disabled", false);
                }
            });

        });

        $('#head').on('click',function (){

            axios({
                method: 'get',
                url: 'api/head',
                params:  {
                    file_path: file_path
                }
            }).then(function (res) {
                if(res.data.errors.length > 0)
                {
                    $('#errors').html('');
                    $('#errors').append(res.data.errors.join(', '))
                } else if(res.data.data.length > 0)
                {
                    buildLinesTable(res);

                    $(".control").attr( "disabled", true );
                    $("#tail").attr( "disabled", false );
                    $("#next").attr( "disabled", false);
                }
            });

        });

        $('#previous').on('click',function (){
            axios({
                method: 'get',
                url: 'api/previous',
                params:  {
                    file_path: file_path,
                    current_page: current_page
                }
            }).then(function (res) {
                if(res.data.errors.length > 0)
                {
                    $('#errors').html('');
                    $('#errors').append(res.data.errors.join(', '))
                } else if(res.data.data.length > 0)
                {
                    buildLinesTable(res);
                    $(".control").attr( "disabled", false );

                    if(current_page == 1){
                        $("#head").attr( "disabled", true );
                        $("#previous").attr( "disabled", true);
                    }
                }
            });
        });

        $('#next').on('click',function (){
            axios({
                method: 'get',
                url: 'api/next',
                params:  {
                    file_path: file_path,
                    current_page: current_page
                }
            }).then(function (res) {
                if(res.data.errors.length > 0)
                {
                    $('#errors').html('');
                    $('#errors').append(res.data.errors.join(', '))
                } else if(res.data.data.length > 0)
                {
                    buildLinesTable(res);

                    $(".control").attr( "disabled", false );
                    if(res.data.eof == true){
                        $("#tail").attr( "disabled", true );
                        $("#next").attr( "disabled", true);
                    }
                }
            });
        });

        $('#tail').on('click',function (){

            axios({
                method: 'get',
                url: 'api/tail',
                params:  {
                    file_path: file_path
                }
            }).then(function (res) {
                if(res.data.errors.length > 0)
                {
                    $('#errors').html('');
                    $('#errors').append(res.data.errors.join(', '))
                } else if(res.data.data.length > 0)
                {
                    buildLinesTable(res);
                    $(".control").attr( "disabled", true );
                    $("#head").attr( "disabled", false );
                    $("#previous").attr( "disabled", false);
                }
            });

        });

    });

</script>
</body>
</html>
