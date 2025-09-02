<?php

session_start();

include_once("connection.php");
include_once("url.php");

$data = $_POST;

// modificaões no banco
if (!empty($data)) {


    // criar contatos
    if ($data["type"] === "create") {

        $name = $data["name"];
        $phone = $data["phone"];
        $observations = $data["observations"];

        $query = "INSERT INTO contacts (name, phone, observations) VALUES (:name, :phone, :observations)";

        $stmt = $conn->prepare($query);

        $stmt->bindParam( ":name", $name);
        $stmt->bindParam( ":phone", $phone);
        $stmt->bindParam( ":observations", $observations);

        try {

            $stmt->execute();
            $_SESSION["msg"] = "Contato criado com sucesso";

        } catch (PDOException $e) {
            // erro na conexxao
            $error = $e->getMessage();
            echo "Erro: $error";
        }
    }

    // redirect HOME 
    header("Location:" . $BASE_URL . "../index.php");

    // seleçao de dados

} else {

    $id;

    if (!empty($_GET)) {
        $id = $_GET["id"];
    }

    // retorna o dado de um contato

    if (!empty($_GET)) {

        $query = "SELECT * FROM contacts WHERE id = :id";

        $stmt = $conn->prepare($query);

        $stmt->bindParam("id", $id);

        $stmt->execute();

        $contact = $stmt->fetch();

    } else {

    }

    // retorna todos os contatos
    $contacts = [];

    $query = "SELECT * FROM contacts";

    $stmt = $conn->prepare($query);

    $stmt->execute();

    $contacts = $stmt->fetchAll();

}


// fechar conexão 

$conn = null;

