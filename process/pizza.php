<?php
include_once("conection.php");
$method = $_SERVER["REQUEST_METHOD"];

if($method === "GET"){ //resgate dos dados, montagem do pedido

    $bordasQuery = $conn -> query("SELECT * FROM bordas;");
    $bordas = $bordasQuery->fetchAll();

    $massasQuery = $conn -> query("SELECT * FROM massas;");
    $massas = $massasQuery->fetchAll();

    $saboresQuery = $conn -> query("SELECT * FROM sabores;");
    $sabores = $saboresQuery->fetchAll();


} else if($method === "POST"){ //criação do pedido
    $data = $_POST;
    $borda = $data["borda"];
    $massa = $data["massa"];
    $sabores = $data["sabores"];

    //validação
    if(count($sabores) >2){
        $_SESSION["msg"] = "selecione no máximo 2 sabores";
        $_SESSION["status"] = "warning";
    } else{
        //complementando o pedido para salvar e dar o insert
        $estadopedido = $conn->prepare("INSERT INTO pizzas (borda_id, massa_id) VALUES(:borda, :massa)");

        //FILTRANDO INPUTS
       $estadopedido->bindParam(":borda", $borda, PDO::PARAM_INT);
       $estadopedido->bindParam(":massa", $massa, PDO::PARAM_INT);
       $estadopedido->execute();

       //RESGATANDO ÚLTIMO ID DA ULTIMA PIZZA
       $pizzaId = $conn->lastInsertId();

       $estadopedido = $conn->prepare("INSERT INTO pizza_sabor (pizza_id, sabor_id) VALUES(:pizza, :sabor)");

       //repetição até salvar todos os sabores RELAÇÃO MANY TO MANY
       foreach($sabores as $sabor){
       //filtrando inputs
       $estadopedido->bindParam(":pizza", $pizzaId, PDO::PARAM_INT);
       $estadopedido->bindParam(":sabor", $sabor, PDO::PARAM_INT);

       $estadopedido->execute();

       }
       //criar o pedido da pizza
       $estadopedido = $conn->prepare("INSERT INTO pedidos (pizza_id, status_id) VALUES(:pizza, :status)");
       //status sempre incia com 1, que é em produção
       $statusId = 1;
       //filtrar
       $estadopedido->bindParam(":pizza", $pizzaId);
       $estadopedido->bindParam(":status", $statusId);
       
       $estadopedido->execute();

       $_SESSION["msg"] = "Pedido Realizado com Sucesso, Acompanhe por aqui!";
       $_SESSION["status"] = "success";

    }
    //Retorna para a página inicial
    header("Location: ..");
}
?>