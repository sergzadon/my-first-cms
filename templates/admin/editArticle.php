<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
<!--            <?php echo "<pre>";
            print_r($results);
            "<BR/>";
            echo 345; 
            print_r($data);
        echo "<pre>"; ?> Данные о массиве $results и типе формы передаются корректно-->
        <?php
        // определение авторов статьи
        if(isset($_GET['articleId'])) {
           $listAuthors = $Authors->getAuthors((int)$_GET['articleId']);
           function authorsArticle($list) {
               $arrId = [];
               foreach($list as $idAuthor){
                  $arrId[] = $idAuthor->id;
               }
               return $arrId;
           }
           $idAuthors = authorsArticle($listAuthors);
//            echo "<pre>";
//            print_r($idAut);
//            echo "<pre>";
//            die();
        }
        ?>

        <h1><?php echo $results['pageTitle']?></h1>

        <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
            <input type="hidden" name="id" value="<?php echo $results['article']->id ?>">

    <?php if ( isset( $results['errorMessage'] ) ) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

            <ul>

              <li>
                <label for="title">Article Title</label>
                <input type="text" name="title" id="title" placeholder="Name of the article" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['article']->title )?>" />
              </li>

              <li>
                <label for="summary">Article Summary</label>
                <textarea name="summary" id="summary" placeholder="Brief description of the article" required maxlength="1000" style="height: 5em;"><?php echo htmlspecialchars( $results['article']->summary )?></textarea>
              </li>

              <li>
                <label for="content">Article Content</label>
                <textarea name="content" id="content" placeholder="The HTML content of the article" required maxlength="100000" style="height: 30em;"><?php echo htmlspecialchars( $results['article']->content )?></textarea>
              </li>

              <li>
                <label for="categoryId">Article Category</label>
                <select name="categoryId">
                  <option value="0"<?php echo !$results['article']->categoryId ? " selected" : ""?>>(none)</option>
                <?php foreach ( $results['categories'] as $category ) { ?>
                  <option value="<?php echo $category->id?>"<?php echo ( $category->id == $results['article']->categoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $category->name )?></option>
                <?php } ?>
                </select>
              </li>
              <li>
                <label for="categoryId">Subcategory</label>
                <select name="subcategoryId">
                  <option value="0"<?php echo !$results['article']->subcategoryId ? " selected" : ""?>>(none)</option>
                <?php foreach ( $results['subcategories'] as $subcategory ) { ?>
                  <option value="<?php echo $subcategory->id?>"<?php echo ( $subcategory->id == $results['article']->subcategoryId ) ? " selected" : ""?>><?php echo htmlspecialchars( $subcategory->titleSubcat)?></option>
                <?php } ?>
                </select>
              </li>
                    <li>
                      <label for="authors[]">Все авторы</label>
                      <select name="authors[]" multiple="multiple">
                          <?php foreach ($results['users'] as $user) { ?>
                              <option value="<?php echo $user->id?>"
                                  <?php echo (isset($idAuthors) &&  in_array($user->id, $idAuthors)) ? " selected" : "" ?>><?php echo htmlspecialchars($user->login)?></option>
                          <?php } ?>
                      </select>
                    </li>
                    <?php
                        if(isset($_GET['articleId'])) { ?>
                        <li>
                            <div id="authors"> Авторы статьи<?php
                                 $count = 0;
                                 foreach($listAuthors as $author) {
                                     echo " ".$author->login ." ";
                                     $count += 1;
                                     if($count != count($listAuthors)) {
                                         echo ",";
                                     }
                                 } 
                             ?>
                            </div>
                       </li>
                  <?php } ?>

              <li>
                <label for="active">Active</label>
                <INPUT NAME="ActiveArticle" TYPE="CHECKBOX" VALUE="1"
                    <?php
                        if ($results['article']->ActiveArticle == 1){
                           echo "checked";
                        }
                    ?> >  
              </li>

              <li>
                <label for="publicationDate">Publication Date</label>
<!--                <input type="date" name="publicationDate" id="publicationDate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['article']->publicationDate ? date( "Y-m-d", $results['article']->publicationDate ) : "" ?>" />-->
                <input type="date" name="publicationDate" id="publicationDate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo $results['article']->publicationDate ? date( "Y-m-d", $results['article']->publicationDate ) : "" ?>" />
              </li>
              


            </ul>
            <div class="buttons">
              <input type="submit" name="saveChanges" value="Save Changes" />
              <input type="submit" formnovalidate name="cancel" value="Cancel" />
            </div>

        </form>

    <?php if ($results['article']->id) { ?>
          <p><a href="admin.php?action=deleteArticle&amp;articleId=<?php echo $results['article']->id ?>" onclick="return confirm('Delete This Article?')">
                  Delete This Article
              </a>
          </p>
    <?php } ?>
	  
<?php include "templates/include/footer.php" ?>

              