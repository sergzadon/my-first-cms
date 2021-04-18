<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
	  
    <h1>Все авторы</h1>

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>


    <?php if ( isset( $results['statusMessage'] ) ) { ?>
            <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>
        <?php
//            echo "<pre>";
//            print_r($results['users']);
//            echo "<pre>";
//            die();
        ?>
    <table>
            <tr>
              <th>Дата регистрации</th>
              <th>Имя автора</th>             
            </tr>
        
    <?php foreach ( $results['users'] as $user ) { ?>
            <tr onclick="location='admin.php?action=viewAuthors&amp;userId=<?php echo $user->id?>'">
              <td><?php echo date('j M Y', $user->registrationDate)?></td>
              <td><?php echo $user->login ?></td>

            </tr>
            

    <?php } ?>

          </table>

          <p><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>

<!--          <p><a href="admin.php?action=newUser">Добавить нового пользователя</a></p>-->

<?php include "templates/include/footer.php" ?> 
