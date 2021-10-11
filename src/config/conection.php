<?php 

    function DBConnectMy() {
        $conexao = mysqli_connect('localhost', 'root', '');
        mysqli_select_db($conexao, 'projeto_audax');
        return $conexao;
    }    

    function DBClose($conexao) {

    }
?>