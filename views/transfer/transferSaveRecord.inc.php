<?php

require 'models/repository/record.class.php';
require_once 'views/repository/records/display.inc.php';


if(isset($_GET['id']) && isset($_POST['level_id']) && isset($_POST['nui']) && isset($_POST['title']) && isset($_POST['date_start']) && isset($_POST['organization_title'])){

            $level_id = htmlspecialchars ($_POST['level_id']);
            $nui = htmlspecialchars ($_POST['nui']);
            $title = htmlspecialchars ($_POST['title']);
            $date_start = htmlspecialchars ($_POST['date_start']);
            $date_end = htmlspecialchars ($_POST['date_end']);
            $observation = htmlspecialchars($_POST['observation']) ;
            $classification_id = htmlspecialchars ($_POST['classification_id']);
            $support = htmlspecialchars ($_POST['support']);
            $statut = htmlspecialchars ($_POST['statut']);
            $keywords = htmlspecialchars ($_POST['keywords']);
            $organization_title = htmlspecialchars ($_POST['organization_title']);
            $transfer_id = htmlspecialchars ($_GET['id']);
            

            $supportTitle = $_POST['support'] ;


            $record = new record();
            $record->setRecordId(NULL);
            $record->setRecordLevelId($level_id);
            $record->setRecordNui($nui);
            $record->setRecordTitle($title); 
            $record->setRecordTimeFormat($date_start);  
            $record->setRecordDateStart($date_start);               
            $record->setRecordDateEnd($date_end);
            $record->setRecordObservation($observation);
            $record->setRecordStatusTitle($statut);
            $record->setRecordClasseId($classification_id);
            $record->setRecordClasseById();
            $record->setRecordStatusTitle($statut);
            $record->setRecordSupportTitle($support); 
            $record->setRecordOrganizationTitle($organization_title);
            $record->setRecordIdByNui();
            $record->setRecordTransferId($transfer_id);

            if (!empty($_GET['parent_id'])) {
                $_GET['id_parent']= htmlspecialchars ($_GET['parent_id']);
                $record->setRecordLinkId($_GET['parent_id']);
            } else {}



            if($record ->controlNui() == TRUE){
                    $record->setRecordTempNui();
                    $record->saveRecord();
                    include "views/repository/records/saveRecordsKeywords.inc.php";
                } else {
                    $record->saveRecord();
                    include "views/repository/records/saveRecordsKeywords.inc.php";
                };
            $record->setRecordIdByNui();
            displayRecordLight($record);
            require_once "views/repository/records/recordContainerForm.php";
            echo 'Veuillez choisir le contenant à inserer...';
            insertRecordInUnkownContainer($record->getRecordId());


} else {

    $error = NULL;
    if(empty($_POST['nui'])){ $error['nui'] = 'Le numéro de référence est vide';}
    if(empty($_POST['title'])){ $error['title'] = 'Le titre est vide';}
    if(empty($_POST['date_start'])){ $error['date_start'] = 'Date de début vide';}
    if(empty($_POST['organization_title'])){ $error['organization_title'] = 'Service producteur est vide';}
    
    echo 'Erreur à corriger <ul>';
    foreach($error as $error_message){
        echo "<li><b>".$error_message."</b></li>";
    }
    echo '</ul>';

}

?>

<br>

<br>
<a href="index.php?q=repository&categ=create&sub=new">Nouveau Enregistrement</a>
<br>
<a href="index.php?q=repository&categ=create&sub=new">Modifier l'enregistrement</a>
<br>
<a href="index.php?q=repository&categ=create&sub=new">Supprimer</a>
<br>