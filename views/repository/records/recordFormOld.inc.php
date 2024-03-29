<?php
require_once 'models/repository/recordsManager.class.php';
require_once 'models/repository/record.class.php';
require_once 'models/tools/organization/organizationManager.class.php';
require_once 'models/tools/organization/organization.class.php';
require_once 'models/tools/classification/classesManager.class.php';
    

$records = new recordsManager();
$allClasses = new activityClassesManager();
$allClasses = $allClasses ->allClasses();
$allStatut = $records -> getAllStatutTitle();
$allSupport = $records -> getAllSupportTitle();
$sqlLastNui = $records -> getLastNui();
$descriptionLevels = $records -> getAllDesciptionLevels();

$organisation = new organizationManager();
$allOrganization = $organisation -> getAllOrganization();

    

foreach($sqlLastNui as $Nui){
    echo "<div class=\"section\"> <h2>Description archivistique</h2></div>";
    echo "<div id=\"notice\">Le dernier enregistrement est : ". $Nui['nui'];
    echo "</div>";
}


?>

<table class="formular">
<tr>
<td class="titre"> Niveau description </td><td>
<select name="level_id">
<?php if(isset($descriptionLevels)){
    foreach($descriptionLevels as $descriptionLevel){
        echo "<option value=\"".$descriptionLevel['id'] ."\"";
        if($descriptionLevel['title'] == "Dossier"){ echo "selected"; }
        echo ">". $descriptionLevel['title'] ."</option>"; 
    }
} 
?>
    
</td> </tr>
<tr> <td class="titre"> N° inventaire </td>  <td> <input type="text" name="nui" size="30"></td> </tr>
<tr> <td class="titre"> Intitulé / analyse </td>  <td> <input type="text" name="title" size="70"></td> </tr>
<tr> <td class="titre"> Producteurs (;)  </td>  <td> <input type="text" name="authors" size="70"></td> </tr>
<tr> <td class="titre"> Dates extrêmes</td>  <td><input type="text" name="date_start" id="date"> au <input type="text" name="date_end" id="date"> </td></tr>
<tr> <td class="titre"> Observation</td>  <td><input type="text-area" name="observation" width="70"> </td></tr>

<tr><td class="titre"> Classe</td><td>
<select name="classification_id">
<?php if(isset($allClasses)){
    foreach($allClasses as $class){
        echo "<option value=\"".$class['id']."\">".$class['code']." - ".$class['title'] ."</option>"; 
    }
} ?>
</select>
</td></tr>

<tr><td class="titre">Détenteur : </td><td>
<select name="organization_title">
<?php if(isset($allOrganization)){
    foreach($allOrganization as $id){
        $organization = new organization();
        $organization ->setOrganizationById($id['id']);
        echo '<option>'.$organization ->getOrganizationTitle() .'</option>'; 
    }
} ?>
</select>
</td></tr>
<tr><td class="titre">Statut</td><td>
<select name="statut">
<?php if(isset($allStatut)){
    foreach($allStatut as $statut){
        if ($statut['statut'] == "disponible"){
            echo '<option>'. $statut['statut'].'</option>';
        } else{
            echo '<option>'. $statut['statut'].'</option>';
        }  }} 
?>
</select>
</td></tr>
<tr> <td class="titre"> Support</td><td> 
<select name="support">
<?php if(isset($allSupport)){
    foreach($allSupport as $support){
        if ($support['support'] == "papier"){
            echo '<option>'. $support['support'].'</option>';
        } else{
            echo '<option>'. $support['support'].'</option>';
        }
    }
} ?>
</select>
</td></tr>
<tr> <td class="titre"> Mots-cléfs (<b style="color:red;">;</b>): </td> <td> <input type="text" name="keywords" size="70"> </td>  </tr>
<tr> <td> <input type="submit" size="30"> </td>  <td> <input type="reset" size="30"></td></tr>
</table>