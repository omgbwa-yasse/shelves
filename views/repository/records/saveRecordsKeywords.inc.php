<?php
require_once 'models/repository/keyword.class.php';


    $Keyword = new keyword();
// Recupération de l'ID sur la base NUI
    $Keyword -> setRecordNui($_POST['nui']);
    $Keyword -> setRecordIdByNui();
    
    // Boucle de découpage, controle, lie ou insertion d'un mot-clé
    $text = htmlspecialchars($_POST['keywords']);
    $lenText = strlen($text);
    $text = explode(';', $text, $lenText);
    $text = array_filter($text);
    foreach($text as $tab){
        $Keyword -> setKeyword($tab);
        if($Keyword->KeywordVerification() == TRUE){
            $Keyword ->linkKeywordRecord(); 
        } else {
            $Keyword ->saveNewKeyword($tab);
        } 
    }
?>