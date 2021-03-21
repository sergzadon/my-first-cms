<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

    <h1><?php echo $results['pageTitle']?></h1>
    
    <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
            <input type="hidden" name="userId" value="<?php echo $results['user']->id ?>">
            
    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>
            <ul>
              <li>
                <label for="login">login</label>
                <input type="text" name="login" id="login" placeholder="Имя пользователя" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['user']->login )?>" />
              </li>

              <li>
                <label for="password">Passsword</label>
                <input  type="text" name="password" id="password" placeholder="Пароль" required maxlength="300" value="<?php echo htmlspecialchars( $results['user']->password )?>" />
              </li>

              <li>
                <label for="registrationDate">Registration Date</label>
                <input type="date" name="registrationDate" id="registrationDate" placeholder="YYYY-MM-DD" required maxlength="15" value="<?php echo $results['user']->registrationDate ? date( "Y-m-d", $results['user']->registrationDate ) : "" ?>" />
              </li>
              
              <li>
                <label for="action">Active</label>
                <INPUT NAME="action" TYPE="CHECKBOX" VALUE="1"
                    <?php
                        if ($results['user']->action == 1){
                           echo "checked";
                        }

                    ?>     
                >   
              </li>
 
            </ul>
            
            
            <div class="buttons">
              <input type="submit" name="saveChanges" value="Save Changes" />
              <input type="submit" formnovalidate name="cancel" value="Cancel" />
            </div>
            
        </form>

        
        

    <?php if ($results['user']->id) { ?>
          <p><a href="admin.php?action=deleteUser&amp;userId=<?php echo $results['user']->id ?>" onclick="return confirm('Delete This User?')">
                  Delete This User
              </a>
          </p>
    <?php } ?>
	  
<?php include "templates/include/footer.php" ?>
