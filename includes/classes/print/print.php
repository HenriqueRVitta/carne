<?php

//print_r($_SERVER['DOCUMENT_ROOT']);
//break;

//require("/carne/includes/header_print.php");

?>
        <script>
            window.print();
            setTimeout(function () { window.close(); }, 100);
        </script>

        <style>
            body{
                display:none;
            }
        </style>

        <!-- print -->
        <link rel="stylesheet" href="/css/print.css" type="text/css" media="print" />
        <link rel="stylesheet" href="css/relatorios/standard/index.css" type="text/css" media="print" />
    </head>
    <body>

        <?php echo $_POST['conteudo'];?>

    </body>
</html>