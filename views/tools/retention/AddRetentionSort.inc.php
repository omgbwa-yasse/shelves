<?php
require_once 'models/tools/retention/retention_sort.class.php';
$retention_sort = new retention_sort();
if (isset($_POST['retention_sort_code']) && isset($_POST['retention_sort_title']) && isset($_POST['retention_sort_comment'])) {
  $retention_sort->addretention_sort();
}
?>
<h1>Ajouter un tri de conservation</h1>
<form method="POST" action="index.php?q=tools&categ=retentonsort&sub=addretentionsort">
  <table>
    <tr>
      <td><label for="retention_sort_code">Code du tri de conservation:</label></td>
      <td><input type="text" id="retention_sort_code" name="retention_sort_code"></td>
    </tr>
    <tr>
      <td><label for="retention_sort_title">titre du tri de conservation:</label></td>
      <td><input type="text" id="retention_sort_title" name="retention_sort_title"></td>
    </tr>
    <tr>
      <td><label for="retention_sort_comment">Commentaire :</label></td>
      <td><textarea id="retention_sort_comment" name="retention_sort_comment"></textarea></td>
    </tr>
    <tr>
      <td><input type="submit" value="Submit"></td>   
      <td><input type="reset" value="Cancel"></td>
    </tr> 
  </table>
</form>
