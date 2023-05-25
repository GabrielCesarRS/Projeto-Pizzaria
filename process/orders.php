<?php
include_once("conection.php");

$method = $_SERVER["REQUEST_METHOD"];

if($method === "GET"){

    $pedidosQuery = $conn->query("SELECT * FROM pedidos;");
    $pedidos = $pedidosQuery->fetchAll();
    $pizzas = []; //ARRAY
    //MONTANDO A PIZZA
    foreach($pedidos as $pedido){
        $pizza = [];
        //definir um array para pizza
        $pizza["id"] = $pedido["pizza_id"];

        //resgatando dados da pizza
        $PizzaQuerry = $conn->prepare("SELECT * FROM pizzas WHERE id = :pizza_id");
        $PizzaQuerry->bindParam(":pizza_id", $pizza["id"]);
        $PizzaQuerry->execute();
        $pizzaData = $PizzaQuerry->fetch(PDO::FETCH_ASSOC);
        //RESGATANDO A BORDA
        $bordaQuerry = $conn->prepare("SELECT * FROM bordas WHERE id = :borda_id");
        $bordaQuerry->bindParam(":borda_id", $pizzaData["borda_id"]);
        $bordaQuerry->execute();
        $borda = $bordaQuerry->fetch(PDO::FETCH_ASSOC);
        $pizza["borda"] = $borda["tipo"];
        //REGATANDO A MASSA
        $massaQuerry = $conn->prepare("SELECT * FROM massas WHERE id = :massa_id");
        $massaQuerry->bindParam(":massa_id", $pizzaData["massa_id"]);
        $massaQuerry->execute();
        $massa = $massaQuerry->fetch(PDO::FETCH_ASSOC);
        $pizza["massa"] = $massa["tipo"];
        //RESGATANDO OS SABORES
        $saboresQuerry = $conn->prepare("SELECT * FROM pizza_sabor WHERE pizza_id = :pizza_id");
        $saboresQuerry->bindParam(":pizza_id", $pizza["id"]);
        $saboresQuerry->execute();
        $sabores = $saboresQuerry->fetchAll(PDO::FETCH_ASSOC);
        //resgatado o nome dos sabores
        $saboresdaPizza = [];
        $saborQuery = $conn->prepare("SELECT * FROM sabores WHERE id = :sabor_id");
        foreach($sabores as $sabor){
            $saborQuery->bindParam(":sabor_id", $sabor["sabor_id"]);
            $saborQuery->execute();
            $saborPizza = $saborQuery->fetch(PDO::FETCH_ASSOC);
            array_push($saboresdaPizza, $saborPizza["nome"]);
        }
        $pizza["sabores"] = $saboresdaPizza;
        //adicionar status do pedido
        $pizza["status"] = $pedido["status_id"];
        //add o rrayde pizza, ao array das pizzas
        array_push($pizzas, $pizza);
    }
    //Resgatando os status
    $statusQuerry = $conn->query("SELECT * FROM status;");
    $status = $statusQuerry->fetchAll();
} else if($method === "POST") {
    //VERIFICANDO O TIPO DE POST PARA EXCLUSÃO/UPDATE
    $type = $_POST["type"];
    //deletar pedido
    if($type === "delete"){
        $pizzaId = $_POST["id"];
        $deleteQuery = $conn->prepare("DELETE FROM pedidos WHERE pizza_id = :pizza_id;");
        $deleteQuery->bindParam(":pizza_id", $pizzaId, PDO::PARAM_INT); // para ter certeza q é um INT, já q vem do cliente
        $deleteQuery->execute();
        $_SESSION["msg"] = "Pedido removido com sucesso";
        $_SESSION["status"] = "success";
    }
    //atualizar o status do pedido
    else if($type === "update"){
        $pizzaId = $_POST["id"];
        $statusId = $_POST["status"];
        $updateQuery = $conn->prepare("UPDATE pedidos SET status_id = :status_id WHERE pizza_id = :pizza_id;");
        $updateQuery->bindParam(":pizza_id", $pizzaId, PDO::PARAM_INT);
        $updateQuery->bindParam(":status_id", $statusId, PDO::PARAM_INT); // para ter certeza q é um INT, já q vem do cliente
        $updateQuery->execute();
        $_SESSION["msg"] = "Pedido atualizado com sucesso";
        $_SESSION["status"] = "success";
    }
    //retorna para dashboard
    header("Location: ../dashboard.php");

}
?>