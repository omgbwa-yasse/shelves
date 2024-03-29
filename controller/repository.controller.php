<?php

    /* default  */    
if($_GET['q'] == "repository"){

        /* redirige si il y'a pas de categorie */
        if(empty($_GET['categ'])){
            include "views/repository/search/allrecords.inc.php" ;
        }else{
        /* Case create */
        if($_GET['q'] == "repository" && $_GET['categ'] == "create" && !empty($_GET['sub'])){
            switch($_GET['sub']){
                case "new" : include "views/repository/records/createRecords.inc.php";
                break ;
                case "newSave" : include "views/repository/records/saveRecords.inc.php";
                break ;
                case "update" : include "views/repository/records/updateRecords.inc.php";
                break ;
                case "delete" : include "views/repository/records/deleteRecord.inc.php"; 
                break ;
                case "child" : include "views/repository/records/createRecordsSub.inc.php";
                break;
                case "addRecordInContainer" : include "views/repository/records/addRecordInContainer.inc.php";
                break ;
                case "saveRecordInContainer" : include "views/repository/records/saveRecordInContainer.inc.php";
                break ;
                default : include "views/repository/records/createRecords.inc.php";
            }
        }

              /* Case create */
              if($_GET['q'] == "repository" && $_GET['categ'] == "recordInContainer" && !empty($_GET['sub'])){
                switch($_GET['sub']){
                    case "addInContainer" : include "";
                    break ;
                    case "InsertRecord" : include "views/repository/records/updateRecords.inc.php";
                    break ;
                    case "link" : include "views/repository/records/addRecordInContainer.inc.php"; 
                    break ;
                    case "save" : include "views/repository/records/saveRecordInContainer.inc.php";
                    break ;
                    default : include "views/repository/records/saveRecords.inc.php";
                }
            }
        
        /* Case search */
        else if($_GET['q'] == "repository" && $_GET['categ'] == "search" && !empty($_GET['sub'])){
            switch($_GET['sub']){
                case "last" : include "views/repository/search/lastRecords.inc.php";
                break ;
                case "display" : include "views/repository/search/displayRecordsSelf.inc.php";
                break ;
                case "allrecords" : include "views/repository/search/allrecords.inc.php";
                break ;
                case "classification" : include "views/repository/search/searchByClassification.inc.php"  ;
                break ;
                case "class" : include "views/repository/search/searchByClass.inc.php";
                break ;  
                case "byKeyword" : include "views/repository/search/searchAllKeyword.inc.php";
                break ;
                case "byKeywordId" :  include "views/repository/search/searchByKeywordId.inc.php";
                break ;
                case "searchByKeyword" : include "views/repository/search/searchByKeyword.inc.php" ;
                break ;
                case "byDateForm" : include "views/repository/search/searchRecordsByDates.inc.php";
                break ;
                case "organization" : include "views/repository/search/displayByOrganization.inc.php";
                break ;
                case "allOrganization" : include "views/repository/search/searchByOrganization.inc.php";
                break ;
                case "byDatesResult" : include "views/repository/search/displayRecordsByDates.inc.php";
                break ;
                case "recordChild" : include "views/repository/search/displayRecordsChild.inc.php";
                break ;
                case "words" : include "views/repository/search/displayQuery.inc.php";
                break ;
                case "container" : include "views/repository/search/displayRecordsInContainer.inc.php";
                break ;
                case "deposit" : include "views/repository/search/searchByDeposit.inc.php";
                break ;
                // default : include "views/repository/search/allrecords.inc.php";
                    }
                
                }
                else if($_GET['q'] == "repository" && $_GET['categ'] == "dolly" && !empty($_GET['sub'])){
                    switch($_GET['sub']){
                        case "all" : include "views/repository/dolly/allRecordDolly.inc.php";
                        break ;
                        case "create" : include "views/repository/dolly/formRecordDolly.inc.php";
                        break ;
                        case "save" : include "views/repository/dolly/saveRecordDolly.inc.php";
                        break ;
                        case "allrecords" : include "views/repository/dolly/dollyRecords.views.php";
                        break ;
                        case "view" : include "views/repository/dolly/viewDollyRecord.inc.php";
                        break ;
                        case "addRecord" : include "views/repository/dolly/addRecordInDollyRecord.inc.php";
                        break ;
                        case "addRecords" : include "views/repository/dolly/addRecordsInDollyRecord.inc.php";
                        break ;
                        case "saveRecords" : include "views/repository/dolly/saveRecordsInDollyRecord.inc.php";
                        break ;
                        case "exportRecords" :
                        case "printRecords" :
                        case "deleteRecords" :
                        case "updateClasse":
                        case "updateOrganization" :
                        case "updateContainer" :
                        case "updateStatus" :
                        case "updateSupport" :
                        case "updateParentRecord" : 
                        case "updateObservation" :
                        case "updateDates" : 
                        case "tranfer" :
                        case "tranfered" :
                        include "views/repository/dolly/dollyRecordsAction.inc.php";
                        break ;
                        default : include "views/repository/dolly/allRecordDolly.inc.php";
                }}
         }
    } 
?>