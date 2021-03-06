
<?php include "templates/include/header.php" ?>
    <ul id="headlines">
<?php       
//    echo "<pre>";
//    print_r($results['articles']);
//    echo "</pre>";
//    die();
?>
    <?php foreach ($results['articles'] as $article) { ?>
        <li class='<?php echo $article->id?>'>
            <h2>
                <span class="pubDate">
                    <?php echo date('j F', $article->publicationDate)?>
                </span>
                
                <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>">
                    <?php echo htmlspecialchars( $article->title )?>
                </a>
                
                <?php if (isset($article->categoryId)) { ?>
                    <span class="category">
                        Категория 
                        <a href=".?action=archive&amp;categoryId=<?php echo $article->categoryId?>">
                            <?php echo htmlspecialchars($results['categories'][$article->categoryId]->name )?>
                        </a>
                    </span>
                <?php } 
                else { ?>
                    <span class="category">
                        <?php echo "Без категории"?>
                    </span>

                <?php } ?>
                
                <?php if (isset($article->subcategoryId) && $article->subcategoryId > 0) { ?>    
                    <span class="subcategory">
                        Подкатегория 
                        <a href=".?action=subcategoryArchive&amp;subcategoryId=<?php echo $article->subcategoryId?>">
                            <?php if($article->subcategoryId > 0)  echo htmlspecialchars($results["subcategories"][$article->subcategoryId]->titleSubcat)?>
                        </a>
                    </span>
               
                    <span class="subcategory">
                        Авторы
                         <h3>
                       <?php 
                            $count = 0;
                            $listAuthors = $Authors->getAuthors($article->id);
                            foreach($listAuthors as $Authors ) {
//                                echo $Authors->login." ";
//                                $count += 1;
//                                if($count != count($listAuthors)) {
//                                    echo ",";
//                                } ?>
                                <a href="admin.php?action=viewAuthors&amp;userId=<?php echo $Authors->id?>">
                                <?php echo htmlspecialchars( 
                                        $Authors->login );
                                $count += 1;
                                if($count != count($listAuthors)) {
                                    echo ",";
                                } ?>
                             </a>
                          <?php  } 
                            $count = 0;
                        ?>
                             
                        </h3>
                    </span>
                
            </h2>
                <?php }
            
                else { ?>
                    <h2>
                        <span class="subcategory">
                            <?php echo "Без подкатегории"?>
                        </span>
                    </h2>
                <?php } ?>
           
<!--            <p class="summary"><?php echo htmlspecialchars($article->fiftychars)?></p>-->
                
            <p class="summary"><?php echo htmlspecialchars($article->summary)?></p>
            <p class="summary"><?php echo htmlspecialchars($article->fiftychars)?></p>
            <img id="loader-identity" src="JS/ajax-loader.gif" alt="gif">
            <ul class="ajax-load">
                <li><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="ajaxArticleBodyByPost" data-contentId="<?php echo $article->id?>">Показать продолжение (POST)</a></li>
                <li><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="ajaxArticleBodyByGet" data-contentId="<?php echo $article->id?>">Показать продолжение (GET)</a></li>
                <li><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="ajaxArticleBodyByPostNew" data-summury="<?php echo $article->id?>">(POST) -- NEW</a></li>
                <li><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="ajaxArticleBodyByGetNew" data-summury="<?php echo $article->id?>">(GET)  -- NEW</a></li>
            </ul>
            <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="showContent" data-contentId="<?php echo $article->id?>">Показать полностью</a>
        </li>
    <?php } ?>
    </ul>
    <p><a href="./?action=archive">Article Archive</a></p>
<?php include "templates/include/footer.php" ?>

    
