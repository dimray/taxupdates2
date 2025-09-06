 <?php

    $fields = require ROOT_PATH . "config/mappings/" . $_SESSION['type_of_business'] . ".php" ?>


 <table class="copy-element">

     <tbody>
         <?php foreach ($fields['cumulative'] as $category => $item): ?>
             <?php foreach ($item as $field_name): ?>

                 <tr>
                     <td><?= $field_name ?></td>
                     <td>99.99</td>
                 </tr>

             <?php endforeach; ?>
         <?php endforeach; ?>
     </tbody>
 </table>