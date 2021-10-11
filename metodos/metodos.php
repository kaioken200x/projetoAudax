<?php
session_start();
include('../src/config/conection.php');
$ConexaoMy = DBConnectMy();

if($_POST["metodo"] == "CarregarProduto"){

   $codigo = $_POST["codigo"];
 
   $SQL = "SELECT nome
           FROM produto
           WHERE codigo = ".$codigo."";
    $Query = mysqli_query($ConexaoMy,$SQL);
    $Array = mysqli_fetch_assoc($Query);
    
    die(json_encode($Array));
}

if ($_POST["metodo"] == "CarregaMateriais") {

   $dados = "";
   $SQL = "SELECT codigo,
                  nome
           FROM produto
           WHERE ativo = 1";
   $Query = mysqli_query($ConexaoMy, $SQL);
   if ((int)mysqli_num_rows($Query) > 0) {
      while ($Array = mysqli_fetch_assoc($Query)) {

         $codigo = $Array["codigo"];
         $nome = $Array["nome"];


         $btn_edita = "<span class='glyphicon glyphicon-cog' title='Editar' onclick='AtualizaMaterial(".$codigo.")'></span>";
         $btn_remove = "<span title='Remover' class='glyphicon glyphicon-trash' onclick='RemoveMaterial(".$codigo.")'></span>";


         $dados .= '<tr>';
         $dados .=   '<td>' . $nome . '</td>';
         $dados .=   '<td style ="text-align:center;width: 120px;">' . $btn_edita . '&nbsp;&nbsp;&nbsp;' . $btn_remove . '</td>';
         $dados .=  '</tr>';
      }
   }

   die($dados);
}
if ($_POST["metodo"] == "RemoveMaterial") {

   $codigo_produto = $_POST["codigo"];

   $SQL = "DELETE FROM produto WHERE codigo = '" . $codigo_produto . "'";
   $Query = mysqli_query($ConexaoMy, $SQL);
   if (!$Query) {

      $Arretorno[0] = 0;
      $Arretorno[1] = "Não Foi Possível Remover este Produto";
      die(json_encode($Arretorno));
   } else {

      $Arretorno[0] = 1;
      $Arretorno[1] = "Produto Removido com sucesso !";
      die(json_encode($Arretorno));
   }
   die($dados);
}
if ($_POST["metodo"] == "FiltrarProduto") {


   $filtro = $_POST["descricao"];
   $dados = "";

   $SQL = "SELECT codigo,
                  nome
           FROM produto
           WHERE ativo = 1 AND nome like '%" . $filtro . "%'  ";
   $Query = mysqli_query($ConexaoMy, $SQL);
   if ((int)mysqli_num_rows($Query) > 0) {
      while ($Array = mysqli_fetch_assoc($Query)) {

         $codigo = $Array["codigo"];
         $nome   = $Array["nome"];

         $btn_edita = "<span class='glyphicon glyphicon-cog' title='Editar' onclick='AtualizaMaterial(".$codigo.")'></span>";
         $btn_remove = "<span title='Remover' class='glyphicon glyphicon-trash' onclick='RemoveMaterial(".$codigo.")'></span>";


         $dados .= '<tr>';
         $dados .=   '<td>' . $nome . '</td>';
         $dados .=   '<td style ="text-align:center;width: 120px;">' . $btn_edita . '&nbsp;&nbsp;&nbsp;' . $btn_remove . '</td>';
         $dados .=  '</tr>';
      }
   }

   die($dados);
}

if ($_POST["metodo"] == "CadastrarMaterial") {

   $codigo = $_POST["codigo"];
   
   $descricao = $_POST["produto"];
  
   $SQL = "SELECT nome 
            FROM produto 
            WHERE nome = '" . $descricao . "' ";
   $Query = mysqli_query($ConexaoMy, $SQL);
   $Array = mysqli_fetch_assoc($Query);

   $nome = $Array["nome"];

   if ($codigo == '') {
      $SQLINSERT = "INSERT INTO produto(
                                     nome,
                                     ativo,
                                     data_hora_cadastro
                                     )
                                     VALUES
                                     (
                                     '" . $descricao . "',
                                     '1',
                                     now()   
                                      );";
                                      
      $QueryInsert = mysqli_query($ConexaoMy, $SQLINSERT);
      if (!$QueryInsert) {
         $ArrRetorno[0] = 0;
         $ArrRetorno[1] = "Erro ao Inserir Produto, o Produto pode estar duplicado !";
         die(json_encode($ArrRetorno));
      } else {
         $ArrRetorno[0] = 1;
         $ArrRetorno[1] = "Produto Cadastrado !";
         die(json_encode($ArrRetorno));
      }
   } else {
      $SQLUPDATE = "UPDATE produto 
                    SET nome = '".$descricao."'
                    WHERE codigo = ".$codigo."";
      $QueryUpdate = mysqli_query($ConexaoMy, $SQLUPDATE);
      if (!$QueryUpdate) {
         $ArrRetorno[0] = 0;
         $ArrRetorno[1] = "Produto Não atualizado !";
         die(json_encode($ArrRetorno));
      }else{

         $ArrRetorno[0] = 1;
         $ArrRetorno[1] = "Produto atualizado !";
         die(json_encode($ArrRetorno));
      }
   }

   die(json_encode($ArrRetorno));
}
if($_POST["metodo"] == "CarregarDadosMaterial"){


   $codigo = $_POST["codigo"];
 
   $SQL = "SELECT nome,
           FROM produto
           WHERE codigo = ".$codigo."";
    $Query = mysqli_query($ConexaoMy,$SQL);
    $Array = mysqli_fetch_assoc($Query);
    
    die(json_encode($Array));
 }



 if ($_POST["metodo"] == "FiltrarUsuario") {

   $filtro = $_POST["descricao"];
   $dados = "";

   $SQL = "SELECT codigo,
                  nome,
                  funcao,
                  email
           FROM usuario
           WHERE ativo = 1 AND nome like '%" . $filtro . "%'  ";
   $Query = mysqli_query($ConexaoMy, $SQL);
   if ((int)mysqli_num_rows($Query) > 0) {
      while ($Array = mysqli_fetch_assoc($Query)) {

         $codigo = $Array["codigo"];
         $nome   = $Array["nome"];
         $funcao = $Array["funcao"];
         $email  = $Array["email"];

         if ($funcao == 1) {
            $funcao = "Administrador";
         } elseif ($funcao == 2) {
            $funcao = "Solicitante";
         } elseif ($funcao == 3) {
            $funcao = "Aprovador";
         }

         $btn_edita = "<span class='glyphicon glyphicon-cog' title='Editar'></span>";
         $btn_remove = "<span title='Remover' class='glyphicon glyphicon-trash'></span>";


         $dados .= '<tr>';
         $dados .=   '<td>' . $nome . '</td>';
         $dados .=   '<td>' . $email . '</td>';
         $dados .=   '<td>' . $funcao . '</td>';
         $dados .=   '<td style="width:100px;">' . $btn_edita . '&nbsp;&nbsp;&nbsp;' . $btn_remove . '</td>';
         $dados .=  '</tr>';
      }
   }

   die($dados);
}
if ($_POST["metodo"] == "CadastroUsuario") {
  
   $nome   = $_POST["nome"];
   $funcao = $_POST["funcao"];
   $email  = $_POST["email"];
   $senha  = $_POST["senha"];
   $codigo = $_POST["codigo"];
  
   $SQL = "SELECT codigo, 
                  email
           FROM usuario 
           WHERE codigo = '".$codigo."'";
   $Query = mysqli_query($ConexaoMy, $SQL);
   $Array = mysqli_fetch_assoc($Query);

   if ($Array["codigo"] == '') {
      $SQLINSERT = "INSERT INTO usuario(
                                        nome,
                                        funcao,
                                        email,
                                        senha,
                                        ativo,
                                        data_hora_cadastro
                                        )
                                        VALUES
                                        (
                                        '" . $nome . "',
                                        '" . $funcao . "',
                                        '" . $email . "',
                                        '" . $senha . "',
                                        1,
                                        NOW()
                                        );";

      $QueryInsert = mysqli_query($ConexaoMy, $SQLINSERT);
      if (!$QueryInsert) {
         $ArrRetorno[0] = 0;
         $ArrRetorno[1] = "Erro ao Inserir Usuário !";
      } else {
         $ArrRetorno[0] = 1;
         $ArrRetorno[1] = "Usuário Cadastrado !";
      }
   } else {

      $SQL = "UPDATE usuario SET nome = '".$nome."',
                         funcao = ".$funcao.",
                         email = '".$email."',
                         senha = '".$senha."'
              WHERE codigo = ".$codigo."";
      $QueryUpdate = mysqli_query($ConexaoMy, $SQL);
      if (!$QueryUpdate) {
         $ArrRetorno[0] = 0;
         $ArrRetorno[1] = "Erro ao Atualizar Usuário !";
      }
      else{
         $ArrRetorno[0] = 1;
         $ArrRetorno[1] = "Usuário Atualizado !";
      }
   }

   die(json_encode($ArrRetorno));
}
if ($_POST["metodo"] == "CarregaUsusario") {

   $dados = "";
   $SQL = "SELECT codigo,
                  nome,
                  funcao,
                  email
           FROM usuario
           WHERE ativo = 1";
   $Query = mysqli_query($ConexaoMy, $SQL);
   if ((int)mysqli_num_rows($Query) > 0) {
      while ($Array = mysqli_fetch_assoc($Query)) {

         $codigo = $Array["codigo"];
         $nome = $Array["nome"];
         $funcao = $Array["funcao"];
         $email = $Array["email"];

         if ($funcao == 1) {
            $funcao = "Administrador";
         } elseif ($funcao == 2) {
            $funcao = "Solicitante";
         } elseif ($funcao == 3) {
            $funcao = "Aprovador";
         }
         $nomeTelaCadastro = "cadastrar.html";
         $codigoParametro = "codigo";

         $btn_edita = "<span class='glyphicon glyphicon-cog' title='Editar' onclick='AtualizaUsuario(" . $codigo . ");'></span>";
         /*$btn_edita = "<span class='glyphicon glyphicon-cog' title='Editar' onclick='AtualizaUsuario(\'".$nomeTelaCadastro."\',\'edt=1&'".$codigoParametro."'='".$codigo."'\')";*/
         $btn_remove = "<span title='Remover' class='glyphicon glyphicon-trash' onclick='RemoveUsuario(" . $codigo . ");'></span>";


         $dados .= '<tr>';
         $dados .=   '<td>' . $nome . '</td>';
         $dados .=   '<td>' . $email . '</td>';
         $dados .=   '<td>' . $funcao . '</td>';
         $dados .=   '<td style ="text-align:center;width:100px;">' . $btn_edita . '&nbsp;&nbsp;&nbsp;' . $btn_remove . '</td>';
         $dados .=  '</tr>';
      }
   }

   die($dados);
}
if ($_POST["metodo"] == "RemoveUsuario") {

   $codigo_usuario = $_POST["codigo"];

   $SQL = "SELECT codigo FROM usuario WHERE codigo = '" . $codigo_usuario . "' ";
   $Query = mysqli_query($ConexaoMy, $SQL);
   $Array = mysqli_fetch_assoc($Query);

   if ($Array["codigo"] == 1) {

      $Arretorno[0] = 0;
      $Arretorno[1] = "Usuário Administrador do sistema não foi possível removerlo!";
      die(json_encode($Arretorno));
   } else {
      $SQL = "DELETE FROM usuario WHERE codigo = '" . $codigo_usuario . "'";
      $Query = mysqli_query($ConexaoMy, $SQL);
      if (!$Query) {

         $Arretorno[0] = 0;
         $Arretorno[1] = "Não Foi Possível Remover este Usuário";
         die(json_encode($Arretorno));
      } else {

         $Arretorno[0] = 1;
         $Arretorno[1] = "Usuário Removido com sucesso !";
         die(json_encode($Arretorno));
      }
   }

   die($dados);
}
if ($_POST["metodo"] == "FiltrarUsuario") {


   $filtro = $_POST["descricao"];
   $dados = "";

   $SQL = "SELECT codigo,
                  nome,
                  email,
                  funcao
           FROM usuario
           WHERE ativo = 1 AND nome like '%" . $filtro . "%'  ";
   $Query = mysqli_query($ConexaoMy, $SQL);
   if ((int)mysqli_num_rows($Query) > 0) {
      while ($Array = mysqli_fetch_assoc($Query)) {

         $codigo = $Array["codigo"];
         $nome   = $Array["nome"];
         $email   = $Array["email"];
         $funcao   = $Array["funcao"];

         if ($funcao == 1) {
            $funcao = "Administrador";
         }elseif($funcao == 2){
            $funcao = "Solicitante";
         }elseif($funcao == 3){
            $funcao = "Aprovador";
         }

         $btn_edita = "<span class='glyphicon glyphicon-cog' title='Editar' onclick='AtualizaUsuario(".$codigo.")'></span>";
         $btn_remove = "<span title='Remover' class='glyphicon glyphicon-trash' onclick='RemoveUsuario(".$codigo.")'></span>";


         $dados .= '<tr>';
         $dados .=   '<td>' . $nome . '</td>';
         $dados .=   '<td>' . $email . '</td>';
         $dados .=   '<td>' . $funcao . '</td>';
         $dados .=   '<td style ="text-align:center;width: 120px;">' . $btn_edita . '&nbsp;&nbsp;&nbsp;' . $btn_remove . '</td>';
         $dados .=  '</tr>';
      }
   }

   die($dados);
}
if($_POST["metodo"] == "CarregarDadosUsuario"){

   $codigo = $_POST["codigo"];
 
   $SQL = "SELECT nome,
                  funcao,
                  email,
                  senha
           FROM usuario
           WHERE codigo = ".$codigo."";
    $Query = mysqli_query($ConexaoMy,$SQL);
    $Array = mysqli_fetch_assoc($Query);
    
    die(json_encode($Array));
 }
 


 /*if ($_POST["metodo"] == "CarregarProdutosCheck") {
   
   $SQL="SELECT codigo,
                nome produto 
         WHERE ativo = 1;";
   $Query = mysqli_query($ConexaoMy, $SQL);
   if ((int)mysqli_num_rows($Query) > 0)) {
      
      while ($Array = mysqli_fetch_assoc($Query)) {
         

      }
   } 
   


}*/
?>