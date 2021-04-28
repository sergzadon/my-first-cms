<?php include "templates/include/header.php" ?>

<h1> Подкатегория </h1>
<h2><?php echo htmlspecialchars ($results['pageHeading'])?> </h2>
<?php if($results["subcategories"]){ ?>
<h3 class="subcategoriesDescription"> <?php echo htmlspecialchars
($results['subcategoryId']->description) ?> </h3> 
<?php }?>

<ul id="tablesubcat" class="subcatarchive">
    
<?php foreach ($results["articles"] as $article) { ?>
    
    <li>
        <h2>
            <span class="pubDate">
                <?php echo date("j F Y", $article->publicationDate) ?>
            </span> 
            <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>">
                    <?php echo htmlspecialchars( 
                            $article->title )?>
            </a>
            
            <h4>
            <div class="catSub">
                Категория
                <?php echo ($results['categories'][$article->categoryId]->name) ?>
            </div> 
            </h4>
            <?php if(!$results['subcategoryId'] && $article->subcategoryId) { ?>
                <span class="subcategory">
                    in
                    <a href=".?action=subcatArchive&amp;subcategoryId=<?php echo
                    $article->subcategoryId ?>">
                    <?php echo htmlspecialchars($results["subcategories"][$article->subcategoryId]->titleSubcat) ?>
                    </a>
                </span>
                
           <?php } ?>
            
            
        </h2> 
    </li>
<?php } ?>
</ul>