
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
    <style>
         .container a{
    color: black;
 
    background-color: white;
    border-radius: 14px;
    font-weight: bold;
    font-size: 16px;
    width: 80%;

    background-image: radial-gradient(0% 0% at 0% 0%, rgba(0, 0, 0, 0.23) 0%, transparent 86.18%), radial-gradient(66% 66% at 26% 20%, rgba(255, 255, 255, 0.55) 0%, rgba(255, 255, 255, 0) 69.79%, rgba(255, 255, 255, 0) 100%);
    box-shadow: inset -3px -3px 9px rgba(255, 255, 255, 0), inset 0px 3px 9px rgba(255, 255, 255, 0), inset 0px 1px 1px rgba(255, 255, 255, 0), inset 0px -8px 36px rgba(0, 0, 0, 0), inset 0px 1px 5px rgba(255, 255, 255, 0), 2px 19px 31px rgba(0, 0, 0,0);
    
    
    border: 0;
  
    user-select: none;
    -webkit-user-select: none;
    touch-action: manipulation;
  
    cursor: pointer;
  } 

    </style>
    
</body>
</html>

<?php
require_once 'models/repository/record.class.php';
require_once 'models/repository/keyword.class.php';
require_once 'models/tools/organization/organization.class.php';

function displayRecordDefault($record){
    $record-> setRecordLevelTitleByLevelId();
    
    echo "<div class=\"recordTitle\">";
    echo "<div class=\"title\"> <img src=\"template\images\plus.png\" class=\"iconePlusMoins\"><a href=\"index.php?q=repository&categ=search&sub=display&id=". $record->getRecordId()."\">". $record-> getRecordTitle() ."  (". $record-> getRecordLevelTitle().")</div>";
    echo "<div class=\"date\">". $record ->getRecordDateStart() ." - ". $record ->getRecordDateStart()."</div>";
    echo "</a>";  
    echo "</div>";

    echo "<div class=\"records\" >";
    // Options sur la fiche
    echo "<div style=\"float:right; width:200px;border:solid 2px yellow;\">";
        optionNavigationAdvanced($record);
    echo "</div>";

    // Aficher les enregistrement
    echo "<div class=\"\" style=\"border:solid 2px red;width:650px;\">";
    $record -> setRecordClasseByTitle();
    $record -> setRecordContainerId();
    $organization = new organization();
    $organization -> setOrganizationById($record -> getRecordOrganizationId());

    echo "<table class=\"\"> 
    <tr><th class=\"element\"> Description <td>". $record -> getRecordObservation()  ."</td></tr>
    <tr><th class=\"element\"> Reférence <td>". $record-> getRecordNui() ."</td></tr>
    <tr><th class=\"element\"> Dates <td>". $record -> getRecordDateStart() ." au ". $record -> getRecordDateEnd()  ."</td></tr>
    <tr><th class=\"element\"> Détenteur <td>
    <a href=\"index.php?q=repository&categ=search&sub=organization&id=".$organization->getOrganizationId()."\">
    ". $record -> getRecordOrganizationTitle() ."(". $organization -> getOrganizationCode() .")</a> </td></tr>
    <tr><th class=\"element\"> Contenant <td><a href=\"index.php?q=repository&categ=search&sub=container&id=".$record -> getRecordContainerId() ."\">". $record -> getRecordContainerTitle() ."</a></td></tr>
    <tr><th class=\"element\"> Classe <td><a href=\"index.php?q=repository&categ=search&sub=byClasseId&id=".$record ->getRecordClasseId()."\">". $record -> getRecordClasseCode() ." - ".$record -> getRecordClasseTitle() ."</a></td></tr>
    ". displayParentTitle($record) ."
    <tr><th class=\"element\"> Support <td>". $record -> getRecordSupportTitle() ."</td></tr>
    <tr><th class=\"element\"> Mots clés <td>";

    // Afficher les mots clés associés
    $KeywordsId = $record -> getAllKeywordsIdByRecordId();
    if(isset($KeywordsId)){
            foreach($KeywordsId as $KeywordId){
                    $word = new keyword();
                    $word -> setKeywordId($KeywordId['keyword_id']); 
                    echo "<a href=\"index.php?q=repository&categ=search&sub=byKeywordId&id=".$KeywordId['keyword_id']."\">";
                    echo $word -> getKeywordById();
                    echo "</a>, " ;
            }}
    echo "</td></tr><tr><td colspan=\"2\">";
    RecordsSubList($record);
    echo "</td></tr></table>";
    echo "</div>";
    echo "</div>";

}


function displayRecordLight($record){
    // Aficher les enregistrement
    $record-> setRecordLevelTitleByLevelId();
    echo "<p style=\"margin-bottom:20px;font-size:16px;\"><a href=\"index.php?q=repository&categ=search&sub=display&id=".$record->getRecordId() ."\">";
    echo  $record-> getRecordTitle() ."  (". $record-> getRecordLevelTitle().")". $record->getRecordDateStart()." au ". $record ->getRecordDateEnd() ."; Ref n° ". $record-> getRecordNui()." : "."</a><p/>";

}

function displayRecord($record){
    // $record->setRecordLevelTitleByLevelId();
    
    // Options sur la fiche
    echo "<div style=\"float:right; width:200px;border:solid 2px yellow;\">";
        optionNavigation($record);
    echo "</div>";

    echo "<div class=\"\" style=\"border:solid 2px red;width:650px;\">";
    // Aficher les enregistrement
    $record -> setRecordClasseByTitle();
    $record -> setRecordContainerId();
    $organization = new organization();
    $organization -> setOrganizationById($record -> getRecordOrganizationId());
 
    echo "<table border=\"1\"> 
    <tr><th class=\"title\" colspan=\"2\">
    <a href=\"index.php?q=repository&categ=search&sub=display&id=".$record->getRecordId()."\">". $record-> getRecordTitle() ."  (". $record-> getRecordLevelTitle().")"."</a></th></tr> 
    <tr><th class=\"element\"> Reférence <td class=\"element\">". $record-> getRecordNui() ."</td></tr>
    <tr><th class=\"element\"> Dates <td class=\"element\">". $record -> getRecordDateStart() ." au ". $record -> getRecordDateEnd()  ."</td></tr>
    <tr><th class=\"element\"> Détenteur <td class=\"element\">
    <a href=\"index.php?q=repository&categ=search&sub=organization&id=".$organization->getOrganizationId()."\">
    ". $record -> getRecordOrganizationTitle() ."(". $organization -> getOrganizationCode() .")</a> </td></tr>
    <tr><th class=\"element\"> Observation <td class=\"element\">". $record -> getRecordObservation()  ."</td></tr>
    <tr><th class=\"element\"> Contenant <td class=\"element\"><a href=\"index.php?q=repository&categ=search&sub=container&id=".$record -> getRecordContainerId() ."\">". $record -> getRecordContainerTitle() ."</a></td></tr>
    <tr><th class=\"element\"> Classe <td class=\"element\"><a href=\"index.php?q=repository&categ=search&sub=byClasseId&id=".$record ->getRecordClasseId()."\">". $record -> getRecordClasseCode() ." - ".$record -> getRecordClasseTitle() ."</a></td></tr>
    ". displayParentTitle($record) ."
    <tr><th class=\"element\"> Support <td class=\"element\">". $record -> getRecordSupportTitle() ."</td></tr>
    <tr><th class=\"element\"> Mots clés <td class=\"element\">";

    // Afficher les mots clés associés
    $KeywordsId = $record -> getAllKeywordsIdByRecordId();
    if(isset($KeywordsId)){
            foreach($KeywordsId as $KeywordId){
                    $word = new keyword();
                    $word -> setKeywordId($KeywordId['keyword_id']); 
                    echo "<a href=\"index.php?q=repository&categ=search&sub=byKeywordId&id=".$KeywordId['keyword_id']."\">";
                    echo $word -> getKeywordById();
                    echo "</a>, " ;
            }}
    echo "</td></tr><tr><td colspan=\"2\">";
    RecordsSubList($record);
    echo "</td></tr></table>";
    echo "</div>";
    echo "</div>";

}

// Affichage long

function displayRecordLong($record){
    displayRecord($record);
    echo "<hr/>";
    displayOption($record);
}

function displayOption($record){
    echo "<div class=\"option\" >
            <a class=\"option element\" href=\"index.php?q=repository&categ=create&sub=child&id=". $record->getRecordId() ." \">Ajouter sous-dossier</a>
            <a class=\"option element\" href=\"index.php?q=repository&categ=create&sub=update&id=". $record->getRecordId() ." \">Modifier</a>
            <a class=\"option element\" href=\"index.php?q=repository&categ=create&sub=export&id=". $record->getRecordId() ." \">exporter</a>
            <a class=\"option element\" href=\"index.php?q=repository&categ=create&sub=delete&id=". $record->getRecordId() ." \">Supprimer</a>
        </div>";
}

function RecordsSubList($record){
    $record->verificationRecordsChild();
    if($record->verificationRecordsChild()){

        echo "<br/><a href=\"index.php?q=repository&categ=search&sub=recordChild&id=". $record->getRecordId() . "\"> Voir les sous élements</a>";
    } else{
        echo "";
    }
}
function optionNavigation($record){
    echo "
        <div class=\"navigation\">
            <a href=\"index.php?q=repository&categ=loan&sub=loan&id=". $record->getRecordId() ." \">Reserver</a>
            </div>
        <div class=\"navigation\">
            <a href=\"index.php?q=repository&categ=create&sub=print&id=". $record->getRecordId() ." \">Imprimer</a>
         </div>
         <div class=\"navigation\">
            <a href=\"index.php?q=repository&categ=create&sub=addDolly&id=". $record->getRecordId() ." \">Ajouter dans chariot</a>
         </div>
         
         <div class=\"navigation\">
            <a href=\"index.php?q=repository&categ=create&sub=export&id=". $record->getRecordId() ." \">Exporter</a>
         </div>
         ";
}
function optionNavigationAdvanced($record){
    echo "
        <div class=\"navigation\">
            <a href=\"index.php?q=repository&categ=loan&sub=loan&id=". $record->getRecordId() ." \">Reserver</a>
            </div>
        <div class=\"navigation\">
            <a href=\"index.php?q=repository&categ=create&sub=print&id=". $record->getRecordId() ." \">Imprimer</a>
         </div>
         <div class=\"navigation\">
            <a href=\"index.php?q=repository&categ=create&sub=addDolly&id=". $record->getRecordId() ." \">Ajouter dans chariot</a>
         </div>
         
         <div class=\"navigation\">
            <a href=\"index.php?q=repository&categ=create&sub=export&id=". $record->getRecordId() ." \">Exporter</a>
         </div>
         <hr style=\"margin-top:20px;margin-bottom:20px;\"/>
         <div class=\"navigation\">
         <a class=\"option element\" href=\"index.php?q=repository&categ=create&sub=child&id=". $record->getRecordId() ." \">Ajouter sous-dossier</a>
         </div>
         <div class=\"navigation\">   
         <a class=\"option element\" href=\"index.php?q=repository&categ=create&sub=update&id=". $record->getRecordId() ." \">Modifier</a>
         </div>
         <div class=\"navigation\">  
         <a class=\"option element\" href=\"index.php?q=repository&categ=create&sub=delete&id=". $record->getRecordId() ." \">Supprimer</a>
         </div>
         ";
}

function displayParentTitle($record){
    $parentValue = NULL;
    
    if($record->verificationRecordsParent() == TRUE){
        $id_parent = $record -> getRecordLinkId();
        $parent = new record();
        $parent -> setRecordId($id_parent);
        $parent -> getRecordById();
        $parentValue = "<tr><th class=\"element\"> In <td class=\"element\"><a href=\"index.php?q=repository&categ=search&sub=display&id=". $parent -> getRecordId()."\">". $parent -> getRecordTitle() ."</a></td></tr>";
    }else{
        $parentValue = "";
    }
    
    return $parentValue;
}





?>