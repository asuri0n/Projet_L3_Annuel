<?php
    if(isset($_SESSION['error']) and !empty($_SESSION['error'])){
        echo "<script>
        $.notify({
            message: '".$_SESSION['error']."'
        },{
            type: 'danger'
        });
        </script>";
        unset($_SESSION['error']);
    }
    if(isset($_SESSION['success']) and !empty($_SESSION['success'])) {
        echo "<script>
        $.notify({
            message: '".$_SESSION['success']."'
        },{
            type: 'success'
        });
        </script>";
        unset($_SESSION['success']);
    }
