<?php
include_once "template/header.inc.php"
?>

<header>
 <?php include_once 'template/search.inc.php'; ?> 
 <?php if(empty($_GET['q'])){ $_GET['q'] = "repository"; } ?> 
<nav>
    <ul>
        <li class="<?php if ($_GET['q'] =="mail") {echo "active"; } else  {echo "";}?>"><a href ="index.php?q=mail&categ=search&sub=allmails">
        <img src="template/css/svg/mail.svg" alt="">    
        Courriel</a></li>
        <li class="<?php if ($_GET['q'] =="repository") {echo "active"; } else  {echo "";}?>"><a href ="index.php?q=repository&categ=search&sub=allrecords">
        <img src="template/css/svg/repertory.svg" alt=""> Repertoire</a></li>

        <li class="<?php if ($_GET['q']=="transfer") {echo "active"; } else  {echo "";}?>"><a href ="index.php?q=transfer&categ=search&sub=all">
        <img src="template/css/svg/documents-svgrepo-com.svg" alt="">Transfert </a></li>

        <li class="<?php if ($_GET['q']=="loan") {echo "active"; } else  {echo "";}?>"><a href ="index.php?q=loan&categ=search&sub=allloan">
        <img src="template/css/svg/communication-conversation-help-svgrepo-com.svg" alt="">Communication</a></li>



        <li class="<?php if ($_GET['q']=="audit") {echo "active"; } else  {echo "";}?>"><a href ="index.php?q=audit&categ=create&sub=add">
        <img src="template/css/svg/audit-report-svgrepo-com.svg" alt=""> Audit</a></li>


        <li class="<?php if ($_GET['q']=="deposit") {echo "active"; } else  {echo "";}?>"><a href ="index.php?q=deposit&categ=search&sub=all">
         <img src="template/css/svg/deposit-svgrepo-com.svg" alt=""> Dépôt</a></li>


        <li class="<?php if ($_GET['q']=="dolly") {echo "active"; } else  {echo "";}?>"><a href ="index.php?q=dolly&categ=records&sub=allDolly">
        <img src="template/css/svg/dolly-solid-svgrepo-com.svg" alt="">
        Chariot</a></li>
        <li class="<?php if ($_GET['q']=="tools") {echo "active"; } else  {echo "";}?>"><a href ="index.php?q=tools&categ=all&sub=all">
         <img src="template/css/svg/tools-svgrepo-com.svg" alt="">
        Outils de gestion</a></li>
        <li class="<?php if ($_GET['q']=="setting") {echo "active"; } else  {echo "";}?>"><a href ="index.php?q=setting"> 
        <img src="template/css/svg/setting-svgrepo-com.svg" alt="">
        Parametre</a></li>
    </ul>
</nav>
</header>

